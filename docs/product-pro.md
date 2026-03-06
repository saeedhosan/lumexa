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
Plan (1) ──────< (many) PlanProduct >────── (1) Product
```

- Plan has many Products (`$plan->products()`)
- Product belongs to many Plans (`$product->plans()`)

## Access Control

```php
// Check if company can access a product
$company->plan->products->contains('code', 'lead_management');
```

## Blade Directive

```blade
@product('lead_management', $company)
    <a href="/leads">View Leads</a>
@endproduct
```

---

**Related:** See [docs/subscription.md](subscription.md) for Plans and billing.
