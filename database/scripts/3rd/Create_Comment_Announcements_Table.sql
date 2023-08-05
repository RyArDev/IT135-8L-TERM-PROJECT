CREATE TABLE comment_announcements (
    comment_id INT(11) UNSIGNED NOT NULL,
    announcement_id INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (comment_id, announcement_id)
);

ALTER TABLE comment_announcements
ADD CONSTRAINT fk_comment_announcements_comment_id FOREIGN KEY (comment_id) REFERENCES comments(comment_id),
ADD CONSTRAINT fk_comment_announcements_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id);