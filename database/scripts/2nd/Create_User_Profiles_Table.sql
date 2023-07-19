CREATE TABLE user_profiles (
    user_profile_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(48) NOT NULL,
    last_name VARCHAR(48) NOT NULL,
    birth_date DATE NOT NULL,
    address VARCHAR(128) NOT NULL,
    gender VARCHAR(16) NOT NULL,
    phone_number VARCHAR(50) UNIQUE NOT NULL,
    job_title VARCHAR(64) DEFAULT 'Not Available',
    job_description VARCHAR(500) DEFAULT 'Not Available',
    profile_image_name VARCHAR(64) NOT NULL,
    profile_image_url VARCHAR(128) NOT NULL,
    banner_image_name VARCHAR(64) NOT NULL,
    banner_image_url VARCHAR(128) NOT NULL,
    description VARCHAR(2500) DEFAULT 'Not Available',
    user_id INT(11) UNSIGNED NOT NULL
);

ALTER TABLE user_profiles
ADD CONSTRAINT fk_user_profiles_user_id FOREIGN KEY (user_id) REFERENCES users(user_id);