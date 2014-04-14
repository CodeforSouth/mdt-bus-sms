# TextMyBusMIA

## What's the purpose of this?
"Text My Bus MIA" is an SMS system that enables the user to text the ID number of his or her bus stop and receive a message with upcoming bus times for that stop. The goal is to provide easy access to updated bus schedules for low-income individuals and other Miami-Dade County residents and visitors who rely on public transportation for commuting and getting around the county.

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
The instructions below explain how to find your bus stop number and your bus number for TEXTMYBUS.

1. Go to http://smsbus.miamicode.org/locate-stops.html
2. Enter the address to an intersection & press search. For example:
  - sw 137 ave & sw 56 st, miami, fl
  - Try to always use the city & state with commas.
    - Works well:
      - alhambra cir & ponce de leon blvd, coral gables, fl
    - Does not work well:
      - alhambra cir & ponce de leon blvd coral gables fl
3. Select the pin that represents your bus stop
4. Write down the bus stop number associated with the pin.
5. Find your sticker.
6. Write your bus number where it says BUS
7. Write the stop number where it says AT
8. Stick your bus sticker on your stop.

## WHAT IF MY BUS NUMBER IS A LETTER?
All lettered buses have numbers. Here is a list of numbers associated with bus 

routes:

- A – 101
- B – 102
- C – 103
- E – 105
- G – 107 
- H – 108
- J – 110
- L - 112
- M – 113
- S – 119

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
