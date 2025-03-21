# Laravel Auth Controller Generator

A simple package to generate authentication controllers with pre-built login and registration functionality.

## Installation

```bash
composer require safia/authcontroller
```

## Usage

After installing the package, you can generate an authentication controller by running:

```bash
php artisan make:auth-controller YourControllerName
```

This will create a controller with pre-built methods for:
- User registration
- User login
- Token-based authentication

## Features

- Quick setup for Laravel API authentication
- Built-in validation
- Supports Laravel Sanctum tokens
- Customizable controller name

## License

MIT
