# ADR-001: Multi-Tenancy via company_id Scoping

**Status:** Accepted  
**Date:** 2026-06-24

## Context

Lumexa serves multiple organizations (companies) from a single application instance. Each company's data must be isolated from all others. Users can belong to multiple companies and switch between them.

## Decision

Use **row-level scoping** via a `company_id` foreign key on all tenant-scoped tables (leads, invites). A custom middleware resolves the current tenant from:

1. The request subdomain (mapped to a company's `slug`)
2. The user's `current_company_id` column

A global Eloquent scope automatically filters all queries by `company_id`. Users who don't belong to the resolved company receive a 403.

## Consequences

- **Positive:** Simple, fast, no separate database per tenant. The tenancy package is custom and lightweight (~300 lines).
- **Negative:** Schema changes require migrations on a shared table. A single database is a scaling bottleneck for thousands of tenants.
- **Trade-off:** Database-per-tenant would offer stronger isolation but adds operational complexity. Row-level scoping is appropriate for a B2B SaaS targeting SMBs.

## Alternatives Considered

- **Database-per-tenant:** Stronger isolation, harder to manage migrations and backups across N databases.
- **Schema-per-tenant:** `SET search_path` approach common on PostgreSQL. Not portable to MySQL/SQLite.
