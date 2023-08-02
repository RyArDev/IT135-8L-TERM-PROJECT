DELIMITER $$

CREATE PROCEDURE WebApp_Logs_GetByTableName(IN param_table_name INT(11))
BEGIN
    SELECT 
        log_id,
        table_name,
        description,
        date_created,
        user_id
    FROM logs WHERE table_name = param_table_name;
END $$

DELIMITER ;