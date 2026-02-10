
<div align="center">

# 🛒 E-Commerce Marketplace Backend

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)](https://www.php.net)
[![JWT Auth](https://img.shields.io/badge/JWT-Auth-000000?style=for-the-badge&logo=json-web-tokens)](https://jwt.io)

**A robust, scalable, and secure backend API for a modern E-Commerce Marketplace.**

</div>

---

## 🚀 Features Implemented

### 🔐 Authentication & Security
- **JWT Authentication**: Secure API authentication using `php-open-source-saver/jwt-auth`.
- **Role-Based Access Control (RBAC)**: Managed via `spatie/laravel-permission`.
  - **Roles**: `admin`, `seller`, `customer`.
  - **Permissions**: Granular control (e.g., `create products`, `view orders`).
- **SOLID Architecture**: `AuthController` refactored to use **Service Pattern** and **Form Requests** for clean, maintainable code.

### 📦 Product Management
- **CRUD Operations**: Complete management of products.
- **Translatable Fields**: Multi-language support for Product Title and Description (English/Arabic).
- **Access Control**:
  - `GET`: Public/Authenticated users.
  - `POST/PUT/DELETE`: Restricted to **Admin** and **Seller** roles.

### 🛠️ Architecture & Best Practices
- **Service Layer**: Business logic separated from Controllers (e.g., `AuthService`).
- **Form Requests**: Validation logic isolated in dedicated Request classes.
- **API Resources**: Standardized JSON responses.
- **Database Seeding**: Automated setup of roles and default permissions via `RolePermissionSeeder`.

---

## 📚 API Documentation

**Base URL**: `http://127.0.0.1:8000/api/v1`

### 🔑 Authentication

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `POST` | `/auth/register` | Register a new user (Customer) | Public |
| `POST` | `/auth/login` | Login and retrieve JWT token | Public |
| `POST` | `/auth/logout` | Invalidate current token | Authenticated |
| `POST` | `/auth/update-profile` | Update user profile details | Authenticated |

### 🛍️ Products

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `GET` | `/products` | List all products | Authenticated |
| `GET` | `/products/{id}` | View specific product | Authenticated |
| `POST` | `/products` | Create a new product | **Admin, Seller** |
| `PUT` | `/products/{id}` | Update a product | **Admin, Seller** |
| `DELETE` | `/products/{id}` | Delete a product | **Admin, Seller** |

---

## 🧪 Testing with Postman

A ready-to-use **Postman Collection** is included in the project root.

**File**: `e_commerce_api.postman_collection.json`

### Usage:
1.  **Import** the file into Postman.
2.  **Register/Login** to get a token.
3.  The token is automatically saved to the collection variables.
4.  **Test** protected endpoints immediately.

> **Note**: If `localhost` fails in Postman (e.g., on Linux/Snap), run the server with:
> ```bash
> php artisan serve 
> ```

---

## ⚙️ Setup & Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/MohammedTaha187/e-commerce-marketplace-Bacend.git
    cd e-commerce-marketplace
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    ```

3.  **Environment Configuration**
    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan jwt:secret
    ```

4.  **Database Setup**
    - Configure your database in `.env`.
    - Run migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```
    *(This runs `RolePermissionSeeder` automatically if configured in `DatabaseSeeder`, otherwise run `php artisan db:seed --class=RolePermissionSeeder`)*

5.  **Run Server**
    ```bash
    php artisan serve
    ```

---

<div align="center">
  <sub>Built with ❤️ using Laravel</sub>
</div>
