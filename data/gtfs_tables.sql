SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `gtfs` ;
USE `gtfs` ;

-- -----------------------------------------------------
-- Table `gtfs`.`agency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`agency` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`agency` (
  `agency_id` INT(11) UNSIGNED NOT NULL ,
  `agency_name` VARCHAR(255) NOT NULL ,
  `agency_url` VARCHAR(255) NOT NULL ,
  `agency_timezone` VARCHAR(50) NOT NULL ,
  `agency_lang` VARCHAR(2) NULL ,
  `agency_phone` VARCHAR(20) NULL ,
  `agency_fare_url` VARCHAR(120) NULL ,
  PRIMARY KEY (`agency_id`) );


-- -----------------------------------------------------
-- Table `gtfs`.`calendar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`calendar` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`calendar` (
  `service_id` INT(11) UNSIGNED NOT NULL ,
  `monday` TINYINT(1) NOT NULL ,
  `tuesday` TINYINT(1) NOT NULL ,
  `wednesday` TINYINT(1) NOT NULL ,
  `thursday` TINYINT(1) NOT NULL ,
  `friday` TINYINT(1) NOT NULL ,
  `saturday` TINYINT(1) NOT NULL ,
  `sunday` TINYINT(1) NOT NULL ,
  `start_date` VARCHAR(8) NOT NULL ,
  `end_date` VARCHAR(8) NOT NULL ,
  INDEX `service_id` (`service_id` ASC) ,
  PRIMARY KEY (`service_id`) );


-- -----------------------------------------------------
-- Table `gtfs`.`calendar_dates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`calendar_dates` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`calendar_dates` (
  `service_id` INT(11) UNSIGNED NOT NULL DEFAULT NULL ,
  `date` VARCHAR(8) NOT NULL ,
  `exception_type` INT(2) NOT NULL ,
  INDEX `service_id` (`service_id` ASC) ,
  INDEX `exception_type` (`exception_type` ASC) ,
  PRIMARY KEY (`service_id`, `date`) );


-- -----------------------------------------------------
-- Table `gtfs`.`routes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`routes` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`routes` (
  `route_id` INT(11) UNSIGNED NOT NULL ,
  `agency_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `route_short_name` VARCHAR(50) NOT NULL ,
  `route_long_name` VARCHAR(255) NOT NULL ,
  `route_type` INT(2) NOT NULL ,
  `rotue_desc` TINYTEXT NULL ,
  `route_url` VARCHAR(120) NULL ,
  `route_color` VARCHAR(6) NULL ,
  `route_text_color` VARCHAR(6) NULL ,
  PRIMARY KEY (`route_id`) ,
  INDEX `agency_id` (`agency_id` ASC) ,
  INDEX `route_type` (`route_type` ASC) ,
  CONSTRAINT `agency`
    FOREIGN KEY (`agency_id` )
    REFERENCES `gtfs`.`agency` (`agency_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `gtfs`.`shapes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`shapes` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`shapes` (
  `shape_id` INT(11) UNSIGNED NOT NULL ,
  `shape_pt_lat` VARCHAR(60) NOT NULL ,
  `shape_pt_lon` VARCHAR(60) NOT NULL ,
  `shape_pt_sequence` INT(5) UNSIGNED NULL ,
  `shape_dist_traveled` FLOAT UNSIGNED NULL ,
  PRIMARY KEY (`shape_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gtfs`.`trips`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`trips` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`trips` (
  `route_id` INT(11) UNSIGNED NOT NULL ,
  `service_id` INT(11) UNSIGNED NOT NULL ,
  `trip_id` INT(11) UNSIGNED NOT NULL ,
  `trip_headsign` VARCHAR(255) NULL ,
  `direction_id` TINYINT(1) NULL DEFAULT NULL ,
  `block_id` INT(11) NULL DEFAULT NULL ,
  `trip_short_name` VARCHAR(60) NULL ,
  `shape_id` INT(11) UNSIGNED NULL ,
  `wheelchair_accessible` TINYINT(1) NULL ,
  PRIMARY KEY (`trip_id`) ,
  INDEX `route_id` (`route_id` ASC) ,
  INDEX `service_id` (`service_id` ASC) ,
  INDEX `direction_id` (`direction_id` ASC) ,
  INDEX `block_id` (`block_id` ASC) ,
  INDEX `shape_idx` (`shape_id` ASC) ,
  CONSTRAINT `route`
    FOREIGN KEY (`route_id` )
    REFERENCES `gtfs`.`routes` (`route_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `block`
    FOREIGN KEY (`block_id` )
    REFERENCES `gtfs`.`trips` (`block_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `calendar`
    FOREIGN KEY (`service_id` )
    REFERENCES `gtfs`.`calendar` (`service_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `calendar_date`
    FOREIGN KEY (`service_id` )
    REFERENCES `gtfs`.`calendar_dates` (`service_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `shape`
    FOREIGN KEY (`shape_id` )
    REFERENCES `gtfs`.`shapes` (`shape_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `gtfs`.`stops`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`stops` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`stops` (
  `stop_id` INT(11) UNSIGNED NOT NULL ,
  `stop_name` VARCHAR(255) NOT NULL ,
  `stop_desc` TINYTEXT NULL ,
  `stop_lat` DECIMAL(8,6) NOT NULL ,
  `stop_lon` DECIMAL(8,6) NOT NULL ,
  `zone_id` INT(11) UNSIGNED NULL ,
  `stop_code` VARCHAR(45) NOT NULL ,
  `stop_url` VARCHAR(120) NULL ,
  `location_type` TINYINT NULL ,
  `parent_station` INT(11) UNSIGNED NULL ,
  `stop_timezone` VARCHAR(45) NULL ,
  `wheelchair_boarding` TINYINT NULL ,
  PRIMARY KEY (`stop_id`, `stop_code`) ,
  INDEX `zone_id` (`zone_id` ASC) ,
  INDEX `stop_lat` (`stop_lat` ASC) ,
  INDEX `stop_lon` (`stop_lon` ASC) ,
  INDEX `parent_idx` (`parent_station` ASC) ,
  CONSTRAINT `parent`
    FOREIGN KEY (`parent_station` )
    REFERENCES `gtfs`.`stops` (`stop_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `gtfs`.`stop_times`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`stop_times` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`stop_times` (
  `trip_id` INT(11) UNSIGNED NOT NULL ,
  `arrival_time` TIME NOT NULL ,
  `departure_time` TIME NOT NULL ,
  `stop_id` INT(11) UNSIGNED NOT NULL ,
  `stop_sequence` INT(11) UNSIGNED NOT NULL ,
  `pickup_type` INT(2) NULL DEFAULT NULL ,
  `drop_off_type` INT(2) NULL DEFAULT NULL ,
  `shape_dist_traveled` FLOAT UNSIGNED NULL ,
  INDEX `trip_id` (`trip_id` ASC) ,
  INDEX `stop_id` (`stop_id` ASC) ,
  INDEX `stop_sequence` (`stop_sequence` ASC) ,
  INDEX `pickup_type` (`pickup_type` ASC) ,
  INDEX `drop_off_type` (`drop_off_type` ASC) ,
  CONSTRAINT `trip`
    FOREIGN KEY (`trip_id` )
    REFERENCES `gtfs`.`trips` (`trip_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `stop`
    FOREIGN KEY (`stop_id` )
    REFERENCES `gtfs`.`stops` (`stop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `gtfs`.`fare_attributes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`fare_attributes` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`fare_attributes` (
  `fare_id` INT(11) UNSIGNED NOT NULL ,
  `price` FLOAT NOT NULL ,
  `currency_type` VARCHAR(3) NOT NULL ,
  `payment_method` TINYINT(1) UNSIGNED NOT NULL ,
  `transfers` TINYINT(1) UNSIGNED NULL ,
  `transfer_duration` INT(6) UNSIGNED NULL ,
  PRIMARY KEY (`fare_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gtfs`.`fare_rules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`fare_rules` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`fare_rules` (
  `fare_id` INT(11) UNSIGNED NOT NULL ,
  `route_id` INT(11) UNSIGNED NULL ,
  `origin_id` INT(11) UNSIGNED NULL ,
  `desitnation_id` INT(11) UNSIGNED NULL ,
  `contains_id` INT(11) UNSIGNED NULL ,
  PRIMARY KEY (`fare_id`) ,
  INDEX `route_idx` (`route_id` ASC) ,
  INDEX `origin_idx` (`origin_id` ASC, `desitnation_id` ASC, `contains_id` ASC) ,
  CONSTRAINT `fare`
    FOREIGN KEY (`fare_id` )
    REFERENCES `gtfs`.`fare_attributes` (`fare_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `route`
    FOREIGN KEY (`route_id` )
    REFERENCES `gtfs`.`routes` (`route_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `zone`
    FOREIGN KEY (`origin_id` , `desitnation_id` , `contains_id` )
    REFERENCES `gtfs`.`stops` (`zone_id` , `zone_id` , `zone_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gtfs`.`frequencies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`frequencies` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`frequencies` (
  `trip_id` INT UNSIGNED NOT NULL ,
  `start_time` TIME NOT NULL ,
  `end_time` TIME NOT NULL ,
  `headway_secs` TIME NOT NULL ,
  `exact_times` TINYINT NULL ,
  PRIMARY KEY (`trip_id`) ,
  CONSTRAINT `trips`
    FOREIGN KEY (`trip_id` )
    REFERENCES `gtfs`.`trips` (`trip_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gtfs`.`feed_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`feed_info` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`feed_info` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `feed_publisher_url` VARCHAR(255) NOT NULL ,
  `feed_publisher_name` VARCHAR(255) NOT NULL ,
  `feed_lang` VARCHAR(45) NOT NULL ,
  `feed_start_date` DATE NULL ,
  `feed_end_date` DATE NULL ,
  `feed_version` VARCHAR(45) NULL ,
  `agency_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `agency`
    FOREIGN KEY (`id` )
    REFERENCES `gtfs`.`agency` (`agency_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gtfs`.`transfers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gtfs`.`transfers` ;

CREATE  TABLE IF NOT EXISTS `gtfs`.`transfers` (
  `from_stop_id` INT UNSIGNED NOT NULL ,
  `to_stop_id` INT UNSIGNED NOT NULL ,
  `transfer_type` TINYINT UNSIGNED NOT NULL ,
  `min_transfer_time` INT UNSIGNED NULL ,
  PRIMARY KEY (`from_stop_id`, `to_stop_id`) ,
  INDEX `from_idx` (`from_stop_id` ASC) ,
  INDEX `to_idx` (`to_stop_id` ASC) ,
  CONSTRAINT `from`
    FOREIGN KEY (`from_stop_id` )
    REFERENCES `gtfs`.`stops` (`stop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `to`
    FOREIGN KEY (`to_stop_id` )
    REFERENCES `gtfs`.`stops` (`stop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `gtfs` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
