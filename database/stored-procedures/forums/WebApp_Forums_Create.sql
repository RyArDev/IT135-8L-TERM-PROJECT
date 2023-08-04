DELIMITER $$

CREATE PROCEDURE WebApp_Forums_Create(
    IN param_title VARCHAR(300),
    IN param_body VARCHAR(5000),
    IN param_user_id INT(11),
    IN param_forum_type_id INT(11)
)
BEGIN
    INSERT INTO forums (
        title,
        body,
        user_id,
        forum_type_id
    )
    VALUES (
        param_title,
        param_body,
        param_user_id,
        param_forum_type_id
    );

    INSERT INTO forum_users (
        forum_id,
        user_id
    )
    VALUES (
        LAST_INSERT_ID(),
        param_user_id
    );

END $$

DELIMITER ;