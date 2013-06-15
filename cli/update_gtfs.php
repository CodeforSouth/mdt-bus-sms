<?php
require "../vendor/autoload.php";

$reader = new \SmsBus\ExchangeFeedReader();
$reader->consumeFeeds();
?>