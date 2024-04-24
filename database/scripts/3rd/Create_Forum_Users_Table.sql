CREATE TABLE forum_users (
    forum_id INT(11) UNSIGNED,
    user_id INT(11) UNSIGNED,
    PRIMARY KEY (forum_id, user_id)
);

ALTER TABLE forum_users
ADD CONSTRAINT fk_forum_users_comment_id FOREIGN KEY (forum_id) REFERENCES forums(forum_id),
ADD CONSTRAINT fk_forum_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id);