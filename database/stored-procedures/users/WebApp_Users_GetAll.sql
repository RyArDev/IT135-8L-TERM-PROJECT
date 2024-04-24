DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetAll()
BEGIN
    SELECT 
        u.user_id,
        u.username,
        u.email,
        u.date_created,
        u.is_active,
        u.role_id,
        p.user_profile_id,
        p.first_name,
        p.last_name,
        p.birth_date,
        p.address,
        p.gender,
        p.phone_number,
        p.job_title,
        p.job_description,
        p.profile_image_name,
        p.profile_image_url,
        p.banner_image_name,
        p.banner_image_url,
        p.description
    FROM users u
    LEFT JOIN user_profiles p ON u.user_id = p.user_id;
END $$

DELIMITER ;