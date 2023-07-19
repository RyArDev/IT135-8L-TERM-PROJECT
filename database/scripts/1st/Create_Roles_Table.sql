CREATE TABLE roles (
    role_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(32) NOT NULL
);

INSERT INTO roles(type) 
VALUES ('none'), ('user'), ('officer'), ('admin');