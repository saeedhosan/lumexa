![Lumexa](docs/banner.png)

# Lumexa

Lumexa is a multi-tenant Laravel SaaS platform for managing companies, users, leads, services, and audit activity inside a single workspace. It is built to demonstrate a production-minded Laravel architecture: authenticated tenant switching, policy-driven admin access, queued notifications, cached dashboards, API resources, and activity tracking all live in the same codebase.

## Project Overview

Lumexa is designed for teams that need one application to coordinate multiple companies without mixing data. The application supports:

- secure authentication and email verification through Fortify
- multi-company access control through policies and pivot-based membership
- an onboarding flow for new users
- a Livewire dashboard with cached analytics
- a JSON API for lead retrieval through Sanctum
- audit visibility through Spatie activity logging

The codebase is intentionally split into clear layers:

- `app/Http/Controllers` for request entry points
- `app/Domain` for reusable business logic
- `app/Actions` for transactional workflows
- `app/Http/Requests` for validation and authorization
- `app/Http/Resources` for API output
- `database/seeders` and `database/factories` for realistic demo data

## Technical Core Features

- Multi-tenant company access with user-company pivot roles
- Role-aware admin and customer policy checks
- Fortify-powered authentication, password reset, email verification, and 2FA support
- Sanctum-authenticated API endpoints for lead access
- Livewire onboarding and dashboard experiences
- Cached dashboard statistics and chart payloads
- Queueable invite notifications
- Event/listener activity capture for authentication and lead creation
- Spatie activity logging for model changes and audit trails
- Form Request validation for admin user and company workflows
- API Resources for consistent lead serialization
- Factory-driven demo data for companies, plans, users, leads, lead lists, invites, and logs

## Requirements

- PHP 8.4
- Composer
- Bun
- SQLite, MySQL, or PostgreSQL

## Local Setup

```bash
git clone https://github.com/saeedhosan/lumexa.git
cd lumexa
cp .env.example .env
composer install
php artisan key:generate
```

Configure your database and app settings in `.env`, then run migrations and seed the demo data:

```bash
php artisan migrate --seed
```

Install frontend dependencies and start the app:

```bash
bun install
composer dev
```

### Resetting the Demo

```bash
php artisan migrate:fresh --seed
```

### One-Command Bootstrap

The repository also defines a full setup script:

```bash
composer setup
```

## Demo Access

The seeded database includes ready-to-use demo accounts.

| Role | Email | Password |
| --- | --- | --- |
| Super Admin | `super@example.com` | `demo1234` |
| Admin | `admin@example.com` | `demo1234` |
| User | `user@example.com` | `demo1234` |

### Live Demo

- Application: `https://your-demo-url.example`
- Admin panel: `https://your-admin-url.example/admin`
- API base: `https://your-demo-url.example/api/v1`

## API

Lumexa exposes a versioned API for lead access.

```bash
GET /api/v1/leads
GET /api/v1/leads/{lead}
```

Authentication uses Sanctum tokens. The response payloads are formatted with API resources and include pagination metadata.

## Testing

Run the test suite:

```bash
php artisan test --compact
```

## Code Quality

Recommended checks:

```bash
vendor/bin/pint --dirty --format agent
```

## Architecture Notes

- Companies, users, services, plans, leads, and lead lists are modeled with explicit relationships.
- Controllers stay thin where the architecture is mature, especially in the admin company and user flows.
- Service classes handle transactional write operations.
- Form Requests centralize validation and authorization.
- Seeders are designed to produce a usable demo environment with no manual data entry.

## Repository Structure

```text
app/
├── Actions/
├── Domain/
├── Events/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Jobs/
├── Livewire/
├── Listeners/
├── Models/
├── Notifications/
├── Observers/
└── Policies/

database/
├── factories/
└── seeders/
```

## Notes

- The demo environment is intended to be reproducible from `migrate --seed`.
- If the frontend changes are not visible, run `bun run dev` or rebuild assets with `bun run build`.
