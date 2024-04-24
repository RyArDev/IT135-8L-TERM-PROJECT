DELIMITER $$

CREATE PROCEDURE WebApp_Users_VerifyDuplicateUsernameById(IN param_user_id INT(11), IN param_username VARCHAR(16))
BEGIN
    IF param_user_id IS NULL THEN
        SELECT COUNT(*) AS username_count FROM users WHERE username = param_username LIMIT 1;
    ELSE
        SELECT COUNT(*) AS username_count FROM users WHERE username = param_username AND user_id != param_user_id LIMIT 1;
    END IF;
END $$

DELIMITER ;