UPDATE `#__icagenda` SET version='3.6', releasedate='2015-12-28' WHERE id=3;

ALTER TABLE `#__icagenda_events` ADD COLUMN `hits` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `language`;
ALTER TABLE `#__icagenda_customfields` ADD COLUMN `groups` VARCHAR(255) NOT NULL DEFAULT '' AFTER `parent_form`;
ALTER TABLE `#__icagenda_registration` ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT '0' AFTER `notes`;
ALTER TABLE `#__icagenda_category` ADD COLUMN `language` varchar(10) NOT NULL DEFAULT '*' AFTER `desc`;
ALTER TABLE `#__icagenda_category` ADD COLUMN `groups` varchar(255) NOT NULL DEFAULT '' AFTER `desc`;
ALTER TABLE `#__icagenda_category` ADD COLUMN `image` varchar(255) NOT NULL AFTER `desc`;
ALTER TABLE `#__icagenda_events` MODIFY `language` varchar(10) NOT NULL DEFAULT '*';
UPDATE `#__icagenda_events` SET language='*' WHERE language='';

CREATE TABLE IF NOT EXISTS `#__icagenda_filters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT '',
  `filter` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `option` varchar(255) NOT NULL DEFAULT '',
  `selected` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
