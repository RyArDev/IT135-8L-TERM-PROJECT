DELIMITER $$

CREATE PROCEDURE WebApp_Users_UpdateStatusById(
    IN param_user_id INT(11),
    IN param_is_active TINYINT(1)
)
BEGIN
    UPDATE users
    SET 
        is_active = IFNULL(param_is_active, is_active)
    WHERE user_id = param_user_id;
END $$

DELIMITER ;