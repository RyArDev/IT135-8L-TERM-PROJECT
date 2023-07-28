DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_Create(
    IN param_title VARCHAR(300),
    IN param_body VARCHAR(5000),
    IN param_user_id INT(11),
    IN param_announcement_type_id INT(11)
)
BEGIN
    INSERT INTO announcements (
        title,
        body,
        user_id,
        announcement_type_id
    )
    VALUES (
        param_title,
        param_body,
        param_user_id,
        param_announcement_type_id
    );

    INSERT INTO announcement_users (
        announcement_id,
        user_id
    )
    VALUES (
        LAST_INSERT_ID(),
        param_user_id
    );

END $$

DELIMITER ;