# Subscription Planning

## Overview

Lumexa is a multi-tenant SaaS platform where companies subscribe to plans that grant access to specific products. This document explains how the subscription system works using **Laravel Cashier** (Stripe).

## Terminology

| Term | Meaning |
|------|---------|
| **Tenant / Company** | The client organization (e.g., "Acme Corp") - these are the same thing |
| **User** | People who work at the company |
| **System Admin** | You (the platform owner) - creates plans and products |
| **Company Admin** | Admin user within a specific company |
| **Billable** | The Company model with Laravel Cashier's Billable trait |

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

| Plan | Products Included | User Limit | Stripe Price ID |
|------|-------------------|------------|-----------------|
| **Starter** | Breach Monitoring | 1 user | `price_starter_monthly` |
| **Professional** | Breach Monitoring + Lead Management | 5 users | `price_pro_monthly` |
| **Enterprise** | All products | Unlimited users | `price_enterprise_monthly` |

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
       ▼ Subscribes to a Plan → Pays via Stripe (Cashier)
       
Users
       │
       ▼ Get access to Plan's Products automatically
```

## Laravel Cashier Integration

### Company Model (Billable)

The Company model uses Laravel Cashier's `Billable` trait:

```php
use Laravel\Cashier\Billable;

class Company extends Model
{
    use Billable;
    
    // ... other relationships
}
```

### Creating a Subscription

```php
use Illuminate\Http\Request;

Route::post('/subscribe', function (Request $request) {
    $company = $request->user()->currentCompany;
    
    $company->newSubscription('default', 'price_pro_monthly')
        ->checkout();
});
```

### Checking Subscription Status

```php
// Check if company has an active subscription
$company->subscribed('default');

// Get the subscription
$subscription = $company->subscription('default');

// Check if active
$subscription->active();

// Check for specific plan
$company->subscribedToPrice('price_pro_monthly');
```

### Webhook Handling

Cashier handles subscription events automatically. Add the webhook route:

```php
// routes/callback.php
Route::post('/stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');
```

Make sure to enable these webhooks in your Stripe dashboard:
- `customer.subscription.created`
- `customer.subscription.updated`
- `customer.subscription.deleted`
- `invoice.payment_succeeded`
- `invoice.payment_failed`

## Data Structure

| Table | Purpose |
|-------|---------|
| `products` | Available features/services (name, code, settings) |
| `plans` | Subscription tiers (name, limits) |
| `plan_products` | Links plans to products (which products are in which plan) |
| `companies` | Has `plan_id`, billable columns (`stripe_customer_id`, etc.) |
| `subscriptions` | Cashier table - tracks subscription status |
| `subscription_items` | Cashier table - tracks subscription items |

### Relationships

```
Plan (1) ──────< (many) PlanProduct >────── (1) Product
  │
  └── Company (belongs_to Plan)
        │
        └── Subscription (hasMany via Cashier)
              │
              └── Users (inherit access through company)
```

### How Access Control Works

1. User logs in → System loads their company
2. Company has a subscription via Cashier → Plan defines accessible products
3. Feature check → UI shows upgrade prompt if product not included

```php
// Check if company can access a product
$company->plan->products->contains('code', 'lead_management');

// Or check subscription status
$company->subscribed('default');
```

## Key Benefits

- **Simple** - Only 2 new database tables needed
- **Scalable** - Easy to add new plans or products
- **Flexible** - Companies can upgrade/downgrade anytime
- **Real Payments** - Stripe integration via Laravel Cashier
- **Clean Access Control** - Users inherit access through their company
- **Automated Webhooks** - Cashier handles subscription lifecycle

## Future Enhancements (Optional)

When needed, you can add:
- Usage-based billing (per email monitored, per lead, etc.)
- Custom plans per company
- Trial periods with `trial_days` on plans
- Invoice management
- Per-company product overrides (bypass plan)
