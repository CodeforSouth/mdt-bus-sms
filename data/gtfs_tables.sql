-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 07, 2013 at 09:10 AM
-- Server version: 5.5.30-30.2
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aramonc_smsbus`
--

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

DROP TABLE IF EXISTS `agency`;
CREATE TABLE IF NOT EXISTS `agency` (
  `agency_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `agency_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agency_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agency_timezone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `agency_lang` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agency_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agency_fare_url` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`agency_id`),
  UNIQUE KEY `name` (`agency_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
CREATE TABLE IF NOT EXISTS `calendar` (
  `service_id` int(11) unsigned NOT NULL,
  `monday` tinyint(1) NOT NULL,
  `tuesday` tinyint(1) NOT NULL,
  `wednesday` tinyint(1) NOT NULL,
  `thursday` tinyint(1) NOT NULL,
  `friday` tinyint(1) NOT NULL,
  `saturday` tinyint(1) NOT NULL,
  `sunday` tinyint(1) NOT NULL,
  `start_date` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`service_id`),
  KEY `trips` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_dates`
--

DROP TABLE IF EXISTS `calendar_dates`;
CREATE TABLE IF NOT EXISTS `calendar_dates` (
  `service_id` int(11) unsigned NOT NULL DEFAULT '0',
  `date` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `exception_type` int(2) NOT NULL,
  PRIMARY KEY (`service_id`,`date`),
  KEY `service_id` (`service_id`),
  KEY `exception_type` (`exception_type`),
  KEY `service` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fare_attributes`
--

DROP TABLE IF EXISTS `fare_attributes`;
CREATE TABLE IF NOT EXISTS `fare_attributes` (
  `fare_id` int(11) unsigned NOT NULL,
  `price` float NOT NULL,
  `currency_type` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` tinyint(1) unsigned NOT NULL,
  `transfers` tinyint(1) unsigned DEFAULT NULL,
  `transfer_duration` int(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`fare_id`),
  KEY `fare` (`fare_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fare_rules`
--

DROP TABLE IF EXISTS `fare_rules`;
CREATE TABLE IF NOT EXISTS `fare_rules` (
  `fare_id` int(11) unsigned NOT NULL,
  `route_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `origin_id` int(11) unsigned DEFAULT NULL,
  `desitnation_id` int(11) unsigned DEFAULT NULL,
  `contains_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`fare_id`),
  KEY `fare_idx` (`fare_id`),
  KEY `route_idx` (`route_id`),
  KEY `origin_idx` (`origin_id`,`desitnation_id`,`contains_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_info`
--

DROP TABLE IF EXISTS `feed_info`;
CREATE TABLE IF NOT EXISTS `feed_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feed_publisher_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feed_publisher_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `feed_lang` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `feed_start_date` date DEFAULT NULL,
  `feed_end_date` date DEFAULT NULL,
  `feed_version` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agency_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `frequencies`
--

DROP TABLE IF EXISTS `frequencies`;
CREATE TABLE IF NOT EXISTS `frequencies` (
  `trip_id` int(10) unsigned NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `headway_secs` time NOT NULL,
  `exact_times` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`trip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AccountSid` varchar(34) COLLATE utf8_unicode_ci NOT NULL,
  `ApiVersion` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Body` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `From` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `FromCity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FromCountry` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `FromState` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `FromZip` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `SmsMessageSid` varchar(34) COLLATE utf8_unicode_ci NOT NULL,
  `SmsSid` varchar(34) COLLATE utf8_unicode_ci NOT NULL,
  `SmsStatus` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `To` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `ToCity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ToCountry` int(4) NOT NULL,
  `ToState` int(4) NOT NULL,
  `ToZip` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `route_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `agency_id` int(11) unsigned DEFAULT NULL,
  `route_short_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `route_long_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `route_type` int(2) NOT NULL,
  `route_desc` tinytext COLLATE utf8_unicode_ci,
  `route_url` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `route_color` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `route_text_color` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`route_id`),
  KEY `agency_id` (`agency_id`),
  KEY `route_type` (`route_type`),
  KEY `route_short_name` (`route_short_name`),
  KEY `agency` (`agency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shapes`
--

DROP TABLE IF EXISTS `shapes`;
CREATE TABLE IF NOT EXISTS `shapes` (
  `shape_id` int(11) unsigned NOT NULL,
  `shape_pt_lat` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `shape_pt_lon` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `shape_pt_sequence` int(5) unsigned NOT NULL DEFAULT '0',
  `shape_dist_traveled` float unsigned DEFAULT NULL,
  PRIMARY KEY (`shape_id`,`shape_pt_sequence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stops`
--

DROP TABLE IF EXISTS `stops`;
CREATE TABLE IF NOT EXISTS `stops` (
  `stop_id` int(11) unsigned NOT NULL,
  `stop_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stop_desc` tinytext COLLATE utf8_unicode_ci,
  `stop_lat` decimal(8,6) NOT NULL,
  `stop_lon` decimal(8,6) NOT NULL,
  `zone_id` int(11) unsigned DEFAULT NULL,
  `stop_code` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `stop_url` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_type` tinyint(4) DEFAULT NULL,
  `parent_station` int(11) unsigned DEFAULT NULL,
  `stop_timezone` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wheelchair_boarding` tinyint(4) DEFAULT NULL,
  `stop_headsign` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`stop_id`,`stop_code`),
  KEY `zone_id` (`zone_id`),
  KEY `stop_lat` (`stop_lat`),
  KEY `stop_lon` (`stop_lon`),
  KEY `parent_idx` (`parent_station`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stop_times`
--

DROP TABLE IF EXISTS `stop_times`;
CREATE TABLE IF NOT EXISTS `stop_times` (
  `trip_id` int(11) unsigned NOT NULL,
  `arrival_time` time NOT NULL,
  `departure_time` time NOT NULL,
  `stop_id` int(11) unsigned NOT NULL,
  `stop_sequence` int(11) unsigned NOT NULL,
  `pickup_type` int(2) DEFAULT NULL,
  `drop_off_type` int(2) DEFAULT NULL,
  `shape_dist_traveled` float unsigned DEFAULT NULL,
  `stop_headsign` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`trip_id`,`stop_id`,`stop_sequence`),
  KEY `trip_id` (`trip_id`),
  KEY `stop_id` (`stop_id`),
  KEY `stop_sequence` (`stop_sequence`),
  KEY `pickup_type` (`pickup_type`),
  KEY `drop_off_type` (`drop_off_type`),
  KEY `stops` (`stop_id`),
  KEY `trips` (`trip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

DROP TABLE IF EXISTS `transfers`;
CREATE TABLE IF NOT EXISTS `transfers` (
  `from_stop_id` int(11) unsigned NOT NULL,
  `to_stop_id` int(11) unsigned NOT NULL,
  `transfer_type` tinyint(3) unsigned NOT NULL,
  `min_transfer_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`from_stop_id`,`to_stop_id`),
  KEY `from_idx` (`from_stop_id`),
  KEY `to_idx` (`to_stop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

DROP TABLE IF EXISTS `trips`;
CREATE TABLE IF NOT EXISTS `trips` (
  `route_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `service_id` int(11) unsigned NOT NULL,
  `trip_id` int(11) unsigned NOT NULL,
  `trip_headsign` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direction_id` tinyint(1) DEFAULT NULL,
  `block_id` int(11) DEFAULT NULL,
  `trip_short_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shape_id` int(11) unsigned DEFAULT NULL,
  `wheelchair_accessible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`trip_id`),
  KEY `route_id` (`route_id`),
  KEY `service_id` (`service_id`),
  KEY `direction_id` (`direction_id`),
  KEY `block_id` (`block_id`),
  KEY `shape_idx` (`shape_id`),
  KEY `rotues` (`route_id`),
  KEY `service` (`service_id`),
  KEY `shape` (`shape_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `trips` FOREIGN KEY (`service_id`) REFERENCES `trips` (`service_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `calendar_dates`
--
ALTER TABLE `calendar_dates`
  ADD CONSTRAINT `calendar_service` FOREIGN KEY (`service_id`) REFERENCES `trips` (`service_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fare_attributes`
--
ALTER TABLE `fare_attributes`
  ADD CONSTRAINT `fare` FOREIGN KEY (`fare_id`) REFERENCES `fare_rules` (`fare_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `fare_rules`
--
ALTER TABLE `fare_rules`
  ADD CONSTRAINT `route` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `feed_info`
--
ALTER TABLE `feed_info`
  ADD CONSTRAINT `agency_info` FOREIGN KEY (`id`) REFERENCES `agency` (`agency_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `frequencies`
--
ALTER TABLE `frequencies`
  ADD CONSTRAINT `trip_frequencies` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `agency` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`agency_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stop_times`
--
ALTER TABLE `stop_times`
  ADD CONSTRAINT `stops` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`stop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `trip_times` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `from` FOREIGN KEY (`from_stop_id`) REFERENCES `stops` (`stop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `to` FOREIGN KEY (`to_stop_id`) REFERENCES `stops` (`stop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `rotues` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `shape` FOREIGN KEY (`shape_id`) REFERENCES `shapes` (`shape_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
