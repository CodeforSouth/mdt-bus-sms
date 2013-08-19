<?php

return array(
	"gtfs_exchange" => array(
		"feeds" => array(
			"mdt" => "http://www.gtfs-data-exchange.com/agency/miami-dade-transit/feed",
            "bct" => "http://www.gtfs-data-exchange.com/agency/broward-county-transit/feed",
			//"brampton" => "http://www.gtfs-data-exchange.com/agency/brampton-transit/feed",
		),
	),
	"twilio" => array(
		"send_number" => "+17866296468",
		"sid" => "AC18f0b1def7b9661468dd23c4f2917df7",
		"auth_token" => "ed1e5ec6e6fad2bc1840e73da9fdc6f9",
	),
    "mapquest" => array(
        "app_key" => "Fmjtd%7Cluub256bn0%2C8a%3Do5-9u8214",
    ),
	"db" => array(
		"driver" => "PDO",
		"dsn" => "mysql:host=localhost;dbname=aramonc_smsbus",
		"user" => "aramonc_gtfs",
		"pass" => "Vtb$;1lT,.fO",
	),
	"translation" => array(
		"base_dir" => __DIR__ . "/../translate/",
		"file_pattern" => "%s/language.php",
		"accepted" => array(
			"en" => "en-US",
			"es" => "es-ES",
			"cr" => "fr-FR",
            "fr" => "fr-FR",
		),
	),
);	