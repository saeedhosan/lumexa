# Interview Preparation

## "Tell Me About a Project" (30-Second Story)

> "I built a multi-tenant SaaS platform called Lumexa. It manages companies, users, leads, and billing in a single workspace. The hardest part was the multi-tenancy — I chose row-level scoping over separate databases because it simplifies operations while still enforcing data isolation at the query level via middleware. I implemented Stripe billing with webhook handling, real-time notifications via WebSockets, and a full REST API with Sanctum authentication. The test suite runs 195 tests in CI with PHPStan at level max. It is live at [lumexa.saeedhosan.com](https://lumexa.saeedhosan.com)."

## Resume Bullet Points

```
Lumexa — Multi-tenant SaaS Platform
github.com/saeedhosan/lumexa | lumexa.saeedhosan.com

- Multi-tenant architecture with subdomain resolution and row-level data isolation
- Stripe billing subscriptions with webhook handling for subscription lifecycle
- Real-time WebSocket broadcasts via Laravel Reverb for live UI updates
- REST API with Sanctum tokens and auto-generated OpenAPI documentation
- 195+ passing tests, PHPStan max level, CI/CD pipelines for quality enforcement
- Livewire 4 + Flux UI + Tailwind CSS v4 frontend with reactive server-side components
```

## Pre-Apply Checklist

- [x] README has live demo link with credentials
- [x] README has status badges (tests, lint, PHPStan, demo)
- [x] All GitHub issues closed or clearly triaged
- [x] No empty methods, dead code, or commented-out code
- [x] `.env.example` is complete and accurate
- [x] `docker-compose.yml` works with a single `docker compose up`
- [x] All 195+ tests pass on a fresh clone
- [x] PHPStan level max passes
- [x] `docs/adr/` has at least 3 decision records
- [x] `docs/architecture.md` has a system diagram
- [x] API documentation is published at `/docs/api` (Scramble)
- [x] Health endpoint returns valid JSON at `GET /health`
