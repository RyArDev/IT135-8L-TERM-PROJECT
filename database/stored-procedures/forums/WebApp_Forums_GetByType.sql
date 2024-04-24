DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetByType(IN param_type VARCHAR(32))
BEGIN
    SELECT 
        forum_type_id,
        type
    FROM forum_types
    WHERE type = param_type LIMIT 1;
END $$

DELIMITER ;