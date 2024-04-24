DELIMITER $$

CREATE PROCEDURE WebApp_Forums_DeleteTypeById(
    IN param_forum_type_id INT(11)
)
BEGIN
    DELETE fu FROM forum_users fu
    JOIN forums f ON fu.forum_id = f.forum_id
    WHERE f.forum_type_id = param_forum_type_id;
    DELETE FROM forums WHERE forum_type_id = param_forum_type_id;
    DELETE FROM forum_types WHERE forum_type_id = param_forum_type_id;
END $$

DELIMITER ;