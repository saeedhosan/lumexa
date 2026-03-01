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

Products are the features or services your platform provides. Examples:
- Breach Monitoring
- Lead Management
- Dark Web Scanning
- AI Content Generation

Each product can be enabled/disabled and has its own settings.

### 2. Plans (Subscription Tiers)

Plans group products together into pricing tiers. Example:

| Plan | Products Included | Typical Use |
|------|-------------------|-------------|
| **Startup** | Basic Monitoring, 1 User | Small teams |
| **Standard** | Full Monitoring, Lead Management, 5 Users | Growing businesses |
| **Business** | All Products, Unlimited Users | Enterprises |

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
| `plans` | Stores plan info (name, price, description) |
| `plan_products` | Links plans to products (which products are in which plan) |
| `companies` | Has `plan_id` to track current subscription |
| `products` | Available features/services |

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
- Trial periods
- Invoice management
