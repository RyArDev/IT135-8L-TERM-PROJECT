CREATE TABLE form_users (
    form_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    is_submitted TINYINT(1) DEFAULT 0,
    PRIMARY KEY (form_id, user_id)
);

ALTER TABLE form_users
ADD CONSTRAINT fk_form_users_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
ADD CONSTRAINT fk_form_users_form_id FOREIGN KEY (form_id) REFERENCES forms(form_id);