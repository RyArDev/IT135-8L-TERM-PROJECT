DELIMITER $$

CREATE PROCEDURE WebApp_Logs_UpdateById(
    IN param_log_id INT(11),
    IN param_table_name VARCHAR(64),
    IN param_description VARCHAR(5000)
)
BEGIN
    UPDATE logs
    SET
        table_name = IFNULL(param_table_name, table_name),
        description = IFNULL(param_description, description)
    WHERE log_id = param_log_id;
END $$

DELIMITER ;