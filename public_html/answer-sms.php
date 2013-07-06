<?php
//Bootsrapt the application
date_default_timezone_set('America/New_York');
require "../vendor/autoload.php";
$config = new \Zend\Config\Config(include __DIR__ . "/../config/config.php");
$twilioCon = clone $config->twilio;
if(!isset($_POST['AccountSid']) || $_POST['AccountSid'] !== (string) $twilioCon->sid) {
	header('Location: http://miamiwiki.org/SMSBus_Project');
	exit(0);
}
$twiml = new \Services_Twilio_Twiml();

$smsTable = new \SmsBus\Db\ReceivedSMSTable();
$result = $smsTable->save($_POST);
$body = strtolower(preg_replace('/[^a-z0-9_-\s]+/i', '', $_POST['Body']));
$route = explode(" ", $body);

//If there was no message or only one word, then return an error
if(count($route) <= 1) {
	$twiml->sms("Please send more information");
	header("content-type: text/xml");
	print $twiml;
	exit();
}

//Prepare translatation
$translator = new \Zend\I18n\Translator\Translator();
$translator->addTranslationFilePattern('phparray', $config->translation->base_dir, $config->translation->file_pattern, 'smsbus');
$accepted = array_keys(iterator_to_array($config->translation->accepted));
if(in_array($route[0], $accepted)) {
	$loc = $config->translation->accepted[array_shift($route)];
	foreach($route as $i => $command) {
		$route[$i] = $translator->translate($command, 'smsbus', $loc);
	}
}

$stop = 0;
$bus = 0;
if($route[0] === "stop") {
	$stop = intval($route[1]);
}
if ($route[2] === "bus") {
	$bus = intval($route[3]);
}
$timesTable = new \SmsBus\Db\StopTimesTable();
$stoptimes = $timesTable->fetchByBusStop($stop, $bus);
$message = '';
$times = array();
if(!$stoptimes) {
	$message = "There was an error fetching the stop times.";
} else if (is_array($stoptimes) && count($stoptimes) > 0) {
	foreach($stoptimes as $stop_time) {
		$now = new \DateTime();
		$time = $now->diff(\DateTime::createFromFormat("H:i:s", $stop_time['arrival_time']));
		$times[] = $time->h . ":" . $time->i . ":" . $time->s;
	}
	$message = implode(', ', $times);
} else {
	$message = "Bus " . $bus . " will not stop at " . stop . " any more today";
}

$twiml->sms($message);
header("content-type: text/xml");
print $twiml;
?>