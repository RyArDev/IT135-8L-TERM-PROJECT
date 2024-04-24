CREATE TABLE comment_types (
    comment_type_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(32) NOT NULL
);

INSERT INTO comment_types(type) 
VALUES ('announcement'), ('forum'), ('profile');