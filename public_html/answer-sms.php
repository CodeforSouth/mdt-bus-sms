<?php
/**
 * TextMyBus SMS App
 */

// SET DEFAULT TIME ZONE IN CASE NOT SET IN INI
date_default_timezone_set('America/New_York');

require_once __DIR__.'/../vendor/autoload.php';

use SmsBus\Bootstrap;
use SmsBus\Controller\Stop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

$app = new Silex\Application();
// BOOTSTRAP THE APPLICATION
$bootstrap = new Bootstrap();
$stopsController = new Stop($app, $bootstrap->getConfig(), $bootstrap->getTranslator());
$busController = new \SmsBus\Controller\Bus($app, $bootstrap->getConfig(), $bootstrap->getTranslator());

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

    $bootstrap->getTwiml()->sms($response->getContent());
    return $bootstrap->getResponse();
});

// REDIRECT GET REQUESTS TO THE MIAMI WIKI
$app->get('/', function() use ($app) {
    return $app->redirect('http://miamiwiki.org/SMSBus_Project');
});

// DEFINE THE CONTROLLERS FOR THE ACTUAL BUSINESS LOGIC OF THE APP
$app->mount('/bus', $busController->getBusAction());
$app->mount('/bus', $busController->getBusArrivalAction());

$app->mount('/stop', $stopsController->getStop());
$app->mount('/stop', $stopsController->getStops());

// QUICK API CALL FOR USE WITH AJAX MAP
$app->get('/stops/{a1}/{a2}/{city}/{state}', function($a1, $a2, $city = null, $state = null) use($app, $bootstrap) {
    // PREPARE ARCGIS API REQUEST
    $url = 'http://www.mapquestapi.com/geocoding/v1/address';

    $location = $a1 . ' at ' . $a2;
    $location .= isset($city) ? ', ' . $city : '' ;
    $location .= isset($city) && isset($state) ? ', ' . $state : '';

    $params = array(
        'key' => $bootstrap->getConfig()->mapquest->app_key,
        'location' => urlencode($location),
        'boundingBox' => '26.172906,-80.781097,25.267266,-79.990082', // BOUNDING BOX COVERS ALL OF DADE COUNTY DOWN TO THE DAGNY JOHNSON KEY
        'maxResults' => 3,
    );

    $consumer = new \ApiConsumer\Consumer();
    $consumer->setUrl($url);
    $consumer->setParams($params);
    $consumer->setOptions(array());
    $consumer->setResponseType('json');
    $consumer->setCallType('get');

    // GET ADDRESS GEOLOCATION
    $result = $consumer->doApiCall();

    $coords = $result['results'][0]['locations'][0]['latLng'];

    $stopsTable = new \SmsBus\Db\StopsTable();
    $stops = $stopsTable->fetchAllNear($coords['lat'], $coords['lng']);

    if(!$stops) {
        return json_encode(array('source' => $coords, 'locs' => array()));
    }

    return json_encode(array('source' => $coords, 'locs' => $stops));
});

$app->run();