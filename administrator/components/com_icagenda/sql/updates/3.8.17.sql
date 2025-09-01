UPDATE `#__icagenda` SET version='3.8.17', releasedate='2023-05-24' WHERE id=3;

ALTER TABLE `#__icagenda_customfields_data` MODIFY `value` mediumtext NOT NULL;
