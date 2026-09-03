UPDATE external_sport_type_mappings esm
JOIN data_sources ds ON ds.id = esm.data_source_id
SET esm.external_id = CASE esm.external_name
    WHEN 'BOOTCAMP' THEN '58'
    WHEN 'CYCLING' THEN '2'
    WHEN 'HIIT' THEN '34'
    WHEN 'INDOOR_CYCLING' THEN '18'
    WHEN 'MOUNTAIN_BIKING' THEN '5'
    WHEN 'OTHER_INDOOR' THEN '83'
    WHEN 'RUNNING' THEN '1'
    WHEN 'STRENGTH_TRAINING' THEN '15'
    WHEN 'TREADMILL_RUNNING' THEN '17'
END
WHERE ds.name = 'polar'
  AND esm.external_name IN ('BOOTCAMP', 'CYCLING', 'HIIT', 'INDOOR_CYCLING', 'MOUNTAIN_BIKING', 'OTHER_INDOOR', 'RUNNING', 'STRENGTH_TRAINING', 'TREADMILL_RUNNING');
-- WALKING left out: '5' conflicted with MOUNTAIN_BIKING, so the date-match
-- for one of them was likely wrong. Re-verify before adding it back.
