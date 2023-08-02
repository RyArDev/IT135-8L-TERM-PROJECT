DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_GetBySearchCriteria(
    param_search_value TEXT
)
BEGIN
    SELECT 
        announcement_id,
        title,
        body,
        date_created,
        date_modified,
        user_id,
        announcement_type_id
    FROM announcements
    WHERE 
        (param_search_value IS NULL OR LOWER(announcement_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(title) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(body) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(date_created) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(date_modified) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(user_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(announcement_type_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (
            param_search_value IS NOT NULL AND
            (
                param_search_value = 'barangay' AND announcement_type_id = 1 OR
                param_search_value = 'city' AND announcement_type_id = 2 OR
                param_search_value = 'region' AND announcement_type_id = 3 OR
                param_search_value = 'nation' AND announcement_type_id = 4 OR
                param_search_value = 'emergency' AND announcement_type_id = 5 OR
                param_search_value = 'holiday' AND announcement_type_id = 6
            )
        );
END $$

DELIMITER ;