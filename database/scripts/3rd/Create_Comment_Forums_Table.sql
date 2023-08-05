CREATE TABLE comment_forums (
    comment_id INT(11) UNSIGNED NOT NULL,
    forum_id INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (comment_id, forum_id)
);

ALTER TABLE comment_forums
ADD CONSTRAINT fk_comment_forums_comment_id FOREIGN KEY (comment_id) REFERENCES comments(comment_id),
ADD CONSTRAINT fk_comment_forums_forum_id FOREIGN KEY (forum_id) REFERENCES forums(forum_id);