# ADR-003: Why Custom Tenancy Instead of Stancl's Tenancy

**Status:** Accepted  
**Date:** 2026-06-24

## Context

Laravel has a mature multi-tenancy package — `stancl/tenancy` — with database-per-tenant, domain resolution, and queue isolation. Despite this, Lumexa uses a custom tenancy package.

## Decision

Build a **custom `saeedhosan/laravel-tenancy` package** that implements company_id-based scoping with:
- A `TenantContext` singleton
- A `TenantResolver` contract with customizable resolution strategies
- A global Eloquent scope for automatic query filtering
- Middleware for per-request tenant resolution

## Consequences

- **Positive:** The custom package is ~300 lines — easy to understand, test, and modify. No dependency on a third-party package that may break on Laravel upgrades. The contract-based design makes it simple to swap strategies (subdomain → header → session).
- **Negative:** No built-in support for database-per-tenant, domain resolution, or queue isolation. If Lumexa grows to thousands of tenants, the scoping approach will need re-architecture.
- **Trade-off:** Stancl's package is powerful but opinionated and complex. For a row-level scoping model, a custom package is simpler and more maintainable.

## Alternatives Considered

- **Stancl's Tenancy:** Best for database-per-tenant and domain-based resolution. Over-engineered for company_id scoping.
- **`spatie/laravel-multitenancy`:** Well-architected but designed for a different model (incoming request → tenant).
