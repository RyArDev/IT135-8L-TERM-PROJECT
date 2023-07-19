DELIMITER $$

CREATE PROCEDURE WebApp_Users_Login(
    IN param_username VARCHAR(16),
    IN param_password VARCHAR(128)
)
BEGIN
    SELECT 
        user_id,
        username
    FROM users WHERE username = param_username AND password = param_password LIMIT 1;
END $$

DELIMITER ;