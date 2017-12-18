UPDATE `#__oevents_external`
SET `level` = '5'
WHERE `level` = 'International';

UPDATE `#__oevents_external`
SET `level` = '4'
WHERE `level` = 'Local (Lv. D)' OR `level` = 'Local';

UPDATE `#__oevents_external`
SET `level` = '3'
WHERE `level` = 'Regional (Lv. C)' OR `level` = 'Regional';

UPDATE `#__oevents_external`
SET `level` = '2'
WHERE `level` = 'Regional (Lv. B)' OR `level` = 'National';

UPDATE `#__oevents_external`
SET `level` = '1'
WHERE `level` = 'National (Lv. A)' OR `level` = 'Major';