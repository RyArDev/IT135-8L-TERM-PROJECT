DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetBySearchCriteria(
    IN param_search_value TEXT
)
BEGIN
    SELECT 
        f.forum_id,
        f.title,
        f.body,
        f.date_created,
        f.date_modified,
        f.user_id,
        f.forum_type_id,
        t.type
    FROM forums f
    LEFT JOIN forum_types t ON f.forum_type_id = t.forum_type_id
    WHERE 
        (param_search_value IS NULL OR LOWER(f.forum_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(f.title) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(f.body) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(f.date_created) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(f.date_modified) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(f.user_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(t.type) LIKE CONCAT('%', LOWER(param_search_value), '%'));
END $$

DELIMITER ;