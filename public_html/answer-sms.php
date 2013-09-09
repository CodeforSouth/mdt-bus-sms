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
$controllers = new StopController($app, $bootstrap->getConfig(), $bootstrap->getTranslator());

// FRONT CONTROLLER
$app->post('/', function (Request $request) use ($app, $bootstrap) {

    // REDIRECT TO THE MIAMI WIKI IF THE REQUEST IS NOT FROM TWILIO
    if($request->get('AccountSid', false) !== $bootstrap->getConfig()->twilio->sid) {
        return $app->redirect('http://miamiwiki.org/SMSBus_Project');
    }

    $sanitized = $bootstrap->getFilter()->sanitizePost($request->request->all());

    // SAVE THE POST TO THE DB FOR DEBUGGING
    $smsTable = new \SmsBus\Db\ReceivedSMSTable();
    $smsTable->save($sanitized);

    $message = $bootstrap->translateMessage($sanitized['Body']);

    // IF AFTER FILTERING & TRANSLATION, THE MESSAGE IS EMPTY, RETURN AN ERROR MESSAGE
    if(empty($message)) {
        $bootstrap->getTwiml()->sms("Please send more information");
        return $bootstrap->getResponse();
    }

    // BUILD COMMAND ROUTE FROM MESSAGE
    $command = $bootstrap->getRouter()->getRoute($message);
    $response = $app->handle(Request::create($command), HttpKernelInterface::SUB_REQUEST, false);

//
//
//    // CREATE A SUB REQUEST TO HANDLE THE DIFFERENT APP COMMANDS
//    if($words[0] == 'stop' && $words[1] == 'at') {
//        // REMOVE THE COMMAND WORDS
//        $action =  array_shift($words) . '/' . array_shift($words);
//        $addressFull = implode(' ', $words);
//        $subRequest = Request::create('/' . $bootstrap->getTranslator()->getLocale() . '/' . $action . '/' . urlencode($addressFull));
//    } else {
//        $subRequest = Request::create('/' . $bootstrap->getTranslator()->getLocale() . '/' . implode('/', $words));
//    }
//    $response = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);

    // RETURN THE TWIML RESPONSE
//    $bootstrap->getTwiml()->sms($response->getContent());
    return $bootstrap->getResponse();
});

// REDIRECT GET REQUESTS TO THE MIAMI WIKI
$app->get('/', function() use ($app) {
    return $app->redirect('http://miamiwiki.org/SMSBus_Project');
});

// DEFINE THE CONTROLLERS FOR THE ACTUAL BUSINESS LOGIC OF THE APP
$app->mount('/{locale}/stop/at', $controllers->getStops());
$app->mount('/{locale}/stop', $controllers->getStopBusController());

$app->run();