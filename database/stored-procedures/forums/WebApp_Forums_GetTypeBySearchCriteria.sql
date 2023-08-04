DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetTypeBySearchCriteria(IN param_search_value TEXT)
BEGIN
    SELECT 
        forum_type_id,
        type
    FROM forum_types
    WHERE 
        (param_search_value IS NULL OR LOWER(forum_type_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
        OR (param_search_value IS NULL OR LOWER(type) LIKE CONCAT('%', LOWER(param_search_value), '%'));
END $$

DELIMITER ;