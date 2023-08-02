DELIMITER $$

CREATE PROCEDURE WebApp_Logs_GetAll()
BEGIN
    SELECT 
        log_id,
        table_name,
        description,
        date_created,
        user_id
    FROM logs;
END $$

DELIMITER ;