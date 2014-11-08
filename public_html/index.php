<?php

// SET DEFAULT TIME ZONE IN CASE NOT SET IN INI
date_default_timezone_set('America/New_York');

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$bootstrap = new \SmsBus\Bootstrap();

$app['debug'] = false;

// SERVICES

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());

// Lazy load controllers & gateways
$app['repos.location'] = $app->share(function() use($app, $bootstrap) {
    return new \SmsBus\Db\LocationTable($bootstrap->getConfig());
});

$app['route_location.controller'] = $app->share(function() use($app) {
    return new \SmsBus\Controller\RouteLocation($app['repos.location']);
});

// ROUTES
$routeUrls = [
    'route_location' => '/routes/{routeShort}/trips/{tripId}/location',
];
$app->post($routeUrls['route_location'], 'route_location.controller:postAction')
    ->assert('routeShort', '[0-9a-zA-Z]+')
    ->assert('tripId', '\d+');
$app->get($routeUrls['route_location'], 'route_location.controller:getAction')
    ->assert('routeShort', '[0-9a-zA-Z]+')
    ->assert('tripId', '\d+');

// REDIRECT GET REQUESTS TO THE MIAMI WIKI
$app->get('/', function() use ($app) {
    return $app->redirect('http://miamiwiki.org/SMSBus_Project');
});

$app->run();
