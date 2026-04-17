# Gir Organic Backend

## Setup Guide (XAMPP)

1. Copy the entire `Gir-Organic` folder into `C:\xampp\htdocs\Gir-Organic`.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Create a database named `gir_organic` or import the provided `database.sql` file.
   - Use the `Import` tab and upload `database.sql`.
5. Update database credentials in `config/config.php` if needed.
   - Default XAMPP credentials are `root` / no password.
6. Make sure the `images/` folder is writable by PHP for image uploads.
7. Open the website in your browser:
   - Frontend: `http://localhost/Gir-Organic/index.php`
   - Admin panel: `http://localhost/Gir-Organic/admin/login.php`

## Default Admin Login

- Username: `admin`
- Password: `admin123`

## Project Structure

- `config/` - database and configuration files
- `models/` - data models for users, products, orders
- `controllers/` - request handlers and business logic
- `views/` - shared frontend templates
- `admin/` - admin dashboard and management pages
- `images/` - product images and uploads
- `database.sql` - database schema and seed data

## Features

- Secure user registration and login with password hashing
- Admin login and protected management panel
- Product CRUD with image upload validation
- Dynamic product listings and WhatsApp order links
- Session-based cart system
- Checkout and order saving with order items
- Multi-language support (English / Gujarati)
- XSS and SQL injection protection using prepared statements
