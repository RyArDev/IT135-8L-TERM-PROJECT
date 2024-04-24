DELIMITER $$

CREATE PROCEDURE WebApp_UserProfiles_VerifyDuplicatePhoneNumberByUserId(IN param_user_id INT(11), IN param_phoneNumber VARCHAR(50))
BEGIN
    IF param_user_id IS NULL THEN
        SELECT COUNT(*) AS phone_number_count FROM user_profiles WHERE phone_number = param_phoneNumber LIMIT 1;
    ELSE
        SELECT COUNT(*) AS phone_number_count FROM user_profiles WHERE phone_number = param_phoneNumber AND user_id != param_user_id LIMIT 1;
    END IF;
END $$

DELIMITER ;