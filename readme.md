## About App

This system is to manage their books

## How to use

  - Clone the repository with git clone
  - Copy .env.example file to .env **cp .env.example .env** and edit database credentials there
  - Run **composer install**
  - Run **php artisan key:generate**
  - Run **php artisan migrate --seed** (it has some seeded data for admin user)
  - Run **php artisan jwt:secret**
  - Run **php artisan serve** (it start server on port 8000)
  - Now you can login as admin: launch the main URL and login with default credentials **admin@admin.com** - **1234567890**
  - Fill in the database with Categories
  - That's it

## Requirements

  - PHP >= 7.1.3
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Mbstring PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - Ctype PHP Extension
  - JSON PHP Extension
