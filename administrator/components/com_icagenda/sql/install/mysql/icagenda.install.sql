--
-- iCagenda: Install Database `icagenda`
--

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda`
--

CREATE TABLE IF NOT EXISTS `#__icagenda` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) DEFAULT NULL,
  `releasedate` varchar(255) DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `#__icagenda`
--

INSERT IGNORE INTO `#__icagenda` (`id`, `version`, `releasedate`, `params`) VALUES
(3,'3.9.11','2025-04-22','');

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_category`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int NOT NULL DEFAULT 0,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int unsigned,
  `checked_out_time` datetime NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `image` varchar(255),
  `groups` varchar(255) NOT NULL DEFAULT '',
  `language` varchar(10) NOT NULL DEFAULT '*',
  `note` varchar(255) NOT NULL DEFAULT '',
  `version` int unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_language` (`language`),
  KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_events`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int NOT NULL DEFAULT 0,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `approval` int(11) NOT NULL DEFAULT '0',
  `site_itemid` int(10) NOT NULL DEFAULT '0',
  `checked_out` int unsigned,
  `checked_out_time` datetime NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `language` varchar(10) NOT NULL DEFAULT '*',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int unsigned NOT NULL DEFAULT 0,
  `username` varchar(255) NOT NULL DEFAULT '',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `created_by_email` varchar(100),
  `modified` datetime NOT NULL,
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL,
  `image` varchar(255),
  `file` varchar(255) NOT NULL,
  `displaytime` int(10) NOT NULL DEFAULT '1',
  `weekdays` varchar(255) NOT NULL DEFAULT '',
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `period` text NOT NULL,
  `dates` mediumtext NOT NULL,
  `next` datetime NOT NULL,
  `place` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `coordinate` varchar(255),
  `lat` float(20, 16) NOT NULL DEFAULT 0,
  `lng` float(20, 16) NOT NULL DEFAULT 0,
  `shortdesc` text NOT NULL,
  `desc` text NOT NULL,
  `metadesc` text NOT NULL,
  `params` text NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `version` int unsigned NOT NULL DEFAULT 1,
  `version_customfields` mediumtext NOT NULL,
  `version_features` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_approval` (`approval`),
  KEY `idx_catid` (`catid`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_language` (`language`),
  KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_registration`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_registration` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int NOT NULL DEFAULT 0,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int unsigned,
  `checked_out_time` datetime NULL DEFAULT NULL,
  `userid` int NOT NULL DEFAULT 0,
  `itemid` int NOT NULL DEFAULT 0,
  `eventid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `date` text NOT NULL,
  `period` tinyint(1) NOT NULL DEFAULT '0',
  `people` int(2) NOT NULL,
  `notes` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int unsigned NOT NULL DEFAULT 0,
  `modified` datetime NOT NULL,
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_eventid` (`eventid`),
  KEY `idx_state` (`state`),
  KEY `idx_status` (`status`),
  KEY `idx_userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_customfields`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_customfields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int unsigned,
  `checked_out_time` datetime NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `parent_form` int(11) NOT NULL DEFAULT '0',
  `groups` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL,
  `options` mediumtext,
  `default` varchar(255),
  `required` tinyint(3) NOT NULL DEFAULT '0',
  `language` varchar(10) NOT NULL DEFAULT '*',
  `params` mediumtext,
  `created` datetime NOT NULL,
  `created_by` int unsigned NOT NULL DEFAULT 0,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL,
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_parent_form` (`parent_form`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_customfields_data`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_customfields_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `slug` varchar(255) NOT NULL,
  `parent_form` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `value` mediumtext NOT NULL,
  `language` varchar(10) NOT NULL DEFAULT '*',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_parent_form` (`parent_form`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_feature`
--

CREATE TABLE IF NOT EXISTS  `#__icagenda_feature` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int NOT NULL DEFAULT 0,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int unsigned,
  `checked_out_time` datetime NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `desc` mediumtext NOT NULL,
  `icon` varchar(255) NOT NULL,
  `icon_alt` varchar(255) NOT NULL,
  `show_filter` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_feature_xref`
--

CREATE TABLE IF NOT EXISTS  `#__icagenda_feature_xref` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_event_id` (`event_id`),
  KEY `idx_feature_id` (`feature_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_filters`
--

CREATE TABLE IF NOT EXISTS `#__icagenda_filters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `state` tinyint(3) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT '',
  `filter` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `option` varchar(255) NOT NULL DEFAULT '',
  `selected` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__icagenda_user_actions`
--

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
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_parent_form` (`parent_form`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Insert Category and Event version structure for table `#__content_types`
--

INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) 
VALUES
(null, 
'iCagenda Category', 
'com_icagenda.icategory', 
'{"special":{"dbtable":"#__icagenda_category","key":"id","type":"iCategoryTable","prefix":"WebiC\\Component\\iCagenda\\Administrator\\Table\\","config":"array()"}}', 
'', 
'', 
'', 
'{"formFile":"administrator\\/components\\/com_icagenda\\/models\\/forms\\/category.xml", "hideFields":["checked_out", "checked_out_time", "version", "groups", "language"], "ignoreChanges":["checked_out", "checked_out_time", "version"],"convertToInt":[], "displayLookup":[]}'),
(null, 
'iCagenda Event', 
'com_icagenda.event', 
'{"special":{"dbtable":"#__icagenda_events","key":"id","type":"EventTable","prefix":"WebiC\\Component\\iCagenda\\Administrator\\Table\\","config":"array()"}}', 
'', 
'', 
'', 
'{"formFile":"administrator\\/components\\/com_icagenda\\/models\\/forms\\/event.xml", "hideFields":["asset_id", "checked_out", "checked_out_time", "version", "first_published_and_approved", "next", "period", "coordinate"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "hits", "ordering", "first_published_and_approved", "next", "period", "coordinate"],"convertToInt":["ordering", "lat", "lng"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__icagenda_category","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}');
