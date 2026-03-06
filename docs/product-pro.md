# Products

Products are the features your SaaS provides (e.g., Breach Monitoring, Lead Management).

## Product List

| Product | Code | Description |
|---------|------|-------------|
| Breach Monitoring | `breach_monitoring` | Monitor emails for data breaches |
| Lead Management | `lead_management` | Track and manage sales leads |

## Data Model

```php
Product {
  id, uuid
  name: string          // Display name
  slug: string          // URL-friendly
  code: string          // Unique identifier (e.g., 'lead_management')
  icon: string          // Icon
  about: string        // Description
  is_active: boolean   // Available for use
  is_default: boolean  // Auto-assign to new companies
  features: json       // Feature toggles
  settings: json       // Product-specific config
}
```

## Relationships

```
Plan ──────< PlanProduct >────── Product
```

- Plan has many Products (via `plan_products` pivot)
- Product belongs to many Plans

## Access Control

Check if a company can access a product:

```php
// Simple check
$company->plan->products->contains('code', 'lead_management');

// Or use ProductService
app(ProductService::class)->isAccessible($company, 'lead_management');
```

## Blade Directive

```blade
@product('lead_management', $company)
    <a href="/leads">View Leads</a>
@endproduct
```

---

**Related:** See [docs/subscription.md](subscription.md) for Plans and billing.
