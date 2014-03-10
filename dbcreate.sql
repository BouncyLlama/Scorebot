CREATE DATABASE `scorebot2` /*!40100 DEFAULT CHARACTER SET latin1 */;
CREATE TABLE `files` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `description` text,
  `link` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
CREATE TABLE `flags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `team` int(11) NOT NULL DEFAULT '0',
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
CREATE TABLE `flagsubmissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `flag_id` bigint(20) NOT NULL DEFAULT '0',
  `submission` text,
  `value` text,
  `username` varchar(128) DEFAULT NULL,
  `correct` bit(1) NOT NULL DEFAULT b'0',
  `timestamp` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action` varchar(128) NOT NULL,
  `username` varchar(128) DEFAULT NULL,
  `timestamp` bigint(20) NOT NULL DEFAULT '0',
  `description` text,
  `query` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=361 DEFAULT CHARSET=latin1;
CREATE TABLE `sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cookie` varchar(45) NOT NULL,
  `remote_ip` varchar(45) NOT NULL,
  `username` varchar(128) NOT NULL,
  `init_time` bigint(20) NOT NULL,
  `last_active` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `handle` varchar(128) DEFAULT NULL,
  `team` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
