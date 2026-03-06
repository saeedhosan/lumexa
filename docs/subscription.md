# Subscriptions

Plans and billing with Laravel Cashier (Stripe).

> For Products (features), see [docs/product-pro.md](product-pro.md)

---

## Plans

| Plan | Products | Users | Monthly | Yearly |
|------|----------|-------|---------|--------|
| Starter | Breach Monitoring | 1 | $9 | $90 |
| Professional | Breach Monitoring + Lead Management | 5 | $29 | $290 |
| Enterprise | All products | Unlimited | $99 | $990 |

## Plan Data Model

```php
Plan {
  id
  name: string           // Starter, Professional, Enterprise
  slug: string          // starter, professional, enterprise
  description: string   // Short description
  is_active: boolean    // Available for subscription
  is_default: boolean   // Default for new companies
  trial_days: integer   // Trial period
  max_users: integer   // 0 = unlimited
  
  // Display prices
  price_monthly: decimal(10,2)
  price_yearly: decimal(10,2)
  currency: string
  
  // Stripe Price IDs
  stripe_price_id_monthly: string|null
  stripe_price_id_yearly: string|null
  
  features: json
  settings: json
  timestamps
}
```

## Migration

```bash
php artisan migrate
```

Creates:
- `plans` table with price columns
- `plan_products` pivot table
- `plan_id` column on `companies` table
- Cashier columns on `companies` table

---

## Laravel Cashier Setup

### 1. Install

```bash
composer require laravel/cashier
```

### 2. Add Billable to Company

```php
use Laravel\Cashier\Billable;

class Company extends Model
{
    use Billable;
}
```

### 3. Run Migrations

```bash
php artisan vendor:publish --tag="cashier-migrations"
php artisan migrate
```

---

## Creating Subscriptions

```php
// Get price based on billing cycle
$priceId = $request->billing_cycle === 'yearly' 
    ? $plan->stripe_price_id_yearly 
    : $plan->stripe_price_id_monthly;

// Create subscription
$company->newSubscription('default', $priceId)
    ->trialDays($plan->trial_days)
    ->checkout();
```

---

## Checking Status

```php
// Has active subscription?
$company->subscribed('default');

// Get subscription
$company->subscription('default')->active();

// Cancel
$company->subscription('default')->cancel();

// Swap plan
$company->subscription('default')->swap('price_enterprise_monthly');
```

---

## Webhooks

```php
// routes/callback.php
Route::post('/stripe/webhook', 
    \Laravel\Cashier\Http\Controllers\WebhookController::class
);
```

Enable in Stripe Dashboard:
- `customer.subscription.created`
- `customer.subscription.updated`
- `customer.subscription.deleted`
- `invoice.payment_succeeded`
- `invoice.payment_failed`

---

## Quick Reference

```php
// Display price
${{ $plan->price_monthly }} / month

// Checkout
$company->newSubscription('default', $plan->stripe_price_id_monthly)->checkout();

// Check subscription
$company->subscribed('default');
```
