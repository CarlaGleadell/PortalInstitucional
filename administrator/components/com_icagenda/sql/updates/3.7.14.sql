UPDATE `#__icagenda` SET version='3.7.14', releasedate='2020-04-27' WHERE id=3;

ALTER TABLE `#__icagenda_events` DROP COLUMN `asset_id`;
ALTER TABLE `#__icagenda_registration` DROP COLUMN `asset_id`;
