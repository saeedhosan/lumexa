# Undeniable Project Roadmap

A step-by-step guide to make Lumexa an undeniable portfolio project
for remote senior software engineer roles in 2026–2027.

> **Goal:** Go from "good Laravel project" to "hire this person on sight"
> by proving production-readiness, architectural depth, and engineering maturity.

---

## Phase 1 — Fix the Hard Blockers

**Timeline:** Week 1
**Theme:** *"This code ships without embarrassment"*

These are things a senior engineer will catch in the first 5 minutes of reading
your code. Fixing them is the difference between "pass" and "fail" in code review.

### 1.1 Fix SQLite-only `strftime()` query

**Issue:** #14

**File:** `app/Livewire/Dashboard.php:157`

**Problem:**
```php
->selectRaw("strftime('%Y-%m-%d', created_at) as date, count(*) as count")
```
`strftime()` is a SQLite function. This crashes on MySQL/PostgreSQL.

**Fix:**
```php
use Illuminate\Support\Facades\DB;

->selectRaw(DB::getDriverName() === 'sqlite'
    ? "strftime('%Y-%m-%d', created_at) as date"
    : "DATE(created_at) as date"
)
```

Or extract into a database-agnostic helper.

**Why it matters:**
An interviewer running this on MySQL will get a 500 error immediately.
It signals "only ever tested on SQLite" — the opposite of production-ready.

---

### 1.2 Add `is_active` column to users table

**Issue:** #13

**File:** `app/Livewire/Admin/Dashboard.php:61`

**Problem:**
```php
$this->activeUsers = User::query()->where('is_active', true)->count();
```
The query references a column that doesn't exist in the users migration.

**Fix:**
```bash
php artisan make:migration add_is_active_to_users_table
```

```php
Schema::table('users', function (Blueprint $table): void {
    $table->boolean('is_active')->default(true)->after('email');
});
```

Then add `'is_active' => 'boolean'` to the User model's `casts()` method
and add `'is_active'` to the `$fillable` array.

**Why it matters:**
Broken admin dashboard pages are the first thing a hiring manager clicks on.

---

### 1.3 Fix or remove Campaign module

**Issue:** #15

**Files:** `routes/app.php:19`, `app/Http/Controllers/App/CampaignController.php`

**Problem:**
Routes reference CampaignController, but there is no Campaign model,
migration, factory, seeder, or policy. Visiting any campaign route
crashes or returns incomplete data.

**Option A — Remove:**
Delete the campaign route and controller.

**Option B — Build:**
```bash
php artisan make:model Campaign -mf
php artisan make:controller App\\CampaignController --resource
php artisan make:policy CampaignPolicy
php artisan make:factory CampaignFactory
```
Implement the full module.

**Why it matters:**
Dead routes signal incomplete work. Either commit fully or remove.

---

### 1.4 Implement empty controller methods

**Issue:** #17

**File:** `app/Http/Controllers/App/LeadController.php`

**Problem:**
```php
public function store(Request $request): void
{
    //
}

public function update(Request $request, Lead $lead): void
{
    //
}

public function destroy(Lead $lead): void
{
    //
}
```

Empty stubs + plain `Request` instead of Form Request.

**Fix:**
Create `app/Http/Requests/App/StoreLeadRequest.php` and
`app/Http/Requests/App/UpdateLeadRequest.php`, then implement the methods.

```php
public function store(StoreLeadRequest $request): RedirectResponse
{
    $lead = Lead::query()->create([
        ...$request->validated(),
        'user_id'    => auth()->id(),
        'company_id' => currentTenant()->tenantKey(),
    ]);

    event(new LeadCreated($lead));

    return to_route('app.leads.index')
        ->with('toast', 'Lead created successfully.');
}
```

**Why it matters:**
An empty method in a resource controller is the first thing a reviewer sees.
It screams "unfinished."

---

### 1.5 Implement or remove CompanyObserver

**Issue:** #16

**File:** `app/Observers/CompanyObserver.php`

**Problem:**
```php
class CompanyObserver
{
    //
}
```

The observer is registered via `#[ObservedBy(CompanyObserver::class)]`
but contains no logic.

**Fix — Add meaningful logic:**
```php
use App\Models\Company;
use Illuminate\Support\Str;

class CompanyObserver
{
    public function creating(Company $company): void
    {
        if (empty($company->slug)) {
            $company->slug = Str::slug($company->name ?? 'company-'.Str::random(6));
        }
    }

    public function created(Company $company): void
    {
        activity()
            ->performedOn($company)
            ->withProperties(['name' => $company->name])
            ->log('Company created');
    }
}
```

Or remove the `#[ObservedBy]` attribute and the file.

---

## Phase 2 — The Undeniable Features

**Timeline:** Weeks 2-3
**Theme:** *"This candidate builds things that matter"*

### 2.1 Live demo on a real URL

**Non-negotiable.** Without this, README screenshots could be faked.

**Options ranked:**
| Platform | Cost | Effort | Best for |
|----------|------|--------|----------|
| Laravel Cloud | Free tier | Low | Laravel-native, zero config |
| Fly.io | ~$5/mo | Medium | Containerized, global |
| DigitalOcean App Platform | ~$5/mo | Medium | Simple PHP hosting |
| VPS (DigitalOcean droplet) | ~$6/mo | High | Full control, Reverb support |

**Recommended:** Laravel Cloud — free for demo, deploys from GitHub in 2 clicks.

**What to add to README:**
```markdown
## Live Demo

👉 **[app.lumexa.dev](https://app.lumexa.dev)**

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `super@example.com` | `demo1234` |
| Admin | `admin@example.com` | `demo1234` |
| User | `user@example.com` | `demo1234` |
```

**Badge:**
```markdown
[![Live Demo](https://img.shields.io/badge/demo-online-brightgreen)](https://app.lumexa.dev)
```

**Why it matters:**
A live demo lets recruiters click around without cloning and configuring.
Every extra step between "saw the repo" and "saw the app" loses candidates.

---

### 2.2 Subdomain-based multi-tenancy

Your current tenancy scopes by `company_id`. Taking it to subdomain
resolution proves deep architecture understanding.

**How it works:**
```
acme.lumexa.test   → company_id = Acme  
vertex.lumexa.test → company_id = Vertex  
app.lumexa.test    → fallback (user selects company)
```

**Implementation sketch:**

**a) Update `CompanyTenantResolver`:**
```php
public function resolveRouteTenant(Request $request): ?Model
{
    // Try subdomain first
    $host = $request->getHost();
    $subdomain = explode('.', $host)[0] ?? null;

    if ($subdomain && $subdomain !== 'app' && $subdomain !== 'www') {
        $company = Company::query()->where('slug', $subdomain)->first();
        if ($company) {
            return $company;
        }
    }

    // Fall back to route parameter
    $companyId = $request->route('company_id');
    return $companyId
        ? Company::query()->find($companyId)
        : null;
}
```

**b) Update `CustomerMiddleware`:**
Redirect to `$subdomain.$domain` if user has a current company.

**c) Test with Laravel Valet or Herd:**
```bash
valet link lumexa
valet domain test
# Now acme.lumexa.test, vertex.lumexa.test work locally
```

**Why it matters:**
Subdomain multi-tenancy is a common interview topic. Building it yourself
proves you understand DNS, middleware, and tenancy at an architectural level.
Most candidates only know theory — you'd have shipped it.

---

### 2.3 Stripe billing integration

The `Plan` model already has:
- `stripe_price_id_monthly`
- `stripe_price_id_yearly`
- `price_monthly`
- `price_yearly`
- `trial_days`
- `features`

They're all unused. Fill them in.

**Implementation:**

**a) Install Stripe:**
```bash
composer require stripe/stripe-php
```

**b) Create billing service:**
```php
namespace App\Actions\Billing;

use App\Models\Company;
use App\Models\Plan;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CreateCheckoutSession
{
    public function handle(Company $company, Plan $plan, string $interval = 'monthly'): Session
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $priceId = $interval === 'monthly'
            ? $plan->stripe_price_id_monthly
            : $plan->stripe_price_id_yearly;

        return Session::create([
            'mode' => 'subscription',
            'customer' => $company->stripe_customer_id,
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'success_url' => route('settings.billing').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('settings.billing'),
        ]);
    }
}
```

**c) Add Stripe webhook handling:**
```php
public function handleWebhook(Request $request): Response
{
    $event = Webhook::constructEvent(
        $request->getContent(),
        $request->header('Stripe-Signature'),
        config('services.stripe.webhook_secret')
    );

    match ($event->type) {
        'customer.subscription.updated' => $this->handleSubscriptionUpdated($event),
        'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event),
        'invoice.payment_succeeded' => $this->handleInvoicePaid($event),
        default => null,
    };

    return response('OK', 200);
}
```

**d) Add migration for Stripe columns:**
```php
Schema::table('companies', function (Blueprint $table): void {
    $table->string('stripe_customer_id')->nullable()->after('settings');
    $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
    $table->string('stripe_subscription_status')->nullable()->after('stripe_subscription_id');
});
```

**Why it matters:**
Billing is the hardest part of any SaaS. Building it proves you understand
webhooks, idempotency, asynchronous payment flows, and PCI compliance
boundaries. Every SaaS company needs this skill.

---

### 2.4 Complete REST API

**Current state:** 2 endpoints (`GET /api/v1/leads`, `GET /api/v1/leads/{id}`).

**Target state:** Full CRUD for all resources.

**Implementation:**

```php
// routes/api.php
Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('leads', LeadController::class);
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('services', ServiceController::class)->only(['index', 'show']);
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::apiResource('logs', LogController::class)->only(['index']);
});
```

**Add API token abilities:**
Use Sanctum's token abilities to scope access:

```php
// Token creation
$token = $user->createToken('api-token', ['leads:read', 'leads:write']);

// Middleware
Route::middleware('ability:leads:read')->get('/leads', ...);
```

**Add API documentation:**
```bash
composer require dedoc/scramble
php artisan scramble:generate
```

Scramble generates OpenAPI docs from your code annotations automatically.

**Add pagination metadata:**
The `LeadCollection` already exists. Ensure it includes:
```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 72
    },
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": "..."
    }
}
```

**Why it matters:**
A full API proves you can design RESTful contracts, handle authentication,
implement rate limiting, versioning, and pagination. These are daily
requirements at any company with mobile apps or third-party integrations.

---

### 2.5 Real-time notifications with broadcasting

Echo + Pusher are already in `package.json`. Channels are registered.
Nothing uses them. This is free differentiation.

**Implementation:**

**a) Create broadcast event:**
```bash
php artisan make:event LeadStatusChanged
```

```php
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeadStatusChanged implements ShouldBroadcast
{
    public function __construct(public Lead $lead) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('company.'.$this->lead->company_id)];
    }
}
```

**b) Listen on frontend:**
```javascript
window.Echo.private(`company.${companyId}`)
    .listen('LeadStatusChanged', (e) => {
        // Update table row without refresh
        Alpine.store('leads').updateRow(e.lead);
    });
```

**c) Test with Reverb locally:**
```bash
php artisan reverb:start
```

**Why it matters:**
Real-time features impress in demos and prove full-stack capability.
Most portfolio projects are static — yours would feel alive.

---

## Phase 3 — Polish That Gets You Hired

**Timeline:** Week 4
**Theme:** *"This candidate is an engineer, not just a coder"*

### 3.1 Production-hardened README

Replace the current README with a version that screams production-readiness.

**Structure:**
```markdown
# Lumexa

[![Tests](https://github.com/saeedhosan/lumexa/actions/workflows/tests.yml/badge.svg)](...)
[![Lint](https://github.com/saeedhosan/lumexa/actions/workflows/lint.yml/badge.svg)](...)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%209-brightgreen)]()
[![Live Demo](https://img.shields.io/badge/demo-online-brightgreen)](https://app.lumexa.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)]()

Multi-tenant SaaS platform — companies, users, leads, services, plans,
billing, and audit logging in one workspace.

## Live Demo

👉 **[app.lumexa.dev](https://app.lumexa.dev)**

...

## Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13 + PHP 8.4 |
| Frontend | Livewire 4 + Flux UI + Tailwind CSS v4 |
| Database | MySQL / PostgreSQL / SQLite |
| Cache | Redis |
| Queue | Redis + Horizon |
| Broadcasting | Laravel Reverb |
| Billing | Stripe |
| API | Sanctum + OpenAPI (Scramble) |
| CI | GitHub Actions (tests + lint) |
| Deploy | Docker Compose / Laravel Cloud |

## Architecture

[See architecture documentation](docs/architecture.md)

## Key Features

- **Multi-tenancy** — row-level scoping with subdomain resolution
- **Authentication** — Fortify with 2FA, email verification, passkeys
- **Role-based access** — Super Admin / Admin / User with policies
- **Billing** — Stripe subscriptions per plan
- **API** — Versioned REST API with Sanctum tokens
- **Real-time** — WebSocket broadcasts via Reverb
- **Audit** — Spatie activity logging + custom database logger
- **Dashboards** — Cached analytics with ApexCharts
- **Onboarding** — Multi-step Livewire wizard
- **Imports** — Excel lead list import

## Quick Start

```bash
composer setup
```

## Testing

```bash
php artisan test --compact
# 205 tests, 406 assertions — all passing
```
```

---

### 3.2 Architecture Decision Records (ADRs)

Create decision documents that prove you think architecturally.

**`docs/adr/001-multi-tenancy-strategy.md`:**
```markdown
# ADR-001: Multi-tenancy Strategy

## Status
Accepted

## Context
Lumexa is a multi-tenant SaaS where each company's data must be isolated.
Two approaches were considered:
1. Separate database per tenant
2. Row-level scoping with a `company_id` column

## Decision
Row-level scoping with `company_id` on every tenant-scoped table.

## Rationale
- Single database simplifies migrations, backups, and operations
- Cross-tenant reporting is straightforward for Super Admins
- Subdomain routing provides logical separation without DB overhead
- Row-level scoping is enforced at the query level via middleware
- Separate DB can be introduced later if a tenant exceeds scale thresholds

## Consequences
- All tenant-scoped queries must include `WHERE company_id = ?`
- The `HasTenant` trait + `TenantMiddleware` ensure this is automatic
- A bug in the scoping middleware could leak data between tenants
- Mitigation: policy authorization as a second layer of defense
```

**`docs/adr/002-dashboard-caching-strategy.md`:**
```markdown
# ADR-002: Dashboard Caching Strategy

## Status
Accepted

## Context
Dashboard pages aggregate data across leads, users, and activities.
Without caching, every page load runs 6+ queries including aggregates.

## Decision
Redis-backed caching with 5-minute TTL for statistics and chart data.
10-minute TTL for chart data (changes less frequently).

## Rationale
- Dashboard data doesn't need real-time accuracy
- 5-minute TTL balances freshness with performance
- Redis provides sub-millisecond reads
- Cache keys are tenant-scoped (`app:dashboard:{company_id}:statistics`)
- A "Clear Cache" button exists for manual refresh
- Cache is invalidated when new data is created via observers

## Consequences
- Dashboard may show stale data for up to 5 minutes
- Cache must be warmed after deployment
- Memory usage grows linearly with number of tenants
```

**`docs/adr/003-api-versioning-strategy.md`:**
```markdown
# ADR-003: API Versioning Strategy

## Status
Accepted

## Context
The public API must support multiple clients without breaking changes.

## Decision
URL prefix versioning (`/api/v1/...`).

## Rationale
- Simplest to implement and test
- Explicit in logs and monitoring
- No header negotiation complexity
- Clients are locked to a specific version URL
- Allows parallel operation of v1 and v2 during migration

## Consequences
- URL changes require client updates (acceptable for this domain)
- Version is part of the route file (`routes/api.php`)
- Deprecated versions should be clearly communicated in responses
```

**`docs/adr/004-authentication-architecture.md`:**
```markdown
# ADR-004: Authentication Architecture

## Status
Accepted

## Context
The application needs authentication that supports web (Livewire) and API
(Sanctum) clients, with 2FA, email verification, and passwordless options.

## Decision
Fortify for web authentication + Sanctum for API tokens.

## Rationale
- Fortify provides all auth views, routes, and actions out of the box
- Sanctum uses the same guard for SPA and token-based auth
- 2FA, email verification, and password reset are built-in
- Custom `LoginResponse` and `RegisterResponse` handle onboarding redirect
- Rate limiting is configured per-feature (login: 5/min, 2FA: 5/min)

## Consequences
- Sanctum limits apply to token-based API access
- 2FA setup requires password confirmation middleware
- Web auth and API auth share the same user table
```

**Why ADRs matter:**
ADRs are the single most undervalued portfolio asset. They prove you
can **think and communicate like a senior engineer** — not just code.
Most candidates have none. Having 3-4 is a massive differentiator.

---

### 3.3 PHPStan at max level

```bash
composer require --dev phpstan/phpstan
composer require --dev phpstan/phpstan-laravel
```

**`phpstan.neon`:**
```neon
includes:
    - vendor/phpstan/phpstan-laravel/extension.neon

parameters:
    level: max
    paths:
        - app/
    excludePaths:
        - app/Providers/*.php
    checkMissingIterableValueType: true
    checkGenericClassInNonGenericObjectType: false
    treatPhpDocTypesAsCertain: false
```

**Add to CI:**
```yaml
- name: PHPStan
  run: vendor/bin/phpstan analyse --no-progress
```

**Add badge:**
```markdown
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen)]()
```

**Why it matters:**
Type safety is a big deal at companies with large codebases (Shopify, Stripe,
GitHub). PHPStan at max level catches bugs that tests miss and enforces
discipline. It's also rare in portfolio projects — easy differentiation.

---

### 3.4 Load testing in CI

Add a K6 script and run it in CI to show you care about performance:

**`tests/Load/leads-api.js`:**
```javascript
import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    stages: [
        { duration: '30s', target: 20 },
        { duration: '1m', target: 20 },
        { duration: '30s', target: 0 },
    ],
};

export default function () {
    const res = http.get('https://app.lumexa.dev/api/v1/leads', {
        headers: { Authorization: 'Bearer TEST_TOKEN' },
    });

    check(res, {
        'status is 200': (r) => r.status === 200,
        'response time < 200ms': (r) => r.timings.duration < 200,
    });

    sleep(1);
}
```

**Why it matters:**
Performance engineering is a senior skill. Showing you can measure and
optimize proves you build for scale, not just correctness.

---

### 3.5 Application health endpoint

Add a health check that validates all services:

```php
Route::get('/health', function (): JsonResponse {
    $checks = [
        'database' => rescue(fn () => DB::select('SELECT 1'), false),
        'cache'    => rescue(fn () => Cache::store('redis')->set('health', true), false),
        'queue'    => rescue(fn () => Queue::size() !== false, false),
    ];

    $status = collect($checks)->every(fn ($v) => $v !== false)
        ? Response::HTTP_OK
        : Response::HTTP_SERVICE_UNAVAILABLE;

    return response()->json([
        'status'    => $status === 200 ? 'healthy' : 'degraded',
        'checks'    => $checks,
        'timestamp' => now()->toIso8601String(),
    ], $status);
});
```

**Why it matters:**
Health endpoints are standard in production deployments. Shows ops awareness.

---

## Phase 4 — The Interview Edge

**Theme:** *"Prove it in the room"*

### 4.1 Prepare your story

When asked "Tell me about a project," your answer:

> "I built a multi-tenant SaaS platform called Lumexa. It manages companies,
> users, leads, and billing in a single workspace. The hardest part was
> the multi-tenancy — I chose row-level scoping over separate databases
> because it simplifies operations while still enforcing data isolation
> at the query level via middleware. I implemented Stripe billing with
> webhook handling, real-time notifications via WebSockets, and a full
> REST API with Sanctum authentication. The test suite runs 205 tests
> in CI with PHPStan at max level. It's live at app.lumexa.dev."

This takes 30 seconds, uses precise technical language, and demonstrates
decision-making. Practice it.

### 4.2 Link everything in your resume

```
Projects
────────
Lumexa — Multi-tenant SaaS Platform
github.com/saeedhosan/lumexa | app.lumexa.dev
- Multi-tenant architecture with subdomain resolution
- Stripe billing subscriptions with webhook handling
- Real-time WebSocket broadcasts via Laravel Reverb
- REST API with Sanctum tokens + OpenAPI documentation
- 205 passing tests, PHPStan max level, CI/CD pipelines
- Livewire 4 + Flux UI + Tailwind CSS v4 frontend
```

### 4.3 Repository checklist before applying

- [ ] README has live demo link with credentials
- [ ] README has status badges (tests, lint, PHPStan, demo)
- [ ] All GitHub issues closed or clearly triaged
- [ ] No empty methods, no dead code, no commented-out code
- [ ] `.env.example` is complete and accurate
- [ ] `docker-compose.yml` works with a single `docker compose up`
- [ ] All 205+ tests pass on a fresh clone
- [ ] PHPStan level max passes
- [ ] `docs/adr/` has at least 3 decision records
- [ ] `docs/architecture.md` has a system diagram
- [ ] API documentation is published (Scramble)
- [ ] Health endpoint returns valid JSON

---

## Summary

| Phase | Focus | Time | Impact |
|-------|-------|------|--------|
| 1 | Fix blockers (broken queries, empty stubs, dead code) | Week 1 | **Critical** — without this, nothing else matters |
| 2 | Build undeniable features (live demo, subdomain tenancy, Stripe, API, real-time) | Weeks 2-3 | **High** — proves production readiness |
| 3 | Polish (README, ADRs, PHPStan, health endpoint, badges) | Week 4 | **Medium-High** — signals senior engineering maturity |
| 4 | Interview prep (story, resume link, checklist) | Ongoing | **High** — turns code into job offer |

The goal isn't perfection. The goal is to remove every excuse a recruiter
could have to say "not impressed" and replace it with "we need to talk."

---

## GitHub Issues Reference

All tasks are tracked as GitHub issues on
[saeedhosan/lumexa](https://github.com/saeedhosan/lumexa/issues).

### Phase 1 — Fix Blockers
| # | Issue | Label |
|---|-------|-------|
| #13 | User model queries non-existent `is_active` column | bug |
| #14 | Dashboard chart uses SQLite-only `strftime()` | bug |
| #15 | Campaign module referenced in routes but model does not exist | bug |
| #16 | `CompanyObserver` is empty | improvement |
| #17 | `App\LeadController` has empty store/update/destroy | improvement |
| #18 | Add custom exception handling | improvement |
| #19 | Broadcasting configured but not implemented | improvement |
| #20 | Inconsistent action class resolution | improvement |

### Phase 2 — Undeniable Features
| # | Issue | Label |
|---|-------|-------|
| #21 | Live demo deployment on a real URL | feature |
| #22 | Subdomain-based multi-tenancy resolution | feature |
| #23 | Stripe billing integration with subscription plans | feature |
| #24 | Complete REST API with full resource CRUD | feature |
| #30 | Real-time notifications with Laravel Reverb broadcasting | feature |

### Phase 3 — Polish
| # | Issue | Label |
|---|-------|-------|
| #25 | Production-hardened README with badges | docs |
| #26 | Architecture Decision Records (ADRs) | docs |
| #27 | Add PHPStan static analysis at max level | improvement |
| #28 | Load testing with K6 in CI pipeline | improvement |
| #29 | Application health endpoint | improvement |

### Phase 4 — Interview Edge
| # | Issue | Label |
|---|-------|-------|
| #31 | Repository pre-apply checklist and interview preparation | docs |

---

*Last updated: June 2026*
