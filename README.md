# Simple Ecommerce API

This is a Simple Ecommerce REST-API project built with Laravel. It provides a backend RESTful API for managing products, orders, and user management for an ecommerce store. It also includes features such as email notifications for order status and registration, category and subcategory management, and integration with Stripe payment gateway.

## Features

-   Product management: CRUD operations for managing products including add, delete, and update.
-   Order management: Create, update, and retrieve orders. Email notifications for order status.
-   User management: Manage user account informations and authentication.
-   Category and subcategory management: Organize products into categories and subcategories.
-   Stripe payment integration: Accept payments using the Stripe payment gateway.

## Installation

1. Clone the repository: `git clone https://github.com/mabdusshakur/simp-estore-api.git`
2. Install dependencies: `composer install`
3. Set up your environment variables: Rename `.env.example` to `.env` and update the necessary values.
4. Add your Stripe API secrets: Open the `.env` file and add your Stripe API keys in the `STRIPE_KEY` and `STRIPE_SECRET`.
5. Configure your mail server: Open the `.env` file and update the `MAIL_*` variables with your mail server configurations.
6. Connect the database: Open the `.env` file and update the `DB_*` variables with your database connection details.
7. Generate an application key: `php artisan key:generate`
8. Run database migrations: `php artisan migrate`
9. Seed the database: `php artisan` , `php artisan db:seed --class=CountrySeeder`
10. Start the development server: `php artisan serve`

# API Documentation

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

#### Create Wishlist

-   URL: `/api/wishlists`
-   Method: `POST`
-   Description: Creates a new wishlists.
-   Parameters:
    - `product_id` (integer): The ID of the product the wishlist belongs to. Required. Must exist in the "products" table.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Wishlist created successfully

#### Delete Wishlist

-   URL: `/api/wishlists/{id}`
-   Method: `DELETE`
-   Description: Delete a Wishlist.
-   Parameters:
    -   `id` (integer): The ID of the Wishlist.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Wishlist deleted successfully

#### Delete All Wishlist

-   URL: `/api/wishlists/destroy-all`
-   Method: `POST`
-   Description: Deletes all wishlists belongs to the Authenticated User.
-   Response:
    -   Status Code: `200 OK`
    -   Body: All wishlist deleted successfully

### Cart

#### Get All Cart

-   URL: `/api/carts`
-   Method: `GET`
-   Description: Retrieves a list of all carts.
-   Parameters: None
-   Response:
    -   Status Code: `200 OK`
    -   Body: Array of carts objects

#### Create Cart

-   URL: `/api/carts`
-   Method: `POST`
-   Description: Creates a new carts.
-   Parameters:
    - `product_id` (integer): The ID of the product the cart belongs to. Required. Must exist in the "products" table.
    - `quantity` (integer): The quantity of the product. Required.
-   Response:
    -   Status Code: `201 Created`
    -   Body: Created cart object

#### Update Cart

-   URL: `/api/carts/{id}`
-   Method: `PUT`
-   Description: Updates an existing Cart.
-   Parameters:
    -   `quantity` (integer): The quantity of the product. Required.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Cart updated successfully

#### Increment Cart Quantity (Specific Function) 

-   URL: `carts/increment/{cart}`
-   Method: `POST`
-   Description: Increments the quantity of the product by 1.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Cart Item incremented successfully

#### Decrement Cart Quantity (Specific Function) 

-   URL: `carts/decrement/{cart}`
-   Method: `POST`
-   Description: Decrements the quantity of the product by 1.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Cart Item decremented successfully

#### Delete Cart

-   URL: `/api/carts/{id}`
-   Method: `DELETE`
-   Description: Deletes a carts.
-   Parameters:
    -   `id` (integer): The ID of the carts.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Cart deleted successfully

#### Delete All Cart

-   URL: `/api/carts/destroy-all`
-   Method: `POST`
-   Description: Deletes all Cart items belongs to the Authenticated User.
-   Response:
    -   Status Code: `200 OK`
    -   Body: Cart emptied successfully

### Profile

#### Show Profile

- URL: `/api/profile`
- Method: `GET`
- Description: Retrieves the user's profile.
- Response:
    - Status Code: `200 OK`
    - Body: JSON object containing the user's profile data

#### Update Profile

- URL: `/api/profile`
- Method: `POST`
- Description: Updates the user's profile.
- Request Body:
    - `name` (string, optional): The user's name.
    - `email` (string, optional): The user's email.
    - `phone_number` (numeric, optional): The user's phone number.
    - `address_1` (string, optional): The user's address line 1.
    - `address_2` (string, optional): The user's address line 2.
    - `city` (string, optional): The user's city.
    - `country` (numeric, optional): The user's country ID.
    - `postal_code` (numeric, optional): The user's postal code.
    - `avatar` (file, optional): The user's avatar.
- Response:
    - Status Code: `200 OK`
    - Body: JSON object containing the updated user's profile data

## Order API

### Create Order

- URL: `/api/orders`
- Method: `POST`
- Description: Creates a new order.
- Request Body:
    - `status` (string, required): The status of the order.
    - `payment_method` (string, required): The payment method used for the order.
        - `options`:
            - `cod` (Cash on Delivery)
            - `stripe` (Stripe)
                - `card_number` (string, required): The card number for payment.
                - `exp_month` (string, required): The expiration month of the card.
                - `exp_year` (string, required): The expiration year of the card.
                - `cvc` (string, required): The card verification code.
            - `stripe_intent` (Stripe Payment Intent)
                - `follow #Confirm Stripe Intent Payment, reference`
    - `transaction_id` (string, nullable): The transaction ID for the order.
- Response:
    - Status Code: `201 Created`
    - Body: JSON object containing the created order data

### Get Orders

- URL: `/api/orders`
- Method: `GET`
- Description: Retrieves a list of orders.
- Response:
    - Status Code: `200 OK`
    - Body: JSON array containing the order objects

### Get Order By ID

- URL: `/api/orders/{id}`
- Method: `GET`
- Description: Retrieves a specific order by ID.
- Response:
    - Status Code: `200 OK`
    - Body: JSON object containing the order data


## Contributing

Contributors are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.
