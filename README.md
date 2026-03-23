![Lumexa Logo](/docs/banner.png)

<center>

# Lumexa

</center>

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-13.0.0-red)](https://laravel.com/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.2.0-06B6D4)](https://tailwindcss.com/)
[![Tests](https://img.shields.io/badge/Tests-38%20passed-green)](https://phpunit.de/)
![Stage](https://img.shields.io/badge/Stage-development-yellow)

## Table of contents

-   [Introduction](#introduction)
-   [Feature list](#feature-list)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Architecture](#architecture)
-   [Dependencies](#dependencies)
-   [Technologies](#technologies)
-   [Stable tests](#stable-tests)
-   [Tests Login](#tests-login)

## Introduction

Lumexa is a multi-tenant SaaS application built on Laravel 13 with a hierarchical role-based access control system. The platform supports three distinct user tiers: Super Administrators who manage the entire system, Admin who oversee company-level operations, and regular Users who interact with the core business features including leads, campaigns, services, and activities.

## Feature list

-   **Multi-Tenant architecture** - Companies with individual workspaces and billing
-   **Role-Based access control** - Three-tier hierarchy: Super Admin, Admin, User
-   **Authentication system** - Secure login with 2FA support via Laravel Fortify
-   **Lead management** - Track and manage sales leads through the pipeline
-   **Campaign management** - Create and monitor marketing campaigns
-   **Service catalog** - Service product offerings and management
-   **Activity tracking** - Log and monitor user and system activities
-   **Billing & invoices** - Company billing and invoice generation
-   **Company management** - Multi-company support with plan-based quotas
-   **User invitations** - Invite new members to companies
-   **System logging** - Comprehensive audit logging
-   **Plan management** - Subscription plans with service limits

Note: Some features (lead management, campaigns, billing, etc.) are still in progress or placeholders.

## Requirements

-   PHP 8.4+
-   Composer
-   Node.js 18+ (for frontend assets)
-   SQLite (default) or MySQL/PostgreSQL
-   Web server (Nginx/Apache)

## Installation

```bash
git clone https://github.com/your-org/lumexa.git
cd lumexa

# Setup project
composer setup

# Start the development server
composer dev

# Build assets for production
npm run build
```

## Architecture

Lumexa follows a layered MVC architecture with middleware-based access control:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── App/         # User-facing controllers
│   │   ├── Admin/       # Company admin controllers
│   │   └── Super/       # Super admin controllers
│   └── Middleware/      # Role-based access middleware
├── Models/              # Eloquent models
├── Enums/               # Application enumerations
├── Actions/             # Business logic actions
└── Livewire/            # Livewire components
```

**Route Structure:**

-   `/` - Public landing page
-   `/app/*` - User dashboard and features
-   `/admin/*` - Company administration
-   `/super/*` - System administration
-   `/settings/*` - User profile settings

## Dependencies

| Package           | Version | Purpose                 |
| ----------------- | ------- | ----------------------- |
| laravel/fortify   | 1.36.1  | Authentication backend  |
| livewire/flux     | 2.13.0  | UI component library    |
| livewire/livewire | 4.2.1   | Reactive PHP components |
| laravel/boost     | 2.3.4   | MCP server & dev tools  |
| pestphp/pest      | 4.4.3   | Testing framework       |
| tailwindcss       | 4.2.0   | CSS framework           |

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
You can login and access the welcome page at `http://localhost:8000` to visit the dasbhoard.