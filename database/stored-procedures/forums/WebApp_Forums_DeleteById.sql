DELIMITER $$

CREATE PROCEDURE WebApp_Forums_DeleteById(
    IN param_forum_id INT(11)
)
BEGIN
    DELETE FROM forum_users WHERE forum_id = param_forum_id;
    DELETE FROM forums WHERE forum_id = param_forum_id;
END $$

DELIMITER ;