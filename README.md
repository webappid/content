# laravel-content

**A Laravel-based Content Management System Core Package**

`laravel-content` serves as the foundational package for building a comprehensive Content Management System (CMS) powered by the Laravel framework. It provides the essential structure and functionalities needed to manage your application's content efficiently.

## Requirements

This package requires **Laravel's built-in authentication system** to be set up and configured.

## Installation and Setup

Follow these steps to integrate `laravel-content` into your Laravel project:

1.  **Scaffold Laravel Authentication:**
    ```bash
    php artisan make:auth
    ```

2.  **Run Initial Migrations:**
    ```bash
    php artisan migrate
    ```

3.  **Update User Model Namespace:**
    Move your `User.php` model from the `App\` namespace to `App\Http\Models\`.

    **Before:**
    ```php
    // App/User.php
    namespace App;
    ```

    **After:**
    ```php
    // App/Http/Models/User.php
    namespace App\Http\Models;
    ```

4.  **Create Default Admin User:**
    Ensure you have a default administrator user with an `id` of `1` in your `users` table. This user will be recognized as the admin by the package.

5.  **Install the Package:**
    ```bash
    composer require webappid/content
    ```

6.  **Run Package Migrations:**
    ```bash
    php artisan migrate
    ```
    *(Note: This step is crucial to create the necessary tables for `laravel-content`.)*

7.  **Seed Default Data:**
    ```bash
    php artisan webappid:content:seed
    ```
    This command will insert essential default data for the package. It's designed to **only insert new data** and will not overwrite any existing information in your database.

---

## Support

If you have any questions or encounter issues while using this package, please don't hesitate to reach out. You can email me at **dyan.galih@gmail.com**.

## Acknowledgements

A big thank you to everyone who contributed to the development and improvement of this package!