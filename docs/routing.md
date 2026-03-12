# Routing

This document defines all routes for the three portal types: Administrator, Admin, and Customer.

---

## Table of Contents

1. [Administrator Portal](#administrator-portal)
2. [Admin Portal](#admin-portal)
3. [Customer Portal](#customer-portal)

---

## Administrator Portal

**Prefix:** `/administrator`  
**Middleware:** `administrator` or `super`  
**Purpose:** System-wide administration, configuration, and oversight.

### Dashboard

| Method | Path | Purpose |
|--------|------|---------|
| GET | / | System overview dashboard |

### Companies

| Method | Path | Purpose |
|--------|------|---------|
| GET | /companies | List all companies |
| GET | /companies/create | Show create company form |
| POST | /companies | Create new company |
| GET | /companies/{company} | View company details |
| GET | /companies/{company}/edit | Show edit company form |
| PUT | /companies/{company} | Update company |
| DELETE | /companies/{company} | Delete company |
| POST | /companies/{company}/activate | Activate company |
| POST | /companies/{company}/deactivate | Deactivate company |

### Users

| Method | Path | Purpose |
|--------|------|---------|
| GET | /users | List all users |
| GET | /users/create | Show create user form |
| POST | /users | Create new user |
| GET | /users/{user} | View user details |
| GET | /users/{user}/edit | Show edit user form |
| PUT | /users/{user} | Update user |
| DELETE | /users/{user} | Delete user |
| POST | /users/{user}/block | Block user |
| POST | /users/{user}/unblock | Unblock user |

### Plans

| Method | Path | Purpose |
|--------|------|---------|
| GET | /plans | List all subscription plans |
| GET | /plans/create | Show create plan form |
| POST | /plans | Create new plan |
| GET | /plans/{plan} | View plan details |
| GET | /plans/{plan}/edit | Show edit plan form |
| PUT | /plans/{plan} | Update plan |
| DELETE | /plans/{plan} | Delete plan |

### Products

| Method | Path | Purpose |
|--------|------|---------|
| GET | /products | List all products |
| GET | /products/create | Show create product form |
| POST | /products | Create new product |
| GET | /products/{product} | View product details |
| GET | /products/{product}/edit | Show edit product form |
| PUT | /products/{product} | Update product |
| DELETE | /products/{product} | Delete product |

### Settings

| Method | Path | Purpose |
|--------|------|---------|
| GET | /settings | View system settings |
| PUT | /settings | Update system settings |
| GET | /settings/appearance | Appearance settings |
| PUT | /settings/appearance | Update appearance settings |
| GET | /settings/email | Email configuration |
| PUT | /settings/email | Update email configuration |
| GET | /settings/payment | Payment gateway settings |
| PUT | /settings/payment | Update payment gateway settings |
| GET | /settings/security | Security settings |
| PUT | /settings/security | Update security settings |

### Activity Logs

| Method | Path | Purpose |
|--------|------|---------|
| GET | /logs | View system activity logs |
| GET | /logs/{log} | View log details |

---

## Admin Portal

**Prefix:** `/admin`  
**Middleware:** `admin`  
**Purpose:** Company-level management, team, billing, and product configuration.

### Dashboard

| Method | Path | Purpose |
|--------|------|---------|
| GET | / | Company dashboard overview |

### Company

| Method | Path | Purpose |
|--------|------|---------|
| GET | /company | View company details |
| GET | /company/edit | Show edit company form |
| PUT | /company | Update company information |
| DELETE | /company | Delete company |
| POST | /company/logo | Upload company logo |
| DELETE | /company/logo | Remove company logo |

### Members

| Method | Path | Purpose |
|--------|------|---------|
| GET | /members | List team members |
| GET | /members/create | Show invite member form |
| POST | /members | Invite new member |
| GET | /members/{member} | View member details |
| GET | /members/{member}/edit | Show edit member form |
| PUT | /members/{member} | Update member |
| DELETE | /members/{member} | Remove member from company |
| PUT | /members/{member}/role | Update member role |

### Products

| Method | Path | Purpose |
|--------|------|---------|
| GET | /products | View available products |
| GET | /products/{product} | View product details |
| POST | /products | Assign product to company |
| DELETE | /products/{product} | Remove product from company |

### Billing

| Method | Path | Purpose |
|--------|------|---------|
| GET | /billing | View subscription & billing |
| GET | /billing/checkout | Show checkout page |
| POST | /billing/checkout | Create checkout session |
| GET | /billing/portal | Customer portal link |
| POST | /billing/cancel | Cancel subscription |
| POST | /billing/resume | Resume subscription |
| POST | /billing/swap | Swap subscription plan |

### Invoices

| Method | Path | Purpose |
|--------|------|---------|
| GET | /invoices | List all invoices |
| GET | /invoices/{invoice} | View invoice details |
| GET | /invoices/{invoice}/download | Download invoice PDF |

### Invitations

| Method | Path | Purpose |
|--------|------|---------|
| GET | /invitations | List pending invitations |
| GET | /invitations/create | Show create invitation form |
| POST | /invitations | Send invitation |
| DELETE | /invitations/{invitation} | Revoke invitation |
| POST | /invitations/{invitation}/resend | Resend invitation |

### Reports

| Method | Path | Purpose |
|--------|------|---------|
| GET | /reports | View reports dashboard |
| GET | /reports/usage | Usage statistics |
| GET | /reports/activity | Activity reports |

---

## Customer Portal

**Prefix:** `/` or `/customer`  
**Middleware:** `customer`  
**Purpose:** End-user features including breach monitoring and personal settings.

### Dashboard

| Method | Path | Purpose |
|--------|------|---------|
| GET | / | Customer dashboard |
| GET | /dashboard | Customer dashboard (alternative) |

### Breach Monitoring

| Method | Path | Purpose |
|--------|------|---------|
| GET | /breaches | List all breaches |
| GET | /breaches/{breach} | View breach details |
| GET | /breaches/export | Export breaches data |

### Monitored Emails

| Method | Path | Purpose |
|--------|------|---------|
| GET | /monitored-emails | List monitored emails |
| POST | /monitored-emails | Add email to monitor |
| GET | /monitored-emails/create | Show add email form |
| GET | /monitored-emails/{email} | View monitored email details |
| DELETE | /monitored-emails/{email} | Remove monitored email |
| POST | /monitored-emails/{email}/rescan | Rescan email for breaches |

### Alerts

| Method | Path | Purpose |
|--------|------|---------|
| GET | /alerts | List all alerts |
| GET | /alerts/{alert} | View alert details |
| PUT | /alerts/{alert} | Update alert preferences |
| DELETE | /alerts/{alert} | Dismiss alert |

### Settings

| Method | Path | Purpose |
|--------|------|---------|
| GET | /settings | View account settings |
| PUT | /settings | Update account settings |
| GET | /settings/notifications | Notification preferences |
| PUT | /settings/notifications | Update notification preferences |
| GET | /settings/security | Security settings |
| PUT | /settings/security | Update security settings |
| POST | /settings/password | Change password |

### Profile

| Method | Path | Purpose |
|--------|------|---------|
| GET | /profile | View user profile |
| PUT | /profile | Update profile |
| PUT | /profile/avatar | Update avatar |
| DELETE | /profile/avatar | Remove avatar |

---

## API Endpoints

| Method | Path | Purpose |
|--------|------|---------|
| POST | /api/generate-token | Generate API token |
| POST | /api/auth/phone | Phone authentication |

---

## Module Routes

Module-specific routes should be defined in `modules/*/routes/`.

### Lead Management Module

| Method | Path | Purpose |
|--------|------|---------|
| GET | /leads | List leads |
| POST | /leads | Create lead |
| GET | /leads/import | Show import form |
| POST | /leads/import | Import leads from CSV/Excel |
| GET | /leads/{lead} | View lead details |
| PUT | /leads/{lead} | Update lead |
| DELETE | /leads/{lead} | Delete lead |
| PUT | /leads/{lead}/status | Update lead status |
| POST | /leads/{lead}/assign | Assign lead to member |

### Onboarding Module

| Method | Path | Purpose |
|--------|------|---------|
| GET | /onboarding | Start onboarding |
| GET | /onboarding/company | Company setup step |
| POST | /onboarding/company | Submit company info |
| GET | /onboarding/user | User setup step |
| POST | /onboarding/user | Submit user info |
| GET | /onboarding/products | Product selection step |
| POST | /onboarding/products | Submit product selection |
| GET | /onboarding/checkout | Checkout step |
| POST | /onboarding/checkout | Complete checkout |
| GET | /onboarding/complete | Onboarding complete |

---

## Route Summary

| File | Prefix | Middleware | Routes |
|------|--------|------------|--------|
| administrator.php | /administrator | administrator | ~40 |
| admin.php | /admin | admin | ~30 |
| customer.php | / | customer | ~25 |
| api.php | /api | api | 2+ |
| Module routes | varies | varies | varies |

---

**Related:** See [docs/architecture.md](architecture.md) for middleware stack and portal details.
