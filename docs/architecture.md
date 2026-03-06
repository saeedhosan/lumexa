# Lumexa - Complete Documentation

**Project Name:** Lumexa / nova-portal  
**Framework:** Laravel 12 | **PHP:** 8.2+ | **Type:** Multi-tenant SaaS

---

## Table of Contents

1. [Overview](#overview)
2. [Technology Stack](#technology-stack)
3. [Project Structure](#project-structure)
4. [Core Domain Models](#core-domain-models)
5. [Portal & Access Control](#portal--access-control)
6. [Modules](#modules)
7. [Authentication & Authorization](#authentication--authorization)
8. [Multi-Tenancy](#multi-tenancy)
9. [Routing](#routing)
10. [Implementation Patterns](#implementation-patterns)
11. [Key Workflows](#key-workflows)
12. [Features & Business Logic](#features--business-logic)
13. [Frontend](#frontend)
14. [Security & Middleware](#security--middleware)
15. [Database](#database)
16. [Testing & Development](#testing--development)

---

## Overview

Lumexa is a multi-tenant SaaS platform providing:

-   Organization and user management
-   Role-based access control (system/admin/customer portals)
-   Billing integration (Laravel Cashier - Stripe)
-   Real-time notifications (Reverb)

**Key Principles:**

-   Follow Laravel conventions
-   Action pattern for single tasks, Service pattern for complex workflows
-   Strict tenant isolation at middleware and query levels
-   Security and privacy first

---

## Terminology

| Term | Meaning |
|------|---------|
| **Tenant / Company** | The client organization - these are the same thing |
| **User** | People who work at the company |
| **System Admin** | You (the platform owner) |
| **Company Admin** | Admin user within a specific company |

---

## Technology Stack

### Backend

-   **Framework:** Laravel 12
-   **Database:** MySQL/PostgreSQL/SQLite
-   **Cache:** Redis
-   **Queue:** Laravel Queue
-   **Authentication:** Sanctum + Laravel built-in
-   **Billing:** Laravel Cashier (Stripe) - Subscriptions, webhooks, invoice management
-   **Testing:** Pest PHP
-   **Code Quality:** PHPStan, Laravel Pint, Rector

### Frontend

-   **UI:** Livewire
-   **Styling:** Tailwind CSS 4
-   **Components:** Livewire 3

### External Services

-   **Stripe** - Payments

---

## Project Structure

```
Lumexa-portal/
├── app/
│   ├── Actions/              # Single-responsibility business logic
│   ├── Services/             # Complex workflow orchestration
│   ├── Http/Controllers/     # HTTP handlers
│   ├── Http/Requests/        # Form validation
│   ├── Http/Resources/       # API transformations
│   ├── Models/               # Eloquent entities
│   ├── Jobs/                 # Queue jobs
│   ├── Events/               # Domain events
│   ├── Listeners/            # Event handlers
│   ├── Middleware/           # Route middleware
│   ├── Livewire/             # Interactive components
│   ├── Notifications/        # Email/SMS templates
│   ├── Observers/            # Model observers
│   ├── Providers/            # Service providers
│   └── Tenancy/              # Multi-tenancy support
├── modules/                  # Feature modules
│   ├── invitation/
│   ├── leadmanage/
│   └── onboarding/
├── config/                   # Configuration
├── database/
│   ├── migrations/           # Schema changes
│   ├── factories/            # Test data
│   └── seeders/              # Initial data
├── routes/
│   ├── web.php               # Public/auth routes
│   ├── api.php               # API endpoints
│   ├── admin.php             # Admin panel
│   ├── customer.php          # Customer portal
│   ├── system.php            # System operations
│   ├── auth.php              # Auth flows
│   └── callback.php          # Webhooks
├── resources/
│   ├── views/                # Blade templates
│   ├── assets/               # CSS/JS
│   ├── lang/                 # Translations
│   └── prompts/              # AI prompts
├── tests/
│   ├── Feature/              # HTTP/integration tests
│   └── Unit/                 # Component tests
└── vendor/                   # Dependencies
```

---

## Core Domain Models

### User

```
- id, uid, company_id
- portal: system|admin|customer
- status: active|inactive|blocked|invited
- plan_id, is_admin, is_customer, is_msp, is_manager

Relations: company(), companies(), account(), plan(),
           breaches(), metas(), monitorEmails()
```

### Company (Tenant)

```
- id, uuid, slug, name, logo
- is_msp, is_public, owner_id, parent_id (for hierarchy)
- config (JSON)

Roles: USER, CLIENT, EDITOR, MANAGER, ADMIN, OWNER

Relations: owner(), parent(), children(), members(), teams(),
           clients(), admins(), products(), locations(),
           services(), monitorEmails()
```

### Plan

```
- id, stripe_price_id, name, features (JSON), settings (JSON)

Relations: users()
```

### Product

```
- id, name, slug, description
- features (JSON), settings (JSON), pricing

Relations: companies(), services()
```

### Invitation

```
- id, company_id, email, token, role
- expires_at, accepted_at

Relations: company()
```

---

## Portal & Access Control

### Three-Tier Portal System

**System Admin Portal**

-   Access: System administrators
-   Routes: `/administrator`, `/system` or configureable
-   Capabilities: System settings, all companies/users, service configs
-   Middleware: `administrator` or `super`

**Admin Portal**

-   Access: Admin, Manager, Owner roles
-   Routes: `/admin`
-   Capabilities: Company management, team/billing, products, reports
-   Middleware: `admin`

**Customer Portal**

-   Access: Customer role users
-   Routes: `/`, `/customer`
-   Capabilities: Dashboard, breach monitoring, alerts, settings
-   Middleware: `customer`

### Authorization Using Spatie Laravel Permission

**Roles:** Super Admin, Admin, Manager, Editor, User/Customer

**Permissions:** manage_users, manage_companies, manage_billing, manage_products, view_reports, manage_leads, access_monitoring, generate_ai_content

**Enforcement:** Gates in `AuthServiceProvider`, Policy-based checks

---

## Modules

### Lead Management

Lead tracking and sales enablement.

-   CSV/Excel import
-   Status tracking (new, contacted, qualified, lost)
-   Assignment and routing
-   Lead formating

### Onboarding

Multi-step org and user setup.

-   Company creation
-   User provisioning
-   Product selection
-   Checkout integration
-   Progress tracking

### Invitation

User invitation and account creation.

-   Secure token generation
-   Email invitations
-   Expiration and revocation
-   Role assignment
-   Account provisioning

---

## Authentication & Authorization

### Auth Methods

1. Email/Password - Traditional login
2. Phone Authentication - SMS via Twilio
3. API Tokens - Sanctum for programmatic access
4. Webhooks - Signature verification (Twilio, API provider)

### Middleware Stack

```
- twilio: Validate Twilio signature
- api: Sanctum + JSON + Throttle
- two-factor: Phone or authenticator app
- admin: Admin role required
- customer: Customer role required
- role: Role-based access
- permission: Permission-based feature access
- tenant: Tenant scoping
```

---

## Multi-Tenancy

**Tenant Model:** Company (organization/MSP)

**Tenant Context:**

```php
TenantContext {
    tenant: Company           // Current tenant
    tenantKeys: array         // Accessible company IDs
    scoped: bool              // Enforcement flag
}
```

**Resolution:**

-   User's primary company_id
-   Multi-company access via pivot table
-   System users bypass tenancy
-   MSP tenants access child companies

**Enforcement:**

-   Query scoping via Model scopes
-   Middleware validation
-   Gateway access checks

---

## Routing

### Route Groups

| File              | Prefix         | Middleware    | Purpose                    |
| ----------------- | -------------- | ------------- | -------------------------- |
| web.php           | /              | web           | Public & authenticated     |
| api.php           | /api           | api           | REST API                   |
| admin.php         | /admin         | admin         | Admin management           |
| customer.php      | / or /customer | customer      | Customer dashboard         |
| system.php        | /system        | administrator | System operations          |
| auth.php          | /auth          | guest\|auth   | Authentication flows       |
| callback.php      | /callback      | -             | Stripe/Twilio/GHL webhooks     |
| administrator.php | /administrator | administrator | System configuration       |

### Key API Endpoints

-   `POST /api/generate-token` - Generate API token
-   `POST /api/auth/phone` - Phone authentication
-   Module-specific endpoints in `modules/*/routes/`

---

## Implementation Patterns

### Actions Pattern

Single-responsibility business logic. Use for: creating entities, state transitions, simple operations.

```php
class CreateUserAction {
    public function __invoke(array $data, Company $company, string $role = 'user'): User {
        // Validate, create user, assign role, trigger events
        return $user;
    }
}

// Usage
$user = app(CreateUserAction::class)($validated, $company, 'manager');
```

### Services Pattern

Complex workflows and API orchestration. Use for: multi-step processes, external API calls, data aggregation.

```php
class InvitationService {
    public function accept(Invitation $invitation, array $userData): User {
        // Validate, create user, assign role, send email
        return $user;
    }

    public function send(Company $company, array $emails, string $role): void {
        // Generate tokens, create invitations, send emails
    }
}

class DarkWebService {
    public function syncBreaches(MonitorEmail $monitor): void {
        // Query SDK, sync records, queue AI generation
    }
}

class OnboardingService {
    public function startOrgSetup(array $orgData): Company { ... }
    public function completeProductSelection(User $user, array $products): void { ... }
}

// Usage
app(InvitationService::class)->accept($invitation, $data);
app(DarkWebService::class)->syncBreaches($monitor);
```

---

## Key Workflows

### 1. Invitation → Account Creation

1. Admin creates invitation
2. Secure token generated, email sent
3. User clicks link, fills registration form
4. `InvitationService` validates token
5. `CreateUserAction` creates user, assigns company role
6. Redirect to portal dashboard

### 2. Onboarding (Org + User)

1. Collect company info → Create Company
2. Collect user info → Create User (admin role)
3. User selects plan → Create Stripe checkout (see [docs/subscription.md](subscription.md))
4. Stripe webhook → Subscription created
5. Redirect to admin dashboard

### 3. Breach Monitoring

1. User adds email to monitor → Create MonitorEmail
2. `MonitorEmailAction` queries Dark Web Scan SDK
3. Sync DarkWebScan records to user
4. Queue AI summary generation job
5. User notified of breaches
6. Scheduled job runs periodically for new breaches

### 5. Third-Party Sync

1. User triggers sync from dashboard
2. Query external SDK/API
3. Transform and normalize data
4. Create/update internal records
5. Cache results
6. Display in UI with sync timestamp

---

## Features & Business Logic

### Lead Management

-   Import from CSV/Excel with field mapping
-   Status tracking: new → contacted → qualified → lost → converted
-   Assignment to team members
-   Lead scoring and prioritization
-   Vendor integration

### Company Management

-   Hierarchical structures (parent/child companies)
-   Member management with role assignment
-   Location management
-   Product and service assignments

### Billing

-   Stripe integration via **Laravel Cashier**
-   See [docs/subscription.md](subscription.md) for full details

---

## Frontend

### Layouts

```
- resources/views/layouts/app.blade.php (main authenticated)
- resources/views/layouts/admin.blade.php (admin portal)
- resources/views/layouts/customer.blade.php (customer portal)
- resources/views/layouts/auth.blade.php (authentication)
```

### View Organization

```
- admin/ - Admin portal views
- customer/ - Customer portal views
- administrator/ - System admin views
- components/ - Reusable Blade components
- auth/ - Auth pages (login, register, 2FA)
```

### Technologies

-   **Livewire 3-4** - Interactive components (tables, forms, charts)
-   **Tailwind CSS 4** - Utility styling
-   **Heroicons/Lucide** - Icons
-   **Chart.js/ApexCharts** - Visualizations

---

## Security & Middleware

### Middleware

-   `AdminMiddleware` - Restrict admin portal to admins
-   `CustomerMiddleware` - Restrict customer portal to customers
-   `AdministratorMiddleware` - Restrict system access to super admins
-   `VerifyTwilioSignature` - Validate Twilio webhooks

### Security Practices

-   Input validation via Form Requests
-   Role/permission checks per portal
-   Signed routes for sensitive flows
-   Webhook signature verification
-   bcrypt password hashing
-   Sanctum token expiration
-   HTTPS enforcement
-   CSRF protection
-   Two-factor authentication support

---

## Database

### Migrations

All schema changes version-controlled.

-   Indexed foreign keys
-   Default values set
-   Reversible (up/down)

### Seeders

-   `RoleSeeder` - System roles
-   `PermissionSeeder` - System permissions
-   `PlanSeeder` - Subscription plans
-   `ProductSeeder` - Product catalog

### Factories

Test data generation:

```php
User::factory()->create();
User::factory(['name' => 'John'])->create();
User::factory(100)->create();
User::factory()->admin()->for(Company::factory())->create();
```

---

## Testing & Development

### Testing Framework

**Pest PHP** with descriptive naming:

```php
it('allows admin to create company', function () { ... })
it('prevents customer from accessing admin panel', function () { ... })
it('sends email on breach detection', function () { ... })
```

### Test Types

-   **Feature Tests** - HTTP endpoints, integration
-   **Unit Tests** - Components in isolation
-   100% PHPStan compliance (strict typing)

### Development Scripts

```bash
composer run setup        # Install, migrate, seed
composer run dev          # Start dev server
composer run format       # Run Rector
composer run lint         # Run PHPStan
composer run test         # Run all tests
```

### Local Development

-   PHP 8.2+, MySQL 8.0/PostgreSQL 14+, Redis, Node.js 18+
-   Configure Stripe, Twilio, OpenAI keys in `.env`
-   Run: `composer run dev` (Laravel dev + Vite + Queue worker + Reverb)

### Git Workflow

-   `main` - Production
-   `develop` - Integration
-   `feature/*` - Features
-   `bugfix/*` - Fixes

**Commit Format:** `feat:`, `fix:`, `docs:`, `style:`, `refactor:`, `test:`, `chore:`

---

## Best Practices

### Code Quality

-   Strict PHP typing throughout
-   PHPDoc comments on public methods
-   Type coverage 100% (PHPStan level 9)
-   Eloquent ORM for all queries
-   Eager loading to prevent N+1

### Performance

-   Query optimization and caching
-   Async processing via queues
-   Database indexes on foreign keys
-   Redis for distributed cache
-   Configuration caching in production

### Security First

-   All input validated
-   Authorization gates on features
-   Encryption at rest for sensitive data
-   Audit logging for sensitive operations
-   Regular security audits

### Deployment

-   Zero-downtime deployments
-   Run migrations before startup
-   Clear cache after deployment
-   Health checks verified
-   Rollback plan ready


**Tech:** Laravel 12, PHP 8.2+, Tailwind, Pest, Stripe
