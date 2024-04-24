DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_GetAll()
BEGIN
    SELECT 
        announcement_id,
        title,
        body,
        date_created,
        date_modified,
        user_id,
        announcement_type_id
    FROM announcements;
END $$

DELIMITER ;