DELIMITER $$

CREATE PROCEDURE WebApp_Forums_UpdateTypeInfoById(
    IN param_forum_type_id INT(11),
    IN param_type VARCHAR(32)
)
BEGIN
    UPDATE forum_types
    SET
        type = IFNULL(param_type, type)
    WHERE forum_id = param_forum_id;
END $$

DELIMITER ;