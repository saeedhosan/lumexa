![Lumexa Logo](/docs/banner.png)
--
![Lumexa demo](/docs/screenshot.png)

<center>

# Lumexa

</center>

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-13.0-red)](https://laravel.com/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.2.0-06B6D4)](https://tailwindcss.com/)
[![Tests](https://img.shields.io/badge/Tests-161-green)](https://phpunit.de/)
[![API](https://img.shields.io/badge/API-REST-brightgreen)](https://laravel.com/)
![Stage](https://img.shields.io/badge/Stage-production-green)

## Table of contents

-   [Introduction](#introduction)
-   [Core Features](#core-features)
-   [API Documentation](#api-documentation)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Architecture](#architecture)
-   [Dependencies](#dependencies)
-   [Technologies](#technologies)
-   [Stable tests](#stable-tests)
-   [Tests Login](#tests-login)

## Introduction

Lumexa is a SaaS application that allows multiple companies to use the same system while keeping their data separate.

It includes a basic role-based system, secure authentication with 2FA, and core features like lead management. The project is being actively developed, with more features like billing, campaigns, and activity tracking planned next.

## Core Features

- **Multi-company support** – Users can work with multiple companies, each with isolated data
- **Authentication** – Secure login with 2FA using Laravel Fortify
- **Access control** – Three roles: Super Admin, Admin, and User
- **Company workspace** – Each company has its own workspace and management area
- **Lead management** – Basic system to create and manage leads
- **User invitations** - Invite new members to companies
- **Activity tracking** - Log and monitor user and system activities
- **REST API** - JSON API for external integrations
- **Event-driven** - Laravel Events & Listeners
- **Background jobs** - Queue processing

## API Documentation

Full API documentation is available in [docs/api.md](/docs/api.md).

**Quick Start:**

```bash
# List leads
curl -X GET "http://localhost:8000/api/v1/leads" \
  -H "Accept: application/json"

# Get single lead
curl -X GET "http://localhost:8000/api/v1/leads/1" \
  -H "Accept: application/json"
```

**Base URL:** `/api/v1`

**Features:**
- RESTful JSON responses
- Pagination support (per_page parameter)
- Rate limited (60 requests/minute)
- API Resources for consistent response formatting

#### Upcoming features

-   **Plan management** - Subscription plans with service limits
-   **Service catalog** - Service product offerings and management
-   **Billing & invoices** - Company billing and invoice generation
-   **Campaign management** - Create and monitor marketing campaigns

## Requirements

-   PHP 8.4+ - [Download PHP](https://www.php.net/downloads.php)
-   Composer - [Get composer](https://getcomposer.org)
-   Node.js 18+ (for frontend assets) - [Install NodeJS](https://nodejs.org/en/download)
-   SQLite (default) or MySQL/PostgreSQL

## Installation

```bash
git clone https://github.com/saeedhosan/lumexa.git
cd lumexa

#1. Setup project
composer setup

#2. Start the development server
composer dev
# visit: http://localhost:8000

# Build assets for production
npm run build
```

## Docker Setup (Optional)

```bash
# Build and run with Docker
docker-compose up -d --build

# Visit: http://localhost:8000

# Stop container
docker-compose down
```

## Architecture

Lumexa follows a layered MVC architecture with middleware-based access control:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── App/                    # User-facing controllers
│   │   ├── Admin/                  # Company admin controllers
│   └── Middleware/                 # Role-based access middleware
├── Models/                         # Eloquent models
├── Enums/                          # Application enumerations
├── Actions/                        # Business logic actions
└── Livewire/                       # Livewire components
└── resources/views/components/     # Livewire components
```

View full architectural [documents](/docs)

**Route Structure:**

-   `/` - Public landing page
-   `/app/*` - User dashboard and features
-   `/admin/*` - Company administration
-   `/admin/*` - System administration
-   `/settings/*` - User profile settings

## Dependencies

| Package           | Version | Purpose                   |
| ----------------- | ------- | ------------------------- |
| laravel/fortify   | 1.36.1  | Authentication backend    |
| livewire/flux     | 2.13.0  | UI component library      |
| livewire/livewire | 4.2.1   | Reactive PHP components   |
| laravel/boost     | 2.3.4   | MCP server & dev tools    |
| pestphp/pest      | 4.4.3   | Testing framework         |
| tailwindcss       | 4.2.0   | CSS framework             |
| maatwebsite/excel | ^3.1    | Excel exports and imports |

## Technologies

-   **Backend:** PHP 8.4, Laravel 13
-   **Frontend:** Blade templates, TailwindCSS 4, Flux UI
-   **Database:** SQLite (default), MySQL/PostgreSQL compatible
-   **Authentication:** Laravel Fortify with 2FA
-   **Testing:** Pest PHP
-   **Code Quality:** Laravel Pint, Rector

## Stable tests

Run tests:

```bash
composer test
```

Run lint:

```bash
composer lint
```

## Tests Login

For testing purposes, you can use the demo [users](/config/demo.php) with UserSeeder.php:

```bash
php artisan migrate:fresh --seed
```

User:

```txt
user: user@example.com
pass: demo1234
```

Admin:

```txt
user: admin@example.com
pass: demo1234
```

You can login and access the welcome page at `http://localhost:8000` to visit the dashboard.
