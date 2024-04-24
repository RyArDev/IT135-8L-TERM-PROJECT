DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_DeleteById(
    IN param_announcement_id INT(11)
)
BEGIN
    DELETE FROM announcement_users WHERE announcement_id = param_announcement_id;
    DELETE FROM announcements WHERE announcement_id = param_announcement_id;
END $$

DELIMITER ;