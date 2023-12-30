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

## Endpoints

### Products

#### Get All Products

-   URL: `/api/products`
-   Method: `GET`
-   Description: Retrieves a list of all products.
-   Parameters: None
-   Response:
    -   Status Code: `200 OK`
    -   Body: Array of product objects

#### Get Product by ID

-   URL: `/api/products/{id}`
-   Method: `GET`
-   Description: Retrieves a specific product by its ID.
-   Parameters:
    -   `id` (integer): The ID of the product.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Product object

#### Create Product

-   URL: `/api/products`
-   Method: `POST`
-   Description: Creates a new product.
-   Parameters:
    -   `name` (string): The name of the product. Required. Maximum length of 255 characters.
    -   `slug` (string): The slug of the product (generated from the name).
    -   `description` (string): The description of the product. Required.
    -   `regular_price` (float): The regular price of the product. Required. Must be a numeric value.
    -   `sale_price` (float): The sale price of the product. Optional. Must be a numeric value.
    -   `category_id` (integer): The ID of the category the product belongs to. Required. Must exist in the "categories" table.
    -   `subcategory_id` (integer): The ID of the subcategory the product belongs to. Required. Must exist in the "sub_categories" table.
    -   `status` (string): The status of the product. Required. Must be a boolean value.
    -   `stock` (integer): The stock quantity of the product. Required. Must be an integer value.
    -   `images` (required): The images of the product. Required.
-   Response:
    -   Status Code: `201 Created`
    -   Body: Created product object

#### Update Product

-   URL: `/api/products/{id}`
-   Method: `PUT`
-   Description: Updates an existing product.
-   Parameters:
    -   `name` (string): The name of the product. Required. Maximum length of 255 characters.
    -   `slug` (string): The slug of the product (generated from the name).
    -   `description` (string): The description of the product. Required.
    -   `regular_price` (float): The regular price of the product. Required. Must be a numeric value.
    -   `sale_price` (float): The sale price of the product. Optional. Must be a numeric value.
    -   `category_id` (integer): The ID of the category the product belongs to. Required. Must exist in the "categories" table.
    -   `subcategory_id` (integer): The ID of the subcategory the product belongs to. Required. Must exist in the "sub_categories" table.
    -   `status` (string): The status of the product. Required. Must be a boolean value.
    -   `stock` (integer): The stock quantity of the product. Required. Must be an integer value.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Updated product object

#### Delete Product

-   URL: `/api/products/{id}`
-   Method: `DELETE`
-   Description: Deletes a product.
-   Parameters:
    -   `id` (integer): The ID of the product.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Product deleted successfully

### Categories

#### Get All Categories

-   URL: `/api/categories`
-   Method: `GET`
-   Description: Retrieves a list of all categories.
-   Parameters: None
-   Response:
    -   Status Code: `200 OK`
    -   Body: Array of categories objects

#### Get Category by ID

-   URL: `/api/categories/{id}`
-   Method: `GET`
-   Description: Retrieves a specific categories by its ID.
-   Parameters:
    -   `id` (integer): The ID of the categories.
-   Response:
    -   Status Code: `200 OK`
    -   Body: categories object

#### Create Category

-   URL: `/api/categories`
-   Method: `POST`
-   Description: Creates a new category.
-   Parameters:
    -   `name` (string): The name of the category. Required. Maximum length of 255 characters. Unique.
    -   `slug` (string): The slug of the category (generated from the name).
-   Response:
    -   Status Code: `201 Created`
    -   Body: Created category object

#### Update Category

-   URL: `/api/category/{id}`
-   Method: `PUT`
-   Description: Updates an existing category.
-   Parameters:
    -   `name` (string): The name of the category. Required. Maximum length of 255 characters. Unique.
    -   `slug` (string): The slug of the category (generated from the name).
-   Response:
    -   Status Code: `200 OK`
    -   Body: Updated category object

#### Delete Category

-   URL: `/api/category/{id}`
-   Method: `DELETE`
-   Description: Deletes a category.
-   Parameters:
    -   `id` (integer): The ID of the category.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Category deleted successfully


### Sub-Categories

#### Get All Sub-Categories

-   URL: `/api/sub-categories`
-   Method: `GET`
-   Description: Retrieves a list of all Sub-Categories.
-   Parameters: None
-   Response:
    -   Status Code: `200 OK`
    -   Body: Array of Sub-Categories objects

#### Get Sub-Category by ID

-   URL: `/api/sub-categories/{id}`
-   Method: `GET`
-   Description: Retrieves a specific Sub-Categories by its ID.
-   Parameters:
    -   `id` (integer): The ID of the Sub-Categories.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Sub-Categories object

#### Create Sub-Category

-   URL: `/api/sub-categories`
-   Method: `POST`
-   Description: Creates a new Sub-Category.
-   Parameters:
    -   `name` (string): The name of the Sub-Category. Required. Maximum length of 255 characters. Unique.
    -   `slug` (string): The slug of the Sub-Category (generated from the name).
    -   `category_id` (integer): The ID of the category the Sub-Category belongs to. Required. Must exist in the "categories" table.
-   Response:
    -   Status Code: `201 Created`
    -   Body: Created Sub-Category object

#### Update Sub-Category

-   URL: `/api/sub-categories/{id}`
-   Method: `PUT`
-   Description: Updates an existing Sub-Category.
-   Parameters:
    -   `name` (string): The name of the Sub-Category. Required. Maximum length of 255 characters. Unique.
    -   `slug` (string): The slug of the Sub-Category (generated from the name).
    -   `category_id` (integer): The ID of the category the Sub-Category belongs to. Required. Must exist in the "categories" table.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Updated Sub-Category object

#### Delete Sub-Category

-   URL: `/api/sub-categories/{id}`
-   Method: `DELETE`
-   Description: Deletes a Sub-Category.
-   Parameters:
    -   `id` (integer): The ID of the Sub-Category.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Sub-Category deleted successfully


### Wishlist

#### Get All Wishlist

-   URL: `/api/wishlists`
-   Method: `GET`
-   Description: Retrieves a list of all wishlists.
-   Parameters: None
-   Response:
    -   Status Code: `200 OK`
    -   Body: Array of wishlists objects



## Contributing

Contributors are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.
