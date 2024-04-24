DELIMITER $$

CREATE PROCEDURE WebApp_Forums_GetLatest()
BEGIN
    SELECT *
    FROM forums ORDER BY date_created DESC LIMIT 1;
END $$

DELIMITER ;