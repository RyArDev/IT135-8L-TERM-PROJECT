DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_GetLatest()
BEGIN
    SELECT *
    FROM announcements ORDER BY date_created DESC LIMIT 1;
END $$

DELIMITER ;