DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_GetById(IN param_announcement_id INT(11))
BEGIN
    SELECT 
        announcement_id,
        title,
        body,
        date_created,
        date_modified,
        user_id,
        announcement_type_id
    FROM announcements WHERE announcement_id = param_announcement_id LIMIT 1;
END $$

DELIMITER ;