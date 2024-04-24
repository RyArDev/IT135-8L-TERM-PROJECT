DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetPasswordByUsername(IN param_username VARCHAR(16))
BEGIN
    SELECT password FROM users WHERE username = param_username LIMIT 1;
END $$

DELIMITER ;