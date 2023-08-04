DELIMITER $$

CREATE PROCEDURE WebApp_Logs_GetBySearchCriteria(
    IN param_search_value TEXT
)
BEGIN
    SELECT 
        log_id,
        table_name,
        description,
        date_created,
        user_id
    FROM logs
    WHERE (param_search_value IS NULL OR LOWER(log_id) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(table_name) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(description) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(date_created) LIKE CONCAT('%', LOWER(param_search_value), '%'))
      OR (param_search_value IS NULL OR LOWER(user_id) LIKE CONCAT('%', LOWER(param_search_value), '%'));
END $$

DELIMITER ;