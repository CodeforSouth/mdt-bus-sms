<?php
date_default_timezone_set('America/New_York');
require "../vendor/autoload.php";
$config = new \Zend\Config\Config(include __DIR__ . "/../config/config.php");
$twilioCon = clone $config->twilio;
if(!isset($_POST['AccountSid']) || $_POST['AccountSid'] !== (string) $twilioCon->sid) {
	header('Location: http://miamiwiki.org/SMSBus_Project');
	exit(0);
}

$smsTable = new \SmsBus\Db\ReceivedSMSTable();
$result = $smsTable->save($_POST);
$body = strtolower(preg_replace('/[^a-z0-9_-\s]+/i', '', $_POST['Body']));
$route = explode(" ", $body);
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
} else {
	$message = "Bus " . $bus . " will not stop at " . stop . " any more today";
}
$twiml = new \Services_Twilio_Twiml();
$twiml->sms(implode(', ', $times));
header("content-type: text/xml");
print $twiml;
?>