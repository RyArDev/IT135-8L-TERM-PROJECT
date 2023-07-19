CREATE TABLE announcements (
    announcement_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(300) NOT NULL,
    body VARCHAR(5000) NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT(11) UNSIGNED NOT NULL,
    announcement_type_id INT(11) UNSIGNED NOT NULL
);

ALTER TABLE announcements
ADD CONSTRAINT fk_announcements_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
ADD CONSTRAINT fk_announcements_announcement_type_id FOREIGN KEY (announcement_type_id) REFERENCES announcement_types(announcement_type_id);