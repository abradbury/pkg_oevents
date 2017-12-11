CREATE TABLE IF NOT EXISTS `#__oevents_external` (
	`event_id` int(10) NOT NULL AUTO_INCREMENT,
	`date` date NOT NULL,
	`title` varchar(255) NOT NULL,
	`venue` varchar(255), 
	`club` varchar(25),
	`level` varchar(25),
	`url` varchar(2083) NOT NULL, 
	`clubUrl`  varchar(2083),
	`status` tinyint(1),
	`remote_id` int(10),
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
