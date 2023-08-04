DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetById(IN param_forum_id INT(11))
BEGIN
    SELECT 
        f.forum_id,
        f.title,
        f.body,
        f.date_created,
        f.date_modified,
        f.user_id,
        f.forum_type_id,
        t.type
    FROM forums f
    LEFT JOIN forum_types t ON f.forum_type_id = t.forum_type_id
    WHERE f.forum_id = param_forum_id LIMIT 1;
END $$

DELIMITER ;