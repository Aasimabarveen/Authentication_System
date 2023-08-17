# Simple Authentication System with User Profile Update

This is a basic authentication system that allows users to register, log in, view their profiles, and update their basic details. The system is built using HTML, CSS, JavaScript, jQuery, AJAX for the front end, PHP for the API, and backend scripting, and utilizes MySQL for communicating with the database. JWT (JSON Web Tokens) are used for authentication and user session management.

## Features

- User Registration: New users can create accounts by providing their basic details(Name, Dob, contact, username, password).
- User Login: Registered users can log in using their username and password.
- User Profile: Once logged in, users can view their profile, which contains personal details.
- Profile Update: Users can update their basic details, and the changes are reflected in the database.
- JWT Authentication: JWTs are used to authenticate users and manage sessions.
- Database Interaction: PHP scripts handle interactions with the MySQL database.

## Technologies Used

- HTML, CSS, JavaScript, jQuery: For creating the frontend user interface and interactions.
- AJAX: Used for asynchronous requests to the backend, enhancing user experience.
- PHP: A backend scripting language for handling API requests and database interactions.
- MySQL: A database system for storing user data securely.
- JWT: JSON Web Tokens for user authentication and session management.
- SessionStorage: Used to store JWTs on the client side for maintaining user sessions.


[NOTE: Need to place the API files and back-end server script files to a server and provide the corresponding location in the Ajax async call URL to ensure the communication between front end and APIs.]
