DELIMITER $$

CREATE PROCEDURE WebApp_Logs_Add(
    IN param_table_name VARCHAR(64),
    IN param_description VARCHAR(5000),
    IN param_date_created DATETIME,
    IN param_user_id INT(11)
)
BEGIN

    IF param_user_id = -1 OR param_user_id = NULL THEN
        SET param_user_id = NULL;
    END IF;

    INSERT INTO logs (
        table_name,
        description,
        date_created,
        user_id
    )
    VALUES (
        param_table_name,
        param_description,
        param_date_created,
        param_user_id
    );

END $$

DELIMITER ;