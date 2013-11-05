# TextMyBusMIA

## What's the purpose of this?
TBA

## Demo-ing the prototype 
- SMS in the Twilio test phone number: (786) 629-6468
- SMS should be in the format: "BUS [ROUTE SHORT NAME] AT [STOP LOCATION ID]"
- For example: "BUS 101 AT 2884" (The 101/A route at 17th St & Meridian Ave, Miami Beach, FL)

## Finding out stop location IDs (for internal use mostly)
- SMS in the Twilio test phone number: (786) 629-6468
- SMS in format: "STOP AT [INTERSECTION ADDRESS, CITY, STATE]" will return a list of stops near that intersection along with the calculated direction of the street they're on
- Example: "STOP AT 17 ST & MEDIRIAN AVE, MIAMI BEACH, FL" returns #2884 Eastbound.
- SMS in format: "STOP [LOCATION ID]" will return a Google Maps link centered on the coordinates of the location ID
- Example: "STOP 2884" will return https://maps.google.com/maps?q=25.792236,-80.136955&num=1&t=m&z=19
- You may also browse to http://smsbus.illogicalsystems.com/locate-stops.html?phrase=17+st+%26+meridian+ave%2C+miami+beach%2C+fl and play with the search box.

## Filling out the Sticker
![Txt Sticker](http://farm3.staticflickr.com/2860/10681801524_9221751bd4_m.jpg)
- The "Bus" field refers to the bus route number. For example: 137, 288 (Kendall Kruiser), 72, etc.
  - The named routes (Sunset Kat, A, H, etc.) have route numbers also. For example: Sunset Kat = 272. If you don't know the number equivalent, look on pamphlets or the bus stop itself.
- The "At" field refers to the stop ID number. You can find the stop number by going to http://smsbus.illogicalsystems.com/locate-stops.html and adding the intersection address.
- Look for bus stops around major intersections near or in your neighborhood. 
  - The stop signs will have the bus route numbers on them. 
  - Once you have selected the stop, gotten it's ID, and bus route, fill out the bus route & stop ID in their respective fields.
  - Place the sticker in a visible place on or near the bus stop sign that doesn't cover important information like route information & phone numbers.

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
- Flesh out more the stop locator & maybe not use SMS to find location IDs.

## Contributors
- Adrian Cardenas (arcardenas@gmail.com)
- Aleyda Mejia (aleydak.mejia@gmail.com)
- Ernie Hsiung (e@erniehsiung.com)
- Lisa Cawley (Copywriter, e-mail redacted)
