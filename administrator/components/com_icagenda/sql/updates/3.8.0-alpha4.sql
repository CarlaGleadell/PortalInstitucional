UPDATE `#__icagenda` SET version='3.8.0-alpha4', releasedate='2021-10-11' WHERE id=3;

ALTER TABLE `#__icagenda_category`
  MODIFY `ordering` int NOT NULL DEFAULT 0,
  MODIFY `checked_out` int unsigned,
  ADD KEY `idx_checkout` (`checked_out`),
  MODIFY `checked_out_time` datetime NULL DEFAULT NULL,
  MODIFY `desc` text NOT NULL,
  MODIFY `image` varchar(255),
  ADD COLUMN `note` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `version` int unsigned NOT NULL DEFAULT 1;

ALTER TABLE `#__icagenda_events`
  CHANGE `username` `username` varchar(255) NOT NULL DEFAULT '' AFTER `created_by`,
  MODIFY `ordering` int NOT NULL DEFAULT 0,
  MODIFY `checked_out` int unsigned,
  ADD KEY `idx_checkout` (`checked_out`),
  MODIFY `checked_out_time` datetime NULL DEFAULT NULL,
  MODIFY `period` text NOT NULL,
  MODIFY `created` datetime NOT NULL,
  MODIFY `created_by` int unsigned NOT NULL DEFAULT 0,
  MODIFY `created_by_email` varchar(100),
  MODIFY `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  MODIFY `modified` datetime NOT NULL,
  DROP COLUMN `daystime`,
  MODIFY `startdate` datetime NOT NULL,
  MODIFY `enddate` datetime NOT NULL,
  MODIFY `dates` mediumtext NOT NULL,
  MODIFY `next` datetime NOT NULL,
  MODIFY `image` varchar(255),
  MODIFY `coordinate` varchar(255),
  MODIFY `lat` float(20, 16) NOT NULL DEFAULT 0,
  MODIFY `lng` float(20, 16) NOT NULL DEFAULT 0,
  MODIFY `weekdays` varchar(255) NOT NULL DEFAULT '',
  MODIFY `name` varchar(255) NOT NULL DEFAULT '',
  MODIFY `desc` text NOT NULL,
  ADD COLUMN `note` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `version` int unsigned NOT NULL DEFAULT 1,
  ADD COLUMN `version_customfields` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `version_features` varchar(255) NOT NULL DEFAULT '',
  DROP COLUMN `time`;
  
ALTER TABLE `#__icagenda_registration`
  MODIFY `ordering` int NOT NULL DEFAULT 0,
  MODIFY `checked_out` int unsigned,
  ADD KEY `idx_checkout` (`checked_out`),
  MODIFY `checked_out_time` datetime NULL DEFAULT NULL,
  MODIFY `userid` int NOT NULL DEFAULT 0,
  MODIFY `itemid` int NOT NULL DEFAULT 0,
  MODIFY `date` text NOT NULL,
  MODIFY `notes` text NOT NULL,
  MODIFY `params` text NOT NULL,
  MODIFY `created` datetime NOT NULL,
  MODIFY `created_by` int unsigned NOT NULL DEFAULT 0,
  MODIFY `modified` datetime NOT NULL;

ALTER TABLE `#__icagenda_customfields`
  MODIFY `default` varchar(255),
  MODIFY `checked_out` int unsigned,
  ADD KEY `idx_checkout` (`checked_out`),
  MODIFY `checked_out_time` datetime NULL DEFAULT NULL,
  MODIFY `created` datetime NOT NULL,
  MODIFY `created_by` int unsigned NOT NULL DEFAULT 0,
  MODIFY `modified` datetime NOT NULL;

ALTER TABLE `#__icagenda_feature`
  MODIFY `ordering` int NOT NULL DEFAULT 0,
  MODIFY `desc` mediumtext NOT NULL,
  MODIFY `checked_out` int unsigned,
  ADD KEY `idx_checkout` (`checked_out`),
  MODIFY `checked_out_time` datetime NULL DEFAULT NULL;

ALTER TABLE `#__icagenda_user_actions`
  MODIFY `created_time` datetime NOT NULL;

ALTER TABLE `#__icagenda` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_category` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_customfields` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_customfields_data` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_events` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_feature` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_feature_xref` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_filters` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_registration` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `#__icagenda_user_actions` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

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
