CREATE TABLE comment_users (
    comment_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (comment_id, user_id)
);

ALTER TABLE comment_users
ADD CONSTRAINT fk_comment_users_comment_id FOREIGN KEY (comment_id) REFERENCES comments(comment_id),
ADD CONSTRAINT fk_comment_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id);