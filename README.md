# Simple Ecommerce API

This is a Simple Ecommerce REST-API project built with Laravel. It provides a backend RESTful API for managing products, orders, and user management for an ecommerce store. It also includes features such as email notifications for order status and registration, category and subcategory management, and integration with Stripe payment gateway.

## Features

-   Product management: CRUD operations for managing products including add, delete, and update.
-   Order management: Create, update, and retrieve orders. Email notifications for order status.
-   User management: Manage user account informations and authentication.
-   Category and subcategory management: Organize products into categories and subcategories.
-   Stripe payment integration: Accept payments using the Stripe payment gateway.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/mabdusshakur/simp-estore-api.git
    ```

2. Install the dependencies:
    ```bash
    composer install
    ```

3. Configure the environment variables:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    - Update the `.env` file with Database credentials.

4. Run the database migrations:
    ```bash
    php artisan migrate
    ```
5. Setup SMTP mail server:
    - Update the `.env` file with the SMTP mail server details.

6. Configure Stripe credentials:
    - Update the `.env` file with the Stripe API keys.

5. Start the development server:
    ```bash
    php artisan serve
    ```

# API Documentation
* A proper API documentation will be added soon, after finishing the project.

## Base URL

The base URL for all API endpoints is `http://localhost:8000/api`.

## Authentication

Authorization: Bearer <your_token_here>



## Contributing

Contributors are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.
