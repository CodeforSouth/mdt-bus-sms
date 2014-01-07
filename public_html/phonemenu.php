<?php

// @start snippet
/* Define Menu */
$web = array();
$web['default'] = array(1=>'english',2=>'spanish', 3=>'french');

/* Get the menu node, index, and url */
$node = $_REQUEST['node'];
$index = (int) $_REQUEST['Digits'];
$url = 'http://'.dirname($_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF']).'/phonemenu.php';

/* Check to make sure index is valid */
if(isset($web[$node]) || count($web[$node]) >= $index && !is_null($_REQUEST['Digits']))
	$destination = $web[$node][$index];
else
	$destination = NULL;
// @end snippet

// @start snippet
/* Render TwiML */
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response>\n";

switch($destination) {
	case 'english': ?>
		<Say voice="alice">Text this number to learn when your next bus will arrive.  Just text the word, BUS, followed by your bus number, followed by the word, AT, followed by the STOP LOCATION ID</Say>
		<?php break;
	case 'spanish': ?>
		<Say voice="alice" language="es-MX">Envia un mensaje a este Número para saber Cuando llegara el próximo autobús. Sólo envia la palabra, BUS, seguido de su número de autobús, seguido de la palabra, AT, seguido por el numero del lugar de parada</Say>
		<?php break;
	case 'french': ?>
		<Say voice="alice" language="fr-FR">Texte ce numéro pour prendre connaissance de l'arrive de votre prochain autobus. Texte simplement le mot, BUS, suivi par le numéro de votre autobus, suivi par le mot, AT, et suivi du nom de la ville de votre destination finale </Say>
		<?php break;
	default: ?>
		<Gather action="<?php echo 'http://' . dirname($_SERVER["SERVER_NAME"] .  $_SERVER['PHP_SELF']) . '/phonemenu.php?node=default'; ?>" numDigits="1">
			<Say voice="alice">Hello and welcome to the Text My Bus App</Say>
			<Say voice="alice">For English, press 1</Say>
			<Say voice="alice" language="es-MX">Para Español, presione 2</Say>
			<Say voice="alice" language="fr-FR">Pour le Français, appuyez sur 3</Say>
		</Gather>
		<?php
		break;
}
// @end snippet

// @start snippet
if($destination && $destination != 'receptionist') { ?>
	<Pause/>
	<Say>Main Menu</Say>
	<Redirect><?php echo 'http://' . dirname($_SERVER["SERVER_NAME"] .  $_SERVER['PHP_SELF']) . '/phonemenu.php' ?></Redirect>
<?php }
// @end snippet

?>

</Response>