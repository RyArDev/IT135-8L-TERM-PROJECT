CREATE TABLE comments (
    comment_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    body VARCHAR(1000) NOT NULL,
    date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT(11) UNSIGNED NOT NULL,
    comment_type_id INT(11) UNSIGNED NOT NULL
);

ALTER TABLE comments
ADD CONSTRAINT fk_comments_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
ADD CONSTRAINT fk_comments_comment_type_id FOREIGN KEY (comment_type_id) REFERENCES comment_types(comment_type_id);