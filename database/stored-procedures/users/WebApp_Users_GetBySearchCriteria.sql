DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetBySearchCriteria(
    param_search_value TEXT
)
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
    LEFT JOIN user_profiles p ON u.user_id = p.user_id
    WHERE (param_search_value IS NULL OR LOWER(u.user_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(u.username) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(p.first_name) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(p.last_name) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(u.email) LIKE CONCAT('%', LOWER(param_search_value), '%'));
END $$

DELIMITER ;