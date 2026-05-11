## ![Lumexa Logo](/docs/banner.png)

![Lumexa](/docs/screenshot.png)

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
-   [Installation](#installation)
    -   [Docker setup](#docker-setup)
    -   [Quick setup](#quick-setup)
-   [Architecture](#architecture)
-   [Dependencies](#dependencies)
-   [Technologies](#technologies)
-   [JSON API](#json-api)
-   [Testing](#testing)

## Introduction

Lumexa is a SaaS application that allows multiple companies to use the same system while keeping their data separate.

### The Problem

Small to medium-sized businesses often struggle with "data sprawl"—using multiple disconnected tools for leads, team management, and internal operations. This leads to security risks, data silos, and high subscription costs.

### The Solution

Lumexa provides a unified, **multi-tenant ecosystem** where companies can securely manage their entire lifecycle—from lead acquisition via Excel imports to team collaboration—all within a single, high-performance platform.

## Core Features

### Authentication & Security

-   **Multi-factor authentication (2FA)** – Secure login with TOTP via Laravel Fortify
-   **Role-based access control** – Three roles: Super Admin, Admin, and User
-   **Email verification** – Email confirmation for user accounts
-   **Password reset** – Secure password recovery flow

### Multi-Tenancy

-   **Multi-company support** – Users can work with multiple companies, each with isolated data
-   **Company workspace** – Each company has its own workspace and management area
-   **User invitations** – Invite new members to companies

### Lead Management

-   **Lead CRUD** – Create, read, update, delete leads
-   **Lead lists** – Individual contacts within leads
-   **Lead status workflow** – Pending, Process, Cleaned, Blocked, Approved, Rejected
-   **Excel import** – Bulk import leads via Excel/CSV files

### API & Integrations

-   **JSON API** – API for external integrations (`/api/v1`)
-   **API Resources** – Consistent JSON response formatting
-   **Rate limiting** – 60 requests/minute per IP
-   **Laravel Sanctum** – Token-based authentication ready

### Performance & Scalability

-   **Dashboard caching** – 5-minute cache for statistics
-   **Background jobs** – Queue processing for heavy tasks
-   **Event-driven** – Laravel Events & Listeners for decoupling

### Activity & Logging

-   **Activity tracking** – Full audit trail with Spatie ActivityLog
-   **System logging** – Comprehensive activity logging

## Installation

```bash
git clone https://github.com/saeedhosan/lumexa.git
cd lumexa
cp .env.example .env
```

### Docker Setup

> Requires [Docker Desktop](https://www.docker.com/products/docker-desktop)

```bash
docker compose down        # stop
docker compose restart     # restart
docker compose exec app bash # enter container

## Get local data for testing
docker compose exec app php artisan migrate:fresh --seed
```

Open [http://localhost:8080] — done!

### Quick setup

**prerequisites**

-   PHP 8.4+ - [Download PHP](https://www.php.net/downloads.php)
-   Composer - [Get composer](https://getcomposer.org)
-   Bun - [Install Bun](https://bun.sh)
-   MySQL 8.0 or SQLite

```bash
#1. Setup project
composer setup

#2. Genenerate local seed data (optional)
php artisan migrate:fresh --seed

#3. Start the development server
composer dev
```

Open [http://localhost:8080] — done!

**Seeder data users**

| Role  | Email             | Password |
| :---- | :---------------- | :------- |
| Admin | admin@example.com | demo1234 |
| User  | user@example.com  | demo1234 |

## Architecture

Lumexa follows a clean Laravel architecture with service layer pattern:

**Technical Decisions & Trade-offs**

| Decision           | Choice             | Rationale                                            |
| :----------------- | :----------------- | :--------------------------------------------------- |
| **Multi-Tenancy**  | Single Database    | Cost-efficient, data isolated via Global Scopes.     |
| **Auth Backend**   | Laravel Fortify    | Battle-tested, headless auth with native 2FA.        |
| **Frontend**       | Livewire + Flux UI | SPA-like reactivity without a separate JS framework. |
| **Testing**        | Pest PHP           | Readable syntax, 160+ tests, stable production env.  |
| **Infrastructure** | Docker             | Consistent environments - development to production. |

View more technical depth in [Technologies](#technologies)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/                     # API controllers
│   │   ├── App/                     # User-facing controllers
│   │   └── Admin/                   # Admin controllers
│   ├── Middleware/                  # Role-based access
│   ├── Resources/                   # API Resources
│   └── Requests/                    # Form requests
├── Models/                          # Eloquent models
├── Domain/                          # Service layer
├── Events/                          # Laravel events
├── Listeners/                       # Event listeners
├── Jobs/                            # Queue jobs
├── Notifications/                   # Notifications
├── Enums/                           # PHP enums
├── Livewire/                        # Livewire components
├── Providers/                       # Service providers
└── Policies/                        # Authorization policies
```

**Route Structure:**

-   `/` - Public landing page
-   `/api/v1/*` - JSON API endpoints
-   `/app/*` - User dashboard and features
-   `/admin/*` - Company administration
-   `/settings/*` - User profile settings
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

## JSON API

Full API docs is available in [docs/api.md](/docs/api.md).

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

-   JSON responses via Laravel API Resources
-   Pagination support (`per_page` parameter)
-   Rate limited (60 requests/minute)
-   Event-driven architecture with Laravel Events
-   Background job processing with Laravel Queues

## Testing

Run tests:

```bash
composer test
```

Run lint:

```bash
composer lint
```
