# TextMyBusMIA

## What's the purpose of this?
TBA

## Demo-ing the prototype 
- SMS in the Twilio test phone number: (786) 629-6468
- SMS should be in the format: "STOP [ID OF THE STOP] BUS [ROUTE NAME OF BUS]"
- For example: "STOP 2884 BUS 101"

## How to set this up
- Create a web server, basic LAMP stack (Requires PHP 5.3 and above)
- Clone the repo 
- Download composer (package manager) 
curl -sS https://getcomposer.org/installer | php
php composer.phar install 
- Create a test Twilio account and set up the SMS hook to the web server, pointing to answer-sms.php
- Config file is in /config/config.php.dist - you put in the twilio send number, sid and auth_token. Don't forget to config your MySQL database in the db section accordingly. We parse the data from the feeds array under "gtfs_exchange".
- In the cli there's a command-line PHP script called update_gtfs.php - you can run this manually or set up a cron job.

## FAQs
- Where do you get your bus stop #s?
- Right now, the bus IDs are in the GTFS data and - unfortunately - not part of the bus stop sign. A possible to-do is to SMS a particular intersection instead.
- How do you actually get the transit data into a file?
- The transit data is in GTFS format: http://developers.google.com/transit/gtfs/reference - We converted the GTFS formatted file into SQL in the data folder, under gtfs_tables.sql

## Contributors
- Adrian Cardenas (arcardenas@gmail.com)
- Aleyda Mejia (aleydak.mejia@gmail.com)
- Ernie Hsiung (e@erniehsiung.com)
- Lisa Cawley (Copywriter, e-mail redacted)
