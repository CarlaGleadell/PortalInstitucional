UPDATE `#__icagenda` SET version='3.8.0-alpha4.1', releasedate='2021-10-13' WHERE id=3;

ALTER TABLE `#__icagenda_customfields`
  MODIFY `checked_out` int unsigned,
  MODIFY `default` varchar(255);

UPDATE `#__icagenda_category` SET `checked_out` = NULL WHERE `checked_out` = 0;
UPDATE `#__icagenda_category` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE `#__icagenda_events` SET `checked_out` = NULL WHERE `checked_out` = 0;
UPDATE `#__icagenda_events` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE `#__icagenda_registration` SET `checked_out` = NULL WHERE `checked_out` = 0;
UPDATE `#__icagenda_registration` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE `#__icagenda_customfields` SET `checked_out` = NULL WHERE `checked_out` = 0;
UPDATE `#__icagenda_customfields` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
UPDATE `#__icagenda_feature` SET `checked_out` = NULL WHERE `checked_out` = 0;
UPDATE `#__icagenda_feature` SET `checked_out_time` = NULL WHERE `checked_out_time` = '0000-00-00 00:00:00';
