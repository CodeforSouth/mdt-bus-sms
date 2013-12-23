<?php
require realpath(__DIR__ . "/../vendor/autoload.php");

$reader = new \SmsBus\ExchangeFeedReader();
$reader->consumeFeeds();
