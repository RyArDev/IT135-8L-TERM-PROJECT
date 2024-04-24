DELIMITER $$

CREATE PROCEDURE WebApp_UserProfiles_RegisterByUserId(
    IN param_first_name VARCHAR(48),
    IN param_last_name VARCHAR(48),
    IN param_birth_date DATE,
    IN param_address VARCHAR(128),
    IN param_gender VARCHAR(16),
    IN param_phone_number VARCHAR(50),
    IN param_job_title VARCHAR(64),
    IN param_job_description VARCHAR(2500),
    IN param_profile_image_name VARCHAR(64),
    IN param_profile_image_url VARCHAR(128),
    IN param_banner_image_name VARCHAR(64),
    IN param_banner_image_url VARCHAR(128),
    IN param_description VARCHAR(2500),
    IN param_user_id INT(11)
)
BEGIN

    IF param_phone_number = "" OR param_phone_number = NULL THEN
        SET param_phone_number = NULL;
    END IF;

    INSERT INTO user_profiles (
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
        description,
        user_id
    )
    VALUES (
        param_first_name,
        param_last_name,
        param_birth_date,
        param_address,
        param_gender,
        param_phone_number,
        param_job_title,
        param_job_description,
        param_profile_image_name,
        param_profile_image_url,
        param_banner_image_name,
        param_banner_image_url,
        param_description,
        param_user_id
    );
END $$

DELIMITER ;