DELIMITER $$

CREATE PROCEDURE WebApp_Users_VerifyDuplicateEmailById(IN param_user_id INT(11), IN param_email VARCHAR(64))
BEGIN
    IF param_user_id IS NULL THEN
        SELECT COUNT(*) AS email_count FROM users WHERE email = param_email LIMIT 1;
    ELSE
        SELECT COUNT(*) AS email_count FROM users WHERE email = param_email AND user_id != param_user_id LIMIT 1;
    END IF;
END $$

DELIMITER ;