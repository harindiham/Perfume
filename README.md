# Perfume Store Management System

A Laravel-based perfume store management system developed as an Advanced Programming project. The system provides a perfume catalogue, category management, administrator functionality, authentication, a REST API, external exchange-rate integration, and automated testing.

## Technologies Used

- Laravel 13.34.0
- PHP 8.5.10
- SQLite
- Livewire 3.8.10
- Laravel Jetstream 5.5.3
- Laravel Sanctum 4.3.3
- Tailwind CSS
- Vite
- PHPUnit

## Main Features

### Customer Catalogue

- Browse available perfumes
- View individual perfume details
- View perfume descriptions and fragrance notes
- Browse perfumes by category
- View price and stock information
- Search and filter perfumes

### Administrator Features

- Secure administrator authentication
- Create perfumes
- Edit perfumes
- Delete perfumes
- Upload perfume images
- Manage perfume categories
- View stock information
- Prevent deletion of categories containing perfumes

## REST API

The application provides an authenticated REST API for accessing perfume and category data.

### Authentication

```text
POST /api/login
POST /api/logout
GET  /api/user
