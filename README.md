# IT135-8L-TERM-PROJECT

## Barangay Dynamic Website

This is a dynamic website developed for Barangay Santa Cruz to provide various functionalities and features. The website includes the following key components:

## Functionalities/Features

### 1. Login/Registration Page

- Users can create an account and log in to access personalized features.
- Registration form collects necessary user information for account creation.
- Login page verifies user credentials and grants access to the website's restricted areas.

### 2. Admin Web Pages

- Admin-specific web pages designed for managing the website and performing administrative tasks.
- Accessible only to authorized administrators with appropriate permissions.
- Admin functions may include user management, content moderation, data analytics, and system configuration.

### 3. User Web Pages

- Web pages tailored for general users, providing various services and features.
- Users can view public information, participate in discussions, interact with community members, etc.
- User-specific features may include profile management, event registration, document downloads, community forums, etc.

### 4. Database Connection

- The website utilizes a database to store and manage data related to users, content, and other relevant information.
- Data is securely stored and accessed through appropriate database queries.
- Database connection ensures efficient data retrieval, storage, and manipulation.

## Technologies Used

- Programming Languages: HTML, CSS, JavaScript, PHP
- Database Management System: MySQL
- Web Development Framework: XAMPP (Apache, MySQL, PHP, phpMyAdmin)

## Getting Started

To set up the project locally and start using the website with XAMPP, follow these steps:

1. Install XAMPP on your development machine by downloading it from the [official website](https://www.apachefriends.org/index.html).
2. Clone the repository: `git clone https://github.com/RyArDev/IT135-8L-TERM-PROJECT.git`.
3. Copy the project folder into the `htdocs` directory of your XAMPP installation.
4. Start XAMPP and ensure that the Apache and MySQL services are running.
5. Access phpMyAdmin through `http://localhost/phpmyadmin` and create a new database for the website.
6. Import the provided SQL file into the newly created database to set up the necessary tables and data.
7. Configure the database connection settings in the website's configuration files.
8. Do composer install to download the dependencies of the project.
9. Open a web browser and access the website at `http://localhost/your-project-folder` or `http://localhost`.
10. You can now start using and modifying the website locally using XAMPP.

## Contributing

Contributions to the project are welcome. If you encounter any issues or have suggestions for improvements, please submit an issue or a pull request.

## License

This project is licensed under the [MIT License](LICENSE).