DELIMITER $$

CREATE PROCEDURE WebApp_Forums_CreateType(
    IN param_type VARCHAR(32)
)
BEGIN
    INSERT INTO forum_types (
        type
    )
    VALUES (
        param_type
    );

END $$

DELIMITER ;