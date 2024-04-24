DELIMITER $$

CREATE PROCEDURE WebApp_Users_GetBySearchCriteria(
    IN param_search_value TEXT
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
      OR (param_search_value IS NULL OR LOWER(u.email) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (
            param_search_value IS NOT NULL AND
            (
                param_search_value = 'None' AND u.role_id = 1 OR
                param_search_value = 'User' AND u.role_id = 2 OR
                param_search_value = 'Officer' AND u.role_id = 3 OR
                param_search_value = 'Admin' AND u.role_id = 4
            )
        );
END $$

DELIMITER ;