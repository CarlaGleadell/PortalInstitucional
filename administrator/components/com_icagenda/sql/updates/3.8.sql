UPDATE `#__icagenda` SET version='3.8', releasedate='2019-12-30' WHERE id=3;

ALTER TABLE `#__icagenda_registration` MODIFY `status` tinyint(1) NOT NULL DEFAULT '1';
