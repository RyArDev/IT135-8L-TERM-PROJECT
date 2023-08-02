DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetAllActive()
BEGIN
    SELECT 
        u.user_id,
        u.username,
        u.email,
        u.is_active,
        u.role_id,
        p.user_profile_id,
        p.first_name,
        p.last_name,
        p.profile_image_name,
        p.profile_image_url
    FROM users u
    LEFT JOIN user_profiles p ON u.user_id = p.user_id
    WHERE u.is_active = 1;
END $$

DELIMITER ;