# Authentication System API

This section of the project contains the PHP scripts responsible for handling various user actions and interactions with the database. The API includes endpoints for user registration, login, profile viewing, and profile updating, as well as JWT token generation and validation for secure user sessions.

## API Endpoints

- `register.php`: Handles user registration by accepting user data and securely storing it in the database.
- `login.php`: Authenticates user credentials and generates a JWT token upon successful login.
- `update.php`: Retrieves user profile data from the front end and updates db and sends success message along with token with updated profile data.

## JWT Token Handling

- `token_handling.php`: Implements JWT token generation and validation. It provides functions to create tokens and validate tokens.


