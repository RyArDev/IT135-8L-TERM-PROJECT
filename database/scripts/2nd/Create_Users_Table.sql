CREATE TABLE users (
    user_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(16) UNIQUE NOT NULL,
    email VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(128) NOT NULL,
    refresh_token VARCHAR(32) NOT NULL,
    date_created DATETIME NOT NULL,
    is_active TINYINT(1) DEFAULT 0,
    role_id INT(11) UNSIGNED NOT NULL
);

ALTER TABLE users
ADD CONSTRAINT fk_users_role_id FOREIGN KEY (role_id) REFERENCES roles(role_id);