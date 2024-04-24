DELIMITER $$

CREATE PROCEDURE WebApp_Logs_GetById(IN param_log_id INT(11))
BEGIN
    SELECT 
        log_id,
        table_name,
        description,
        date_created,
        user_id
    FROM logs WHERE log_id = param_log_id LIMIT 1;
END $$

DELIMITER ;