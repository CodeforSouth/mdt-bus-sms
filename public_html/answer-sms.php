<?php
require "../vendor/autoload.php";
$config = new \Zend\Config\Config(include __DIR__ . "/../config/config.php");
$twilioCon = clone $config->twilio;
if(!isset($_POST['To']) || $_POST['To'] !== (string) $twilioCon->send_number) {
	header('Location: http://miamiwiki.org/SMSBus_Project');
	exit(0);
}

$smsTable = new \SmsBus\Db\ReceivedSMSTable();
$result = $smsTable->save($_POST);
$body = (string) $_POST['Body'];
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
$times = $timesTable->fetchByBusStop($stop, $bus);
$message = '';
if(!$times) {
	$message = "There was an error fetching the stop times.";
} else if (is_array($times) && count($times) > 0) {
	foreach($times as $stop_time) {
		$message .= $stop_time['arrival_time'] . ", ";
	}
} else {
	$message = "Bus " . $bus . " will not stop at " . stop . " any more today";
}

$twiml = new \Services_Twilio_Twiml();
$twiml->sms($message);
header("content-type: text/xml");
print $twiml;
?>