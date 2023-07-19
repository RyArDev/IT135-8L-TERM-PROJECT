DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetPasswordById(IN param_user_id INT(11))
BEGIN
    SELECT password FROM users WHERE user_id = param_user_id LIMIT 1;
END $$

DELIMITER ;