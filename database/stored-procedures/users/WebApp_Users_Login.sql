DELIMITER $$

CREATE PROCEDURE WebApp_Users_Login(
    IN param_username VARCHAR(16),
    IN param_password VARCHAR(128),
    IN param_refresh_token VARCHAR(32)
)
BEGIN
    DECLARE userCount INT;

    SELECT COUNT(*) INTO userCount
    FROM users 
    WHERE username = param_username AND password = param_password;

    IF userCount = 1 THEN
        UPDATE users
        SET
            refresh_token = IFNULL(param_refresh_token, refresh_token)
        WHERE username = param_username AND password = param_password;
    END IF;

    SELECT 
        user_id,
        username
    FROM users 
    WHERE username = param_username AND password = param_password
    LIMIT 1;
END $$

DELIMITER ;