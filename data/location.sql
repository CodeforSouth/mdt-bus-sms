USE smsbus;

CREATE TABLE `location` (
  `route` varchar(5) NOT NULL DEFAULT '',
  `trip` int(10) unsigned NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`route`,`trip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;