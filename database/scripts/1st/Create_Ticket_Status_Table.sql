CREATE TABLE ticket_status (
    status_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(16) NOT NULL
);

INSERT INTO ticket_status (status_name)
VALUES ('open'), ('closed'), ('in progress'), ('resolved');