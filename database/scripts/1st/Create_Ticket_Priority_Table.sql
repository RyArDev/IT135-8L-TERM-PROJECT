CREATE TABLE ticket_priority (
    priority_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    priority_name VARCHAR(16) NOT NULL
);

INSERT INTO ticket_priority (priority_name)
VALUES ('low'), ('medium'), ('high'), ('urgent');