DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetAllTypes()
BEGIN
    SELECT 
        forum_type_id,
        type
    FROM forum_types;
END $$

DELIMITER ;