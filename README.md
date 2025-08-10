<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Multi-Vendor E-commerce Platform

This project is a multi-vendor e-commerce platform built with [Laravel](https://laravel.com). It features a basic UI, robust session and database cart management, vendor grouping during checkout, and separate panels for admin and vendors.

---

## Features

- **Basic UI:** The user interface is currently simple and functional.
- **Cart Functionality:**
  - Products can be added to the cart without logging in (stored in session).
  - When logged in, cart items are stored in the database.
- **Checkout Process:**
  - Users can checkout all products in their cart.
  - Products are grouped by vendors during checkout.
- **Admin Panel:**
  - Manage vendors and oversee platform operations.
- **Vendor Panel:**
  - Vendors can manage their own products and orders.

---

## Project Setup

Follow these steps to set up the project on your local machine:

1. **Clone the Repository**
   ```bash
   git clone <your-repo-url>
   cd multi-vendor
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   npm run dev
   ```

3. **Environment Configuration**
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Set your MySQL database credentials in the `.env` file:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_database_user
     DB_PASSWORD=your_database_password
     ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```

---

## Credentials

You can use the following credentials to log in as different user roles:

| Role      | Email                      | Password |
|-----------|----------------------------|----------|
| Admin     | admin@gmail.com            | 123456   |
| Customer  | customer@gmail.com         | 123456   |
| Vendor 1  | vendor1@gmail.com          | 123456   |
| Vendor 2  | vendor2@gmail.com          | 123456   |

---

## About the Project

- The UI is intentionally kept basic for now, focusing on core functionality.
- Products can be added to the cart without authentication; these are stored in the session. Once the user logs in, the cart is persisted in the database.
- During checkout, products are grouped by their respective vendors, allowing for a seamless multi-vendor checkout experience.
- The admin panel allows for comprehensive vendor management.
- Vendors have their own panel to manage their products and orders independently.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

