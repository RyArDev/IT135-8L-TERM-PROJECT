CREATE TABLE announcement_users (
    announcement_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (announcement_id, user_id)
);

ALTER TABLE announcement_users
ADD CONSTRAINT fk_announcement_users_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id),
ADD CONSTRAINT fk_announcement_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id);