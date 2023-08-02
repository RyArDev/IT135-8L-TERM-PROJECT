DELIMITER $$

CREATE PROCEDURE WebApp_Users_UpdateStatusByRefreshToken(
    IN param_refresh_token VARCHAR(32),
    IN param_is_active TINYINT(1)
)
BEGIN
    UPDATE users
    SET 
        is_active = IFNULL(param_is_active, is_active)
    WHERE refresh_token = param_refresh_token;
END $$

DELIMITER ;