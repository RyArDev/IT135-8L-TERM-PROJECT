DELIMITER $$

CREATE PROCEDURE WebApp_UserProfiles_GetByUserId(IN param_user_id INT(11))
BEGIN
    SELECT 
        user_profile_id,
        first_name,
        last_name,
        birth_date,
        address,
        gender,
        phone_number,
        job_title,
        job_description,
        profile_image_name,
        profile_image_url,
        banner_image_name,
        banner_image_url,
        description
    FROM user_profiles WHERE user_id = param_user_id LIMIT 1;
END $$

DELIMITER ;