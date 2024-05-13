# Online Book Store Website

Welcome to the Online Book Store Website! This website provides a platform for users to browse and purchase books online. It includes functionalities for user registration, email verification, user and admin login, adding books to the inventory by admins, adding items to the cart, checking out, and logging out.

## Features
- **User Registration:** Users can create new accounts by providing necessary details such as username, email, and password.
- **Email Verification:** Upon registration, users receive a verification email to confirm their email address.
- **Login:** Users and admins can log in to their accounts securely using their credentials.
- **User Roles:** The website distinguishes between regular users and admins. Admins have access to additional functionalities such as managing the book inventory.
- **Admin Dashboard:** Admins have a dedicated dashboard where they can add new books to the inventory.
- **Book Inventory:** Users can browse through the available books in the inventory and view details such as title, author, genre, description, and price.
- **Add to Cart:** Users can add books to their cart for purchase.
- **Checkout:** Users can proceed to checkout, review their order, and complete the purchase.
- **Order History:** Users can view their order history to track their past purchases.
- **Logout:** Users and admins can safely log out of their accounts.

## Technologies Used
- PHP
- MySQL/MariaDB
- HTML
- CSS
- JavaScript

## Setup Instructions
1. Clone the repository to your local machine:
    ```bash
    git clone
    ```
2. Import the database schema located in the `database` directory into your MySQL.
    - **Database Schema:**
        - **shop_db**
            - **Tables:**
                - `products`: id, name, author, genre, description, price, pieces, image
                - `cart`: id, user_id, name, price, quantity, image
                - `views`: id, view, product_id, user_id
                - `message`: id, user_id, name, email, number, message
                - `orders`: id, user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status
                - `users`: id, name, email, password, user_type
3. Add config.php file inside the root folder
4. Configure the database connection parameters in the `config.php` file by adding the following
    ```bash
    <?php
    $conn = mysqli_connect('localhost','username','password','shop_db') or die('connection failed');
    ```
   Make sure to put your correct parameter for usename and password of mysql server
5. Set up a web server (e.g., Apache) and configure it to serve the website files.
6. Access the website through your web browser and start using the Online Book Store!

## Contributing
Contributions are welcome! If you find any issues or have suggestions for improvements, feel free to open an issue or create a pull request.
