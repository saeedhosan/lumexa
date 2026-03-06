# Subscriptions

This document covers Plans and billing with Laravel Cashier (Stripe).

> For Products (features), see [docs/product-pro.md](product-pro.md)

---

## Plans

Plans bundle products into subscription tiers.

| Plan | Products | Users | Monthly Price |
|------|----------|-------|---------------|
| Starter | Breach Monitoring | 1 | $9 |
| Professional | Breach Monitoring + Lead Management | 5 | $29 |
| Enterprise | All products | Unlimited | $99 |

### Plan Data Model

```php
Plan {
  id
  name: string           // Starter, Professional, Enterprise
  slug: string          // starter, professional, enterprise
  description: string   // Short description
  is_active: boolean    // Available for subscription
  is_default: boolean   // Default for new companies
  trial_days: integer   // Trial period (0 = no trial)
  max_users: integer   // 0 = unlimited
  
  // Display prices (for customers)
  price_monthly: decimal(10,2)   // 29.00
  price_yearly: decimal(10,2)   // 290.00
  currency: string              // USD
  
  // Stripe Price IDs (for checkout)
  stripe_price_id_monthly: string|null
  stripe_price_id_yearly: string|null
  
  features: json       // Feature flags
  settings: json      // Settings
  
  timestamps
}
```

### Migration

```php
Schema::create('plans', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->boolean('is_default')->default(false);
    $table->integer('trial_days')->default(14);
    $table->integer('max_users')->default(5);
    
    // Display prices
    $table->decimal('price_monthly', 10, 2)->nullable();
    $table->decimal('price_yearly', 10, 2)->nullable();
    $table->string('currency')->default('USD');
    
    // Stripe Price IDs
    $table->string('stripe_price_id_monthly')->nullable();
    $table->string('stripe_price_id_yearly')->nullable();
    
    $table->json('features')->nullable();
    $table->json('settings')->nullable();
    $table->timestamps();
});
```

### Displaying Prices

```blade
<!-- In pricing page -->
${{ $plan->price_monthly }} / month
${{ $plan->price_yearly }} / year

<!-- With currency -->
{{ $plan->currency }} {{ number_format($plan->price_monthly, 2) }} / month
```

### Plan-Product Relationship

```
Plan (1) ──────< (many) PlanProduct >────── (1) Product
```

A Plan has many Products through the pivot table.

---

## Laravel Cashier Setup

### 1. Install Cashier

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

Cashier adds these columns to `companies`:
- `stripe_customer_id`
- `stripe_subscription_id`
- `stripe_price_id`
- `stripe_current_period_end`

---

## Creating Subscriptions

### Basic Checkout

```php
$company->newSubscription('default', 'price_pro_monthly')
    ->checkout();
```

### With Trial

```php
$company->newSubscription('default', $plan->stripe_price_id_monthly)
    ->trialDays($plan->trial_days)
    ->checkout();
```

### Checkout with Plan

Use Price ID from Plan to create subscription:

```php
// Get the price based on billing cycle
$priceId = $request->billing_cycle === 'yearly' 
    ? $plan->stripe_price_id_yearly 
    : $plan->stripe_price_id_monthly;

$company->newSubscription('default', $priceId)
    ->trialDays($plan->trial_days)
    ->checkout();
```

---

## Checking Subscription Status

```php
// Has active subscription?
$company->subscribed('default');

// Get subscription
$subscription = $company->subscription('default');

// Is active?
$subscription->active();

// Cancel
$company->subscription('default')->cancel();

// Swap to different price
$company->subscription('default')->swap('price_enterprise_monthly');
```

---

## Webhooks

Add the webhook route to handle Stripe events:

```php
// routes/callback.php
Route::post('/stripe/webhook', 
    \Laravel\Cashier\Http\Controllers\WebhookController::class
);
```

Enable these events in Stripe Dashboard:
- `customer.subscription.created`
- `customer.subscription.updated`
- `customer.subscription.deleted`
- `invoice.payment_succeeded`
- `invoice.payment_failed`

---

## User Flow

```
1. User signs up → Create Company
2. User selects Plan → Choose Starter/Pro/Enterprise
3. Redirect to Stripe Checkout → Pay
4. Stripe webhook → Subscription created in database
5. User accesses Products → Based on Plan
```

---

## Quick Reference

```php
// Create subscription
$company->newSubscription('default', $priceId)->checkout();

// Check if subscribed
$company->subscribed('default');

// Get subscription
$company->subscription('default')->active();

// Cancel
$company->subscription('default')->cancel();
```

---

**End of Subscription Documentation**
