DELIMITER $$

CREATE PROCEDURE WebApp_Logs_DeleteById(
    IN param_log_id INT(11)
)
BEGIN
    DELETE FROM logs WHERE log_id = param_log_id;
END $$

DELIMITER ;