DELIMITER $$

CREATE PROCEDURE WebApp_Users_Register(
    IN param_username VARCHAR(16),
    IN param_first_name VARCHAR(48),
    IN param_last_name VARCHAR(48),
    IN param_email VARCHAR(64),
    IN param_password VARCHAR(128),
    IN param_birth_date DATE,
    IN param_address VARCHAR(128),
    IN param_gender VARCHAR(16),
    IN param_phone_number VARCHAR(50),
    IN param_job_title VARCHAR(64),
    IN param_job_description VARCHAR(500),
    IN param_refresh_token VARCHAR(32),
    IN param_date_created DATETIME,
    IN param_profile_image_name VARCHAR(64),
    IN param_profile_image_url VARCHAR(128),
    IN param_banner_image_name VARCHAR(64),
    IN param_banner_image_url VARCHAR(128),
    IN param_description VARCHAR(2500),
    IN param_role_id INT(11)
)
BEGIN
    INSERT INTO users (
        username,
        email,
        password,
        refresh_token,
        date_created,
        role_id
    )
    VALUES (
        param_username,
        param_email,
        param_password,
        param_refresh_token,
        param_date_created,
        param_role_id
    );

    CALL WebApp_UserProfiles_RegisterByUserId(
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
        LAST_INSERT_ID()
    );

END $$

DELIMITER ;