CREATE TABLE announcement_types (
    announcement_type_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(32) NOT NULL
);

INSERT INTO announcement_types(type) 
VALUES ('barangay'), ('city'), ('region'), ('nation'), ('emergency'), ('holiday');