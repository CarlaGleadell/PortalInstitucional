UPDATE `#__icagenda` SET version='3.8.0-alpha5', releasedate='2021-11-22' WHERE id=3;

ALTER TABLE `#__icagenda_events`
  MODIFY `version_customfields` mediumtext NOT NULL,
  MODIFY `version_features` mediumtext NOT NULL;

UPDATE `#__icagenda_events`
SET alias=CONCAT(id, '-', alias)
WHERE id IN (
  SELECT e.id
  FROM (SELECT * FROM `#__icagenda_events`) AS e
  INNER JOIN (
    SELECT
      alias
      , MIN(id)  as ID
    FROM `#__icagenda_events`
    GROUP BY
      alias 
      HAVING COUNT(id) > 1
    ) duplicates
  ON (e.id > duplicates.id AND e.alias = duplicates.alias)
);
