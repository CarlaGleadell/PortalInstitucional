UPDATE `#__icagenda` SET version='3.7', releasedate='2018-05-25' WHERE id=3;

CREATE TABLE IF NOT EXISTS `#__icagenda_user_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT 0,
  `user_action` varchar(255) NOT NULL,
  `parent_form` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `action_subject` varchar(100) NOT NULL DEFAULT '',
  `action_body` text NOT NULL,
  `user_ip` varchar(100) NOT NULL DEFAULT '',
  `user_agent` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
