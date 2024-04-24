DELIMITER $$

CREATE PROCEDURE WebApp_Users_UpdatePasswordById(
    IN param_user_id INT(11),
    IN param_password VARCHAR(128)
)
BEGIN
    UPDATE users
    SET 
        password = IFNULL(param_password, password)
    WHERE user_id = param_user_id;
END $$

DELIMITER ;