DELIMITER $$

CREATE PROCEDURE WebApp_UserProfiles_UpdateInfoByUserId(
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
    IN param_banner_image_name  VARCHAR(64),
    IN param_banner_image_url  VARCHAR(128),
    IN param_description  VARCHAR(2500),
    IN param_user_id INT(11)
)
BEGIN
    UPDATE user_profiles
    SET
        first_name = IFNULL(param_first_name, first_name),
        last_name = IFNULL(param_last_name, last_name),
        birth_date = IFNULL(param_birth_date, birth_date),
        address = IFNULL(param_address, address),
        gender = IFNULL(param_gender, gender),
        phone_number = IFNULL(param_phone_number, phone_number),
        job_title = IFNULL(param_job_title, job_title),
        job_description = IFNULL(param_job_description, job_description),
        profile_image_name = IFNULL(param_profile_image_name, profile_image_name),
        profile_image_url = IFNULL(param_profile_image_url, profile_image_url),
        banner_image_name = IFNULL(param_banner_image_name, banner_image_name),
        banner_image_url = IFNULL(param_banner_image_url, banner_image_url),
        description = IFNULL(param_description, description)
    WHERE user_id = param_user_id;
END $$

DELIMITER ;