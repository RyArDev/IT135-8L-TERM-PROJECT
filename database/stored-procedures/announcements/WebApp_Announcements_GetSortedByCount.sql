DELIMITER $$

CREATE PROCEDURE WebApp_Announcements_GetSortedByCount(IN param_count INT(11), IN param_sort_order VARCHAR(4))
BEGIN

    IF param_sort_order != 'DESC' OR param_sort_order != 'ASC' THEN
        SET param_sort_order = 'DESC';
    END IF;

    SET @sql = CONCAT('
        SELECT 
            announcement_id,
            title,
            body,
            date_created,
            date_modified,
            user_id,
            announcement_type_id
        FROM announcements 
        ORDER BY date_created ', param_sort_order, '
        LIMIT ', param_count);

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;
