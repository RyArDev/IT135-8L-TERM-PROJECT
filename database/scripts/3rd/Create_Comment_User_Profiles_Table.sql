CREATE TABLE comment_user_profiles (
    comment_id INT(11) UNSIGNED NOT NULL,
    user_profile_id INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (comment_id, user_profile_id)
);

ALTER TABLE comment_user_profiles
ADD CONSTRAINT fk_comment_user_profiles_comment_id FOREIGN KEY (comment_id) REFERENCES comments(comment_id),
ADD CONSTRAINT fk_comment_user_profiles_user_profile_id FOREIGN KEY (user_profile_id) REFERENCES user_profiles(user_profile_id);