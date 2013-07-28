<?php
/**
 * TextMyBus SMS App
 */

// SET DEFAULT TIME ZONE IN CASE NOT SET IN INI
date_default_timezone_set('America/New_York');

require_once __DIR__.'/../vendor/autoload.php';

use SmsBus\Bootstrap;
use SmsBus\StopController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

$app = new Silex\Application();
// BOOTSTRAP THE APPLICATION
$bootstrap = new Bootstrap();
$controllers = new StopController($app, $bootstrap->getTranslator());

// FRONT CONTROLLER
$app->post('/', function (Request $request) use ($app, $bootstrap) {

    // REDIRECT TO THE MIAMI WIKI IF THE REQUEST IS NOT FROM TWILIO
    if($request->get('AccountSid', false) !== $bootstrap->getConfig()->twilio->sid) {
        return $app->redirect('http://miamiwiki.org/SMSBus_Project');
    }

    // SAVE THE POST TO THE DB FOR DEBUGGING
    $smsTable = new \SmsBus\Db\ReceivedSMSTable();
    $smsTable->save($request->request->all());

    // FILTER AND RETRIEVE THE SMS MESSAGE FROM THE REQUEST
    $body = strtolower(preg_replace('/[^a-z0-9_-\s]+/i', '', $request->get('Body')));
    $words = explode(" ", $body);

    // RETURN ERROR FOR LACK OF INFORMATION
    if(count($words) <= 1) {
        $bootstrap->getTwiml()->sms("Please send more information");
        return $bootstrap->getResponse();
    }

    // CHECK THAT THE FIRST WORD IS AN ACCEPTED TRANSLATION LANGUAGE
    if($bootstrap->isAcceptedLocale($words[0])) {
        $bootstrap->setLocale(array_shift($words));
        // TRANSLATE EACH WORD OR RETURN THE WORD (NUMBERS JUST GET RETURNED)
        foreach($words as $i => $command) {
            $words[$i] = $bootstrap->getTranslator()->translate($command, 'smsbus');
        }
    }

    // CREATE A SUB REQUEST TO HANDLE THE DIFFERENT APP COMMANDS
    $subRequest = Request::create('/' . implode('/', $words) . '/' . $bootstrap->getTranslator()->getLocale());
    $response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);

    // RETURN THE TWIML RESPONSE
    $bootstrap->getTwiml()->sms($response->getContent());
    return $bootstrap->getResponse();
});

// REDIRECT GET REQUESTS TO THE MIAMI WIKI
$app->get('/', function() use ($app) {
    return $app->redirect('http://miamiwiki.org/SMSBus_Project');
});

// DEFINE THE CONTROLLERS FOR THE ACTUAL BUSINESS LOGIC OF THE APP
$app->mount('/stop/{stopId}/bus/{busId}/{locale}', $controllers->getStopBusController());
$app->mount('/stop/{stopId/{locale}', $controllers->getStopController());

$app->run();