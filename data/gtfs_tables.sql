SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`agency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`agency` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`agency` (
  `agency_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `agency_name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `agency_url` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `agency_timezone` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `agency_lang` VARCHAR(2) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `agency_phone` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `agency_fare_url` VARCHAR(120) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`agency_id`) ,
  UNIQUE INDEX `name` (`agency_name` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`routes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`routes` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`routes` (
  `route_id` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `agency_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `route_short_name` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `route_long_name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `route_type` INT(2) NOT NULL ,
  `route_desc` TINYTEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `route_url` VARCHAR(120) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `route_color` VARCHAR(6) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `route_text_color` VARCHAR(6) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`route_id`) ,
  INDEX `agency_id` (`agency_id` ASC) ,
  INDEX `route_type` (`route_type` ASC) ,
  INDEX `route_short_name` (`route_short_name` ASC) ,
  INDEX `agency` (`agency_id` ASC) ,
  CONSTRAINT `agency`
    FOREIGN KEY (`agency_id` )
    REFERENCES `aramonc_smsbus`.`agency` (`agency_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`shapes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`shapes` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`shapes` (
  `shape_id` INT(11) UNSIGNED NOT NULL ,
  `shape_pt_lat` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `shape_pt_lon` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `shape_pt_sequence` INT(5) UNSIGNED NOT NULL DEFAULT '0' ,
  `shape_dist_traveled` FLOAT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`shape_id`, `shape_pt_sequence`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`trips`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`trips` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`trips` (
  `route_id` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `service_id` INT(11) UNSIGNED NOT NULL ,
  `trip_id` INT(11) UNSIGNED NOT NULL ,
  `trip_headsign` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `direction_id` TINYINT(1) NULL DEFAULT NULL ,
  `block_id` INT(11) NULL DEFAULT NULL ,
  `trip_short_name` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `shape_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `wheelchair_accessible` TINYINT(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`trip_id`) ,
  INDEX `route_id` (`route_id` ASC) ,
  INDEX `service_id` (`service_id` ASC) ,
  INDEX `direction_id` (`direction_id` ASC) ,
  INDEX `block_id` (`block_id` ASC) ,
  INDEX `shape_idx` (`shape_id` ASC) ,
  INDEX `rotues` (`route_id` ASC) ,
  INDEX `service` (`service_id` ASC) ,
  INDEX `shape` (`shape_id` ASC) ,
  CONSTRAINT `rotues`
    FOREIGN KEY (`route_id` )
    REFERENCES `aramonc_smsbus`.`routes` (`route_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `service`
    FOREIGN KEY (`service_id` )
    REFERENCES `aramonc_smsbus`.`trips` (`trip_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `shape`
    FOREIGN KEY (`shape_id` )
    REFERENCES `aramonc_smsbus`.`shapes` (`shape_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`calendar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`calendar` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`calendar` (
  `service_id` INT(11) UNSIGNED NOT NULL ,
  `monday` TINYINT(1) NOT NULL ,
  `tuesday` TINYINT(1) NOT NULL ,
  `wednesday` TINYINT(1) NOT NULL ,
  `thursday` TINYINT(1) NOT NULL ,
  `friday` TINYINT(1) NOT NULL ,
  `saturday` TINYINT(1) NOT NULL ,
  `sunday` TINYINT(1) NOT NULL ,
  `start_date` VARCHAR(8) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `end_date` VARCHAR(8) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`service_id`) ,
  INDEX `trips` (`service_id` ASC) ,
  CONSTRAINT `trips`
    FOREIGN KEY (`service_id` )
    REFERENCES `aramonc_smsbus`.`trips` (`service_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`calendar_dates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`calendar_dates` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`calendar_dates` (
  `service_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `date` VARCHAR(8) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `exception_type` INT(2) NOT NULL ,
  PRIMARY KEY (`service_id`, `date`) ,
  INDEX `service_id` (`service_id` ASC) ,
  INDEX `exception_type` (`exception_type` ASC) ,
  INDEX `service` (`service_id` ASC) ,
  CONSTRAINT `calendar_service`
    FOREIGN KEY (`service_id` )
    REFERENCES `aramonc_smsbus`.`trips` (`service_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`fare_attributes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`fare_attributes` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`fare_attributes` (
  `fare_id` INT(11) UNSIGNED NOT NULL ,
  `price` FLOAT NOT NULL ,
  `currency_type` VARCHAR(3) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `payment_method` TINYINT(1) UNSIGNED NOT NULL ,
  `transfers` TINYINT(1) UNSIGNED NULL DEFAULT NULL ,
  `transfer_duration` INT(6) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`fare_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`stops`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`stops` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`stops` (
  `stop_id` INT(11) UNSIGNED NOT NULL ,
  `stop_name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `stop_desc` TINYTEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `stop_lat` DECIMAL(8,6) NOT NULL ,
  `stop_lon` DECIMAL(8,6) NOT NULL ,
  `zone_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `stop_code` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `stop_url` VARCHAR(120) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `location_type` TINYINT(4) NULL DEFAULT NULL ,
  `parent_station` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `stop_timezone` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `wheelchair_boarding` TINYINT(4) NULL DEFAULT NULL ,
  `stop_headsign` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  PRIMARY KEY (`stop_id`, `stop_code`) ,
  INDEX `zone_id` (`zone_id` ASC) ,
  INDEX `stop_lat` (`stop_lat` ASC) ,
  INDEX `stop_lon` (`stop_lon` ASC) ,
  INDEX `parent_idx` (`parent_station` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`fare_rules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`fare_rules` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`fare_rules` (
  `fare_id` INT(11) UNSIGNED NOT NULL ,
  `route_id` VARCHAR(50) NULL DEFAULT NULL ,
  `origin_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `desitnation_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `contains_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`fare_id`) ,
  INDEX `route_idx` (`route_id` ASC) ,
  INDEX `origin_idx` (`origin_id` ASC, `desitnation_id` ASC, `contains_id` ASC) ,
  CONSTRAINT `fare`
    FOREIGN KEY (`fare_id` )
    REFERENCES `aramonc_smsbus`.`fare_attributes` (`fare_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `route`
    FOREIGN KEY (`route_id` )
    REFERENCES `aramonc_smsbus`.`routes` (`route_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `zone`
    FOREIGN KEY (`origin_id` , `desitnation_id` , `contains_id` )
    REFERENCES `aramonc_smsbus`.`stops` (`zone_id` , `zone_id` , `zone_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`feed_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`feed_info` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`feed_info` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `feed_publisher_url` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `feed_publisher_name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `feed_lang` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `feed_start_date` DATE NULL DEFAULT NULL ,
  `feed_end_date` DATE NULL DEFAULT NULL ,
  `feed_version` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `agency_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `agency_info`
    FOREIGN KEY (`id` )
    REFERENCES `aramonc_smsbus`.`agency` (`agency_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`frequencies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`frequencies` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`frequencies` (
  `trip_id` INT(10) UNSIGNED NOT NULL ,
  `start_time` TIME NOT NULL ,
  `end_time` TIME NOT NULL ,
  `headway_secs` TIME NOT NULL ,
  `exact_times` TINYINT(4) NULL DEFAULT NULL ,
  PRIMARY KEY (`trip_id`) ,
  CONSTRAINT `trip_frequencies`
    FOREIGN KEY (`trip_id` )
    REFERENCES `aramonc_smsbus`.`trips` (`trip_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`stop_times`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`stop_times` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`stop_times` (
  `trip_id` INT(11) UNSIGNED NOT NULL ,
  `arrival_time` TIME NOT NULL ,
  `departure_time` TIME NOT NULL ,
  `stop_id` INT(11) UNSIGNED NOT NULL ,
  `stop_sequence` INT(11) UNSIGNED NOT NULL ,
  `pickup_type` INT(2) NULL DEFAULT NULL ,
  `drop_off_type` INT(2) NULL DEFAULT NULL ,
  `shape_dist_traveled` FLOAT UNSIGNED NULL DEFAULT NULL ,
  `stop_headsign` VARCHAR(200) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  INDEX `trip_id` (`trip_id` ASC) ,
  INDEX `stop_id` (`stop_id` ASC) ,
  INDEX `stop_sequence` (`stop_sequence` ASC) ,
  INDEX `pickup_type` (`pickup_type` ASC) ,
  INDEX `drop_off_type` (`drop_off_type` ASC) ,
  INDEX `stops` (`stop_id` ASC) ,
  INDEX `trips` (`trip_id` ASC) ,
  PRIMARY KEY (`trip_id`, `stop_id`, `stop_sequence`) ,
  CONSTRAINT `stops`
    FOREIGN KEY (`stop_id` )
    REFERENCES `aramonc_smsbus`.`stops` (`stop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `trip_times`
    FOREIGN KEY (`trip_id` )
    REFERENCES `aramonc_smsbus`.`trips` (`trip_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `aramonc_smsbus`.`transfers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aramonc_smsbus`.`transfers` ;

CREATE  TABLE IF NOT EXISTS `aramonc_smsbus`.`transfers` (
  `from_stop_id` INT(10) UNSIGNED NOT NULL ,
  `to_stop_id` INT(10) UNSIGNED NOT NULL ,
  `transfer_type` TINYINT(3) UNSIGNED NOT NULL ,
  `min_transfer_time` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`from_stop_id`, `to_stop_id`) ,
  INDEX `from_idx` (`from_stop_id` ASC) ,
  INDEX `to_idx` (`to_stop_id` ASC) ,
  CONSTRAINT `from`
    FOREIGN KEY (`from_stop_id` )
    REFERENCES `aramonc_smsbus`.`stops` (`stop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `to`
    FOREIGN KEY (`to_stop_id` )
    REFERENCES `aramonc_smsbus`.`stops` (`stop_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
