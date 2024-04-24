DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetIdByRefreshToken(
    IN param_refresh_token VARCHAR(64)
)
BEGIN
    SELECT 
        user_id
    FROM users 
    WHERE refresh_token = param_refresh_token
    LIMIT 1;
END $$

DELIMITER ;