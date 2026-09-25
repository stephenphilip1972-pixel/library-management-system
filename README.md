# Library Management System

PHP 8 + MySQL/MariaDB starter application for managing books, members, issue/return transactions and fines.

## Stack
- PHP 8.1+
- MySQL 8 / MariaDB
- Bootstrap 5

## Install
1. Put the repository in WAMP/XAMPP web root.
2. Import `database/schema.sql`.
3. Edit `config/database.php`.
4. Generate an administrator password with PHP `password_hash()` and insert it into `users`.
5. Open `login.php`.

## Modules
Login, dashboard, books, members, issue/return, overdue/fine calculation.
