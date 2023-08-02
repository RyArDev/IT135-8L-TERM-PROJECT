CREATE TABLE logs (
    log_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(64) NOT NULL,
    description VARCHAR(5000) NOT NULL,
    date_created DATETIME NOT NULL,
    user_id INT(11) UNSIGNED NULL
);

ALTER TABLE logs
ADD CONSTRAINT fk_logs_user_id FOREIGN KEY (user_id) REFERENCES users(user_id);