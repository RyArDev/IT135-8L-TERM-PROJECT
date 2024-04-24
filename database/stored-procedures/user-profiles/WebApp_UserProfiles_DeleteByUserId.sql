DELIMITER $$

CREATE PROCEDURE WebApp_UserProfiles_DeleteByUserId(
    IN param_user_profile_id INT(11),
    IN param_user_id INT(11)
)
BEGIN
    DELETE FROM user_profiles WHERE user_profile_id = param_user_profile_id AND user_id = param_user_id;
END $$

DELIMITER ;