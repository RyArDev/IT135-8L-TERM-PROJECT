DELIMITER $$

CREATE PROCEDURE WebApp_Forums_UpdateInfoById(
    IN param_forum_id INT(11),
    IN param_title VARCHAR(300),
    IN param_body VARCHAR(5000)
)
BEGIN
    UPDATE forums
    SET
        title = IFNULL(param_title, title),
        body = IFNULL(param_body, body)
    WHERE forum_id = param_forum_id;
END $$

DELIMITER ;