# Product Architecture - Lumexa

**Version:** 1.0  
**Last Updated:** 2026-03-04  
**Target:** Simple, Scalable, Maintainable

---

## 1. Overview

This document outlines the product and subscription system for Lumexa. The architecture focuses on simplicity while remaining scalable for future growth.

### Core Principles (SOLO DEV)

- **KISS** - Keep It Simple, Stupid
- **YAGNI** - You Aren't Gonna Need It
- **DRY** - Don't Repeat Yourself
- Use existing Laravel patterns (Actions, Services)
- Feature flags over code branches

---

## 2. Terminology

| Term | Meaning |
|------|---------|
| **Product** | A SaaS feature/module (e.g., Breach Monitoring, Lead Management) |
| **Plan** | Subscription tier (Basic, Pro, Enterprise) |
| **Company** | Tenant/organization that subscribes to a plan |
| **User** | Team member who inherits access through their company |

---

## 3. Products List

Each product is a standalone module that can be enabled/disabled.

### Current Products

| Product | Code | Goal | Status |
|---------|------|------|--------|
| Breach Monitoring | `breach_monitoring` | Monitor emails for data breaches | MVP |
| Lead Management | `lead_management` | Track and manage sales leads | MVP |

### Future Products (Extensible)

| Product | Code | Goal |
|---------|------|------|
| Dark Web Scan | `darkweb_scan` | Scan dark web for company data |
| AI Insights | `ai_insights` | AI-powered security recommendations |
| API Access | `api_access` | Programmatic API access |

### Product Data Structure

```
Product {
  id, uuid
  name: string          // Display name
  slug: string          // URL-friendly
  code: string          // Unique identifier (e.g., 'lead_management')
  icon: string          // Icon class/name
  logo: string         // Product logo URL
  about: string        // Description
  is_active: boolean   // Available for assignment
  is_default: boolean  // Auto-assigned to new companies
  version: string      // Semantic version
  provider: string     // External provider (optional)
  features: json       // Feature toggles (optional)
  settings: json       // Product-specific config
}
```

---

## 4. Plans (3-Tier Subscription)

Simple three-tier structure. Each plan bundles specific products.

### Plan Structure

| Plan | Products Included | Target |
|------|-------------------|--------|
| **Starter** | Breach Monitoring | Small teams, 1 user |
| **Professional** | Breach Monitoring + Lead Management | Growing businesses, 5 users |
| **Enterprise** | All products + API access | Large orgs, unlimited users |

### Plan Data Model

```php
Plan {
  id
  name: string           // Display name (Starter/Professional/Enterprise)
  slug: string          // URL-friendly (starter/professional/enterprise)
  description: string   // Short description
  is_active: boolean    // Available for subscription
  is_default: boolean  // Default for new companies
  trial_days: integer  // Trial period (0 = no trial)
  
  // Limits
  max_users: integer    // 0 = unlimited
  
  // Metadata
  features: json        // Plan-level feature flags
  settings: json       // Plan-specific settings
  
  timestamps
}
```

### Plan-Product Relationship

```
plans
  │
  └── plan_products (pivot)
        │
        └── products
```

**Migration Example:**

```php
// plans table
Schema::create('plans', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->boolean('is_default')->default(false);
    $table->integer('trial_days')->default(14);
    
    $table->integer('max_users')->default(5);
    $table->json('features')->nullable();
    $table->json('settings')->nullable();
    
    $table->timestamps();
});

// plan_products pivot
Schema::create('plan_products', function (Blueprint $table) {
    $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->primary(['plan_id', 'product_id']);
});

// Add plan_id to companies (Cashier adds stripe columns automatically)
Schema::table('companies', function (Blueprint $table) {
    $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
});

// Run Cashier migrations separately:
// php artisan vendor:publish --tag="cashier-migrations"
// php artisan migrate
```

> **Note:** Laravel Cashier automatically adds `stripe_customer_id`, `stripe_subscription_id`, `stripe_price_id`, `stripe_current_period_end`, and other billing columns to the `companies` table when you run its migrations.

---

## 5. How to Create Products (Admin)

### Admin Routes

```
/admin/products          # List all products
/admin/products/create   # Create new product
/admin/products/{id}     # View product
/admin/products/{id}/edit # Edit product
/admin/products/{id}/toggle # Enable/disable
```

### Product Creation Flow

```
1. Admin visits /admin/products/create
2. Fills form:
   - Name (required)
   - Code (required, unique, e.g., 'lead_management')
   - Icon (optional)
   - Description (optional)
   - Features JSON (optional)
   - Settings JSON (optional)
3. Submits form
4. Product created with is_active = true
5. Redirects to product list with success message
```

### Product CRUD Actions

```php
// app/Actions/Product/CreateProductAction.php
class CreateProductAction
{
    public function handle(array $data): Product
    {
        $data['code'] = Str::slug($data['name']);
        $data['is_active'] = true;
        
        return Product::create($data);
    }
}

// app/Actions/Product/UpdateProductAction.php
class UpdateProductAction
{
    public function handle(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }
}

// app/Actions/Product/ToggleProduct.php
class ToggleProductAction
{
    public function handle(Product $product): Product
    {
        $product->is_active = !$product->is_active;
        $product->save();
        return $product;
    }
}
```

---

## 6. Access Control & Feature Gating

### Laravel Cashier Integration

The Company model uses Laravel Cashier's `Billable` trait for Stripe subscriptions:

```php
use Laravel\Cashier\Billable;

class Company extends Model
{
    use Billable;
    
    // ... relationships
}
```

#### Creating a Subscription

```php
// In a controller or service
$company->newSubscription('default', 'price_pro ->trial_monthly')
   Days(14)
    ->checkout();
```

#### Checking Subscription Status

```php
// Check if subscribed
$company->subscribed('default');

// Get active subscription
$subscription = $company->subscription('default');
$subscription->active();

// Check specific price
$company->subscribedToPrice('price_pro_monthly');
```

#### Webhook Route

```php
// routes/callback.php
Route::post('/stripe/webhook', \Laravel\Cashier\Http\Controllers\WebhookController::class);
```

Enable these events in Stripe Dashboard:
- `customer.subscription.created`
- `customer.subscription.updated`
- `customer.subscription.deleted`

### How It Works

1. **User logs in** → System loads their company
2. **Company has plan** → Plan defines accessible products
3. **Feature check** → UI shows upgrade prompt if not included

### Implementation

#### A. Check if Product is Accessible

```php
// app/Services/ProductService.php
class ProductService
{
    public function isAccessible(Company $company, string $productCode): bool
    {
        if (!$company->plan) {
            return false;
        }
        
        return $company->plan->products()
            ->where('code', $productCode)
            ->where('is_active', true)
            ->exists();
    }
    
    public function getAccessibleProducts(Company $company): Collection
    {
        if (!$company->plan) {
            return collect();
        }
        
        return $company->plan->products()
            ->where('is_active', true)
            ->get();
    }
    
    public function getUpgradePrompt(Company $company): ?array
    {
        if ($company->plan) {
            return null;
        }
        
        return [
            'title' => 'Upgrade to Access Features',
            'message' => 'Subscribe to a plan to access premium features.',
            'cta' => 'View Plans',
            'url' => '/admin/plans',
        ];
    }
}
```

#### B. Blade Directive for Feature Gating

```php
// app/Providers/AppServiceProvider.php
Blade::directive('product', function ($expression) {
    $parts = explode(',', $expression);
    $code = trim($parts[0], " '\"");
    $plan = isset($parts[1]) ? trim($parts[1]) : 'company';
    
    return "<?php if (app(\\App\\Services\\ProductService::class)->isAccessible({$plan}, '{$code}')): ?>";
});

Blade::directive('endproduct', function () {
    return "<?php endif; ?>";
});
```

#### C. Usage in Templates

```blade
{{-- Show feature to users who have access --}}
@product('breach_monitoring', $user->currentCompany)
    <div class="breach-monitoring-card">
        <h3>Breach Monitoring</h3>
        <a href="/breaches">View Breaches</a>
    </div>
@endproduct

{{-- Show upgrade prompt to users without access --}}
@unless(product_accessible('lead_management', $user->currentCompany))
    <div class="upgrade-prompt">
        <h3>Lead Management</h3>
        <p>Upgrade to Professional plan to access this feature.</p>
        <a href="/admin/plans" class="btn-primary">Upgrade Now</a>
    </div>
@endunless
```

#### D. Middleware for Route Protection

```php
// app/Http/Middleware/RequireProduct.php
class RequireProduct
{
    public function handle(Request $request, Closure $next, string $productCode)
    {
        $company = $request->user()?->currentCompany;
        
        if (!$company) {
            return redirect('/login');
        }
        
        $productService = app(ProductService::class);
        
        if (!$productService->isAccessible($company, $productCode)) {
            return redirect('/upgrade-required')
                ->with('product_code', $productCode);
        }
        
        return $next($request);
    }
}
```

---

## 7. User Flow Diagrams

### New Company Signup

```
┌─────────────────┐
│ User Registers │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Create Company │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Select Plan    │◄──── Choose Starter/Professional/Enterprise
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Stripe Checkout │ (or free trial)
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Access Granted │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Dashboard with  │
│ Plan Products  │
└─────────────────┘
```

### Feature Access Check

```
User visits /leads
        │
        ▼
Check: Company has plan?
        │
   ┌────┴────┐
   │         │
  YES        NO
   │         │
   ▼         ▼
Check: Plan  Redirect to
includes    /upgrade
'leads'?
   │
┌──┴──┐
│     │
 YES   NO
 │     │
 ▼     ▼
Show  Show
Page  Upgrade
      Prompt
```

---

## 8. Database Schema (Summary)

### Tables

| Table | Purpose |
|-------|---------|
| `products` | Available products/features |
| `plans` | Subscription tiers |
| `plan_products` | Which products in which plan |
| `companies` | Has `plan_id`, `plan_ends_at` |
| `company_product` | Direct product assignment (optional override) |

### Relationships

```
Plan (1) ──────< (many) PlanProduct >────── (1) Product
                │
                └── also accessible via---
                              │
Company (1) ───< (many) <─────┘
```

---

## 9. Implementation Checklist

### Phase 1: Core (MVP)

- [ ] Create Plan model + migration
- [ ] Create PlanProduct pivot model
- [ ] Add `plan_id` to Company
- [ ] Seed default plans (Starter, Professional, Enterprise)
- [ ] Seed default products (Breach Monitoring, Lead Management)
- [ ] Assign products to plans
- [ ] ProductService: `isAccessible()` method
- [ ] Blade directive `@product`

### Phase 2: Admin UI

- [ ] Admin routes for plans CRUD
- [ ] Admin routes for products CRUD
- [ ] List view: plans with product counts
- [ ] List view: products with status
- [ ] Create/Edit forms

### Phase 3: User Experience

- [ ] Dashboard: show accessible products only
- [ ] Upgrade prompt component
- [ ] Middleware: route protection

### Phase 4: Payments (Future)

- [ ] Stripe integration
- [ ] Checkout flow
- [ ] Webhook handling
- [ ] Plan change (upgrade/downgrade)

---

## 10. File Structure

```
app/
├── Models/
│   ├── Plan.php           # NEW
│   ├── Product.php        # EXISTS
│   └── Company.php        # EXISTS (+ plan_id)
├── Actions/
│   ├── Plan/              # NEW
│   │   ├── CreatePlan.php
│   │   ├── UpdatePlan.php
│   │   └── AssignProductsToPlan.php
│   └── Product/           # EXISTS
│       ├── CreateProduct.php
│       └── UpdateProduct.php
├── Services/
│   └── ProductService.php # NEW
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── PlanController.php    # NEW
│   │   │   └── ProductController.php # NEW
│   └── Middleware/
│       └── RequireProduct.php        # NEW
└── Http/Requests/
    ├── CreatePlanRequest.php         # NEW
    └── UpdatePlanRequest.php         # NEW
```

---

## 11. Key Decisions

| Decision | Rationale |
|----------|-----------|
| **JSON features/settings** | Extensible without migrations |
| **Plan → Products (not Company → Products)** | Simpler - users inherit from plan |
| **Blade directive** | Clean template syntax, readable |
| **Service class** | Reusable, testable |
| **Actions pattern** | Single responsibility, follows Laravel conventions |

---

## 12. Extension Points

### Adding New Products

1. Create product in admin UI or seeder
2. Assign to relevant plans
3. (Optional) Add feature checks in code

### Adding New Plans

1. Create plan in admin UI or seeder
2. Assign products to plan
3. Set Stripe price IDs (when payments added)

### Per-Company Customization

If you need custom product sets per company later:

```
Company (1) ──────< (many) CompanyProduct >────── (1) Product
                       │  (overrides plan)
                       └── is_active
```

---

## 13. Quick Reference

### Check Product Access

```php
// In controller, service, or Blade
app(ProductService::class)->isAccessible($company, 'lead_management');
```

### Get User's Products

```php
$user->currentCompany->plan->products;
```

### Check Plan Tier

```php
$company->plan->slug; // 'starter', 'professional', 'enterprise'
```

---

**End of Architecture Document**
