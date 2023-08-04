DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetAllByType(IN param_type VARCHAR(32))
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
    WHERE LOWER(t.type) LIKE CONCAT('%', LOWER(param_type), '%');
END $$

DELIMITER ;