DELIMITER $$

CREATE PROCEDURE WebApp_Users_DeleteById(
    IN param_user_id INT(11),
    IN param_user_profile_id INT(11)
)
BEGIN
    DELETE FROM users WHERE user_id = param_user_id;
    CALL WebApp_UserProfiles_DeleteByUserId(param_user_profile_id, param_user_id);
END $$

DELIMITER ;