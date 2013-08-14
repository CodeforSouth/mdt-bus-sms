# TextMyBusMIA

## What's the purpose of this?
"Text My Bus MIA" is an SMS system that enables the user to text the ID number of his or her bus stop and receive a message with upcoming bus times for that stop. The goal is to provide easy access to updated bus schedules for low-income individuals and other Miami-Dade County residents and visitors who rely on public transportation for commuting and getting around the county.

## Demo-ing the prototype 
- SMS in the Twilio test phone number: *(786) 629-6468*
- SMS can be in the format: "STOP [ID OF THE STOP] BUS [ROUTE NAME OF BUS]"
- For example: "STOP 2884 BUS 101"

- At this time, bus stops in Miami do not return the IDs of the stop. You can find Bus stop IDs in the format: "STOP AT [DIRECTION NUMBER] ST & [DIRECTION NUMBER] AVE, MIAMI FL"
- For example: "STOP AT NW 79 ST & 7 AVE, MIAMI FL" returns the following: "Westbound stop # 713, Northbound stop # 519, Southbound stop # 511, Eastbound stop # 705"
- To get the next bus lines use the following formats:
1. "STOP 713"
1. "STOP 713 BUS 112"

## How to set this up
- Create a web server, basic LAMP stack (Requires PHP 5.3 and above)
- Clone the repo 
- Download composer (package manager) 
curl -sS https://getcomposer.org/installer | php
php composer.phar install 
- Create a test Twilio account and set up the SMS hook to the web server, pointing to answer-sms.php
- Config file is in /config/config.php.dist - you put in the twilio send number, sid and auth_token. Don't forget to config your MySQL database in the db section accordingly. We parse the data from the feeds array under "gtfs_exchange".
- In the cli there's a command-line PHP script called update_gtfs.php - you can run this manually or set up a cron job.
- The transit data is in GTFS format: http://developers.google.com/transit/gtfs/reference - We converted the GTFS formatted file into SQL in the data folder, under gtfs_tables.sql

## Open issues & Future Feature Requests
- Right now, the bus IDs are in the GTFS data and - unfortunately - not part of the bus stop sign. As a workaround, we are playing around with taking a bus line and assigning a simple code for a stop. We are focusing on the South Beach Local (123) - Route ID #12782 in GTFS.
- Translations in Spanish and Haitian Creole

## Contributors
- Adrian Cardenas (arcardenas@gmail.com)
- Aleyda Mejia (aleydak.mejia@gmail.com)
- Lisa Cawley Ruiz (lisancawley@gmail.com)
- Ernie Hsiung (e@erniehsiung.com)
