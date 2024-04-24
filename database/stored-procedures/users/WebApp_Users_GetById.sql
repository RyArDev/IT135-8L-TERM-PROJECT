DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetById(IN param_user_id INT(11))
BEGIN
    SELECT 
        user_id,
        username,
        email,
        date_created,
        is_active,
        role_id
    FROM users WHERE user_id = param_user_id LIMIT 1;
END $$

DELIMITER ;