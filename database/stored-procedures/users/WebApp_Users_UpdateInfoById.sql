DELIMITER $$

CREATE PROCEDURE WebApp_Users_UpdateInfoById(
    IN param_user_id INT(11),
    IN param_username VARCHAR(16),
    IN param_email VARCHAR(64)
)
BEGIN
    UPDATE users
    SET
        username = IFNULL(param_username, username),
        email = IFNULL(param_email, email)
    WHERE user_id = param_user_id;
END $$

DELIMITER ;