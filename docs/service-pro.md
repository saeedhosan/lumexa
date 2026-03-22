# Services

Services are the features your SaaS provides (e.g., Breach Monitoring, Lead Management).

## Service List

| Service | Code | Description |
|---------|------|-------------|
| Breach Monitoring | `breach_monitoring` | Monitor emails for data breaches |
| Lead Management | `lead_management` | Track and manage sales leads |

## Data Model

```php
Service {
  id, uuid
  name: string          // Display name
  slug: string          // URL-friendly
  code: string          // Unique identifier (e.g., 'lead_management')
  icon: string          // Icon
  about: string        // Description
  is_active: boolean   // Available for use
  is_default: boolean  // Auto-assign to new companies
  features: json       // Feature toggles
  settings: json       // Service-specific config
}
```

## Relationships

```
Plan (1) ──────< (many) PlanService >────── (1) Service
```

- Plan has many Services (`$plan->services()`)
- Service belongs to many Plans (`$service->plans()`)

## Access Control

```php
// Check if company can access a Service
$company->plan->services->contains('code', 'lead_management');
```

## Blade Directive

```blade
@service('lead_management', $company)
    <a href="/leads">View Leads</a>
@endservice
```

---

**Related:** See [docs/subscription.md](subscription.md) for Plans and billing.
