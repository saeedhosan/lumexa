# Subscription Planning

## Overview

Lumexa is a multi-tenant SaaS platform where companies subscribe to plans that grant access to specific products. This document explains how the subscription system works.

## Terminology

| Term | Meaning |
|------|---------|
| **Tenant / Company** | The client organization (e.g., "Acme Corp") - these are the same thing |
| **User** | People who work at the company |
| **System Admin** | You (the platform owner) - creates plans and products |
| **Company Admin** | Admin user within a specific company |

## How It Works

### 1. Products (What You Offer)

Products are the SaaS features or services your platform provides:

| Product | Code | Goal |
|---------|------|------|
| Breach Monitoring | `breach_monitoring` | Monitor emails for data breaches |
| Lead Management | `lead_management` | Track and manage sales leads |

Each product can be enabled/disabled and has its own settings.

### 2. Plans (Subscription Tiers)

Plans group products together into pricing tiers:

| Plan | Products Included | User Limit |
|------|-------------------|-------------|
| **Starter** | Breach Monitoring | 1 user |
| **Professional** | Breach Monitoring + Lead Management | 5 users |
| **Enterprise** | All products | Unlimited users |

### 3. Company (Tenant)

A company represents an organization using the platform. When a company signs up, they:
1. Choose a plan that fits their needs
2. Get automatic access to all products in that plan
3. Can upgrade or downgrade anytime

### 4. Users (Team Members)

Users belong to a company and automatically inherit access to products based on their company's plan. No per-user licensing needed.

## Flow

```
System Admin (YOU)
       │
       ▼ Creates Plans → Assigns Products to Plans
       
Company/Tenant
       │
       ▼ Subscribes to a Plan → Pays via Stripe
       
Users
       │
       ▼ Get access to Plan's Products automatically
```

## Data Structure

| Table | Purpose |
|-------|---------|
| `products` | Available features/services (name, code, settings) |
| `plans` | Subscription tiers (name, limits, Stripe price IDs) |
| `plan_products` | Links plans to products (which products are in which plan) |
| `companies` | Has `plan_id`, `plan_started_at`, `plan_ends_at` |

### Relationships

```
Plan (1) ──────< (many) PlanProduct >────── (1) Product
  │
  └── Company (belongs_to Plan)
        │
        └── Users (inherit access through company)
```

### How Access Control Works

1. User logs in → System loads their company
2. Company has a plan → Plan defines accessible products
3. Feature check → UI shows upgrade prompt if product not included

```php
// Check if company can access a product
$company->plan->products->contains('code', 'lead_management');
```

## Key Benefits

- **Simple** - Only 2 new database tables needed
- **Scalable** - Easy to add new plans or products
- **Flexible** - Companies can upgrade/downgrade anytime
- **Real Payments** - Stripe integration for real billing
- **Clean Access Control** - Users inherit access through their company

## Future Enhancements (Optional)

When needed, you can add:
- Usage-based billing (per email monitored, per lead, etc.)
- Custom plans per company
- Trial periods with `trial_days` on plans
- Invoice management
- Per-company product overrides (bypass plan)
