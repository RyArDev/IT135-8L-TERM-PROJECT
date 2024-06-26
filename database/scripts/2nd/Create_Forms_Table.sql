CREATE TABLE forms (
    form_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    form_name VARCHAR(300) UNIQUE NOT NULL,
    form_path VARCHAR(255) UNIQUE NOT NULL,
    date_uploaded TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT(11) UNSIGNED NOT NULL,
    form_type_id INT(11) UNSIGNED NOT NULL
);

ALTER TABLE forms
ADD CONSTRAINT fk_forms_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
ADD CONSTRAINT fk_forms_form_type_id FOREIGN KEY (form_type_id) REFERENCES form_types(form_type_id);