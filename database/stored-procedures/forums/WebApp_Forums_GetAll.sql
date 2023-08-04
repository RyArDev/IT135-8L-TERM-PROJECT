DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetAll()
BEGIN
    SELECT 
        forum_id,
        title,
        body,
        date_created,
        date_modified,
        user_id,
        forum_type_id
    FROM forums;
END $$

DELIMITER ;