DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_UpdateInfoById(
    IN param_announcement_id INT(11),
    IN param_title VARCHAR(300),
    IN param_body VARCHAR(5000),
    IN param_announcement_type_id INT(11)
)
BEGIN
    UPDATE announcements
    SET
        title = IFNULL(param_title, title),
        body = IFNULL(param_body, body),
        announcement_type_id = IFNULL(param_announcement_type_id, announcement_type_id)
    WHERE announcement_id = param_announcement_id;
END $$

DELIMITER ;