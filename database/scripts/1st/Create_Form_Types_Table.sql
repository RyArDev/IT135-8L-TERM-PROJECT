CREATE TABLE form_types (
    form_type_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(32) NOT NULL
);

INSERT INTO form_types(type) 
VALUES ('government');