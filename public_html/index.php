<?php

// SET DEFAULT TIME ZONE IN CASE NOT SET IN INI
date_default_timezone_set('America/New_York');

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// REDIRECT GET REQUESTS TO THE MIAMI WIKI
$app->get('/', function() use ($app) {
    return $app->redirect('http://miamiwiki.org/SMSBus_Project');
});

$app->run();