CREATE TABLE tickets (
    ticket_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(300) NOT NULL,
    body VARCHAR(5000) NOT NULL,
    date_created DATETIME NOT NULL,
    date_modified DATETIME NOT NULL,
    user_id INT(11) UNSIGNED NULL,
    status_id INT(11) UNSIGNED NOT NULL,
    priority_id INT(11) UNSIGNED NOT NULL
);

ALTER TABLE tickets
ADD CONSTRAINT fk_tickets_user_id FOREIGN KEY (user_id) REFERENCES users(user_id),
ADD CONSTRAINT fk_tickets_status_id FOREIGN KEY (status_id) REFERENCES ticket_status(status_id),
ADD CONSTRAINT fk_tickets_priority_id FOREIGN KEY (priority_id) REFERENCES ticket_priority(priority_id);