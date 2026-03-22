# UI Layouts

This document describes the UI layout components available in Lumexa, built with Flux UI and Livewire.

---

## Auth Layout

**Component:** `layouts::auth` (wraps `layouts::auth.simple`)  
**Supports:** Blade templates, Livewire, Flux UI  
**Usage:** `<x-layouts::auth :title="__('Login')"> {{ $slot }} </x-layouts::auth>`

| Prop | Type | Description |
|------|------|-------------|
| title | string | Page title (optional) |

**Variants:**

- `layouts::auth` - The default
- `layouts::auth.simple` - Centered content, minimal design
- `layouts::auth.card` - Card-wrapped content
- `layouts::auth.split` - Split-screen layout (e.g., image + form)

---

## App Layout

**Component:** `layouts::app` (wraps `layouts::app.sidebar`)  
**Supports:** Blade templates, Livewire, Flux UI navigation  
**Usage:** `<x-layouts::app :title="__('Dashboard')"> {{ $slot }} </x-layouts::app>`

| Prop | Type | Description |
|------|------|-------------|
| title | string | Page title (optional) |

**Sections:**
- [Sidebar](#sidebar) - Left navigation panel
- [Header/Topbar](#header--topbar) - Top navigation bar
- Main content area - Uses `<flux:main>` wrapper

---

## Admin Layout

**Component:** `layouts::admin` (to be created - wraps `layouts::app.sidebar`)  
**Route prefix:** `/admin`  
**Middleware:** `admin`  
**Usage:** `<x-layouts::app :title="__('Admin Dashboard')"> {{ $slot }} </x-layouts::app>`

| Prop | Type | Description |
|------|------|-------------|
| title | string | Page title (optional) |

### Menu Items (from `routes/admin.php`)

| Route Name | URL | Description |
|------------|-----|-------------|
| admin.home | /admin | Dashboard |
| admin.dashboard | /admin/dashboard | Dashboard |
| admin.company.index | /admin/company | Companies |
| admin.memebers.index | /admin/memebers | Members |
| admin.products.index | /admin/products | Products |
| admin.billing.index | /admin/billing | Billing |
| admin.invoices.index | /admin/invoices | Invoices |
| admin.invites.index | /admin/invites | Invitations |
| admin.reports.index | /admin/reports | Reports |

### Sidebar Structure

```blade
<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Overview')">
        <flux:sidebar.item icon="home" :href="route('admin.home')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading="__('Management')">
        <flux:sidebar.item icon="building-office" :href="route('admin.company.index')" wire:navigate>
            {{ __('Companies') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="users" :href="route('admin.memebers.index')" wire:navigate>
            {{ __('Members') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="cube" :href="route('admin.products.index')" wire:navigate>
            {{ __('Products') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading="__('Finance')">
        <flux:sidebar.item icon="credit-card" :href="route('admin.billing.index')" wire:navigate>
            {{ __('Billing') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="document-text" :href="route('admin.invoices.index')" wire:navigate>
            {{ __('Invoices') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading="__('Settings')">
        <flux:sidebar.item icon="envelope" :href="route('admin.invites.index')" wire:navigate>
            {{ __('Invitations') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="chart-bar" :href="route('admin.reports.index')" wire:navigate>
            {{ __('Reports') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>
```

---

## Super Layout

**Component:** `layouts::app` (to be created - wraps `layouts::app.sidebar`)  
**Route prefix:** `/super` or `/super`  
**Middleware:** `super` or `administrator`  
**Usage:** `<x-layouts::apptitle="__('System Dashboard')"> {{ $slot }} </x-layouts:x-layouts::app

| Prop | Type | Description |
|------|------|-------------|
| title | string | Page title (optional) |

### Menu Items (from `routes/super.php`)

| Route Name | URL | Description |
|------------|-----|-------------|
| super.home | /super | Dashboard |
| super.dashobard | /super/dashobard | Dashboard |
| super.logs.index | /super/logs | System Logs |
| super.users.index | /super/users | Users |
| super.plans.index | /super/plans | Plans |
| super.companies.index | /super/companies | Companies |
| super.products.index | /super/products | Products |
| super.settings.index | /super/settings | Settings |

### Sidebar Structure

```blade
<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Overview')">
        <flux:sidebar.item icon="home" :href="route('super.home')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading="__('System')">
        <flux:sidebar.item icon="users" :href="route('super.users.index')" wire:navigate>
            {{ __('Users') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="building-office" :href="route('super.companies.index')" wire:navigate>
            {{ __('Companies') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="cube" :href="route('super.products.index')" wire:navigate>
            {{ __('Products') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading="__('Billing')">
        <flux:sidebar.item icon="currency-dollar" :href="route('super.plans.index')" wire:navigate>
            {{ __('Plans') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading=__("__('Maintenance')">
        <flux:sidebar.item icon="document-text" :href="route('super.logs.index')" wire:navigate>
            {{ __('Logs') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="cog" :href="route('super.settings.index')" wire:navigate>
            {{ __('Settings') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>
```

---

## Customer Layout

**Component:** `layouts::customer` (to be created - wraps `layouts::app.sidebar`)  
**Route prefix:** `/` or `/customer`  
**Middleware:** `customer`  
**Usage:** `<x-layouts::app :title="__('My Dashboard')"> {{ $slot }} </x-layouts::app>`

| Prop | Type | Description |
|------|------|-------------|
| title | string | Page title (optional) |

### Menu Items (from `routes/app.php`)

| Route Name | URL | Description |
|------------|-----|-------------|
| app.leads.index | /leads | Leads |
| app.breaches.index | /breaches | Breaches |
| app.products.index | /products | Products |
| app.companies.index | /companies | Companies |
| app.activities.index | /activities | Activities |

### Sidebar Structure

```blade
<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Core')">
        <flux:sidebar.item icon="megaphone" :href="route('app.leads.index')" wire:navigate>
            {{ __('Leads') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="shield-exclamation" :href="route('app.breaches.index')" wire:navigate>
            {{ __('Breaches') }}
        </flux:sidebar.item>
    </flux:sidebar.group>

    <flux:sidebar.group :heading="__('Management')">
        <flux:sidebar.item icon="cube" :href="route('app.products.index')" wire:navigate>
            {{ __('Products') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="building-office" :href="route('app.companies.index')" wire:navigate>
            {{ __('Companies') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="clock" :href="route('app.activities.index')" wire:navigate>
            {{ __('Activities') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>
```

---

## Header / Topbar

Located in: `resources/views/layouts/app/header.blade.php`

**Flux UI Components:**
- `flux:header` - Main header container
- `flux:navbar` - Navigation items container
- `flux:navbar.item` - Individual navigation link
- `flux:sidebar.toggle` - Mobile menu toggle button
- `flux:tooltip` - Tooltip for icons
- `flux:spacer` - Flexible spacing

### Left Section

```blade
<flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />
<x-app-logo href="{{ route('dashboard') }}" wire:navigate />
<flux:navbar class="-mb-px max-lg:hidden">
    <flux:navbar.item icon="layout-grid" :href="route('dashboard')" wire:navigate>
        {{ __('Dashboard') }}
    </flux:navbar.item>
</flux:navbar>
```

### Right Section

```blade
<flux:navbar class="me-1.5 space-x-0.5">
    <flux:tooltip :content="__('Search')" position="bottom">
        <flux:navbar.item icon="magnifying-glass" href="#" :label="__('Search')" />
    </flux:tooltip>
</flux:navbar>
<x-desktop-user-menu />
```

---

## Sidebar

Located in: `resources/views/layouts/app/sidebar.blade.php`

**Flux UI Components:**
- `flux:sidebar` - Sidebar container
- `flux:sidebar.header` - Header section with logo
- `flux:sidebar.collapse` - Collapse toggle button
- `flux:sidebar.nav` - Navigation container
- `flux:sidebar.group` - Grouped navigation with heading
- `flux:sidebar.item` - Individual menu item
- `flux:spacer` - Flexible spacing

### Structure

```blade
<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200">
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Platform')" class="grid">
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="folder-git-2" href="https://github.com/..." target="_blank">
            {{ __('Repository') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

    <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
</flux:sidebar>
```

### Responsive Behavior

| Screen Size | Sidebar | Profile Dropdown |
|-------------|---------|------------------|
| Desktop (`lg`+) | Always visible | In sidebar (line 33) |
| Mobile (`<lg`) | Collapsible via toggle | In header (lines 38-90) |

**Sidebar:**
- `sticky` - Keeps sidebar fixed while scrolling
- `collapsible="mobile"` - Collapses to hamburger menu on mobile
- Use `<flux:sidebar.toggle icon="bars-2" />` in header to open/close on mobile

**Profile Dropdown:**
- Desktop: `<x-desktop-user-menu class="hidden lg:block" />` in sidebar
- Mobile: Same dropdown component inside `<flux:header class="lg:hidden">`

---

## Profile Dropdown

Located in: `resources/views/components/desktop-user-menu.blade.php`

**Note:** Profile dropdown is shown on both mobile and desktop, but implemented differently:
- **Desktop:** `<x-desktop-user-menu class="hidden lg:block" />` inside sidebar
- **Mobile:** Same dropdown component inside `<flux:header class="lg:hidden">`

**Flux UI Components:**
- `flux:dropdown` - Dropdown container
- `flux:profile` - Profile button with avatar/initials
- `flux:menu` - Menu container
- `flux:menu.radio.group` - Radio button group
- `flux:menu.item` - Individual menu item
- `flux:menu.separator` - Visual separator
- `flux:avatar` - User avatar component

### Structure

```blade
<flux:dropdown position="top" align="end">
    <flux:profile
        :initials="auth()->user()->initials()"
        icon-trailing="chevron-down"
    />

    <flux:menu>
        <flux:menu.radio.group>
            <div class="p-0 text-sm font-normal">
                <div class="flex items-center gap-2 px-1 py-1.5 text-start">
                    <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                    <div class="grid flex-1 text-start text-sm leading-tight">
                        <flux:heading>{{ auth()->user()->name }}</flux:heading>
                        <flux:text>{{ auth()->user()->email }}</flux:text>
                    </div>
                </div>
            </div>
        </flux:menu.radio.group>

        <flux:menu.separator />

        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
            {{ __('Settings') }}
        </flux:menu.item>

        <flux:menu.separator />

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                {{ __('Log Out') }}
            </flux:menu.item>
        </form>
    </flux:menu>
</flux:dropdown>
```

---

## Company Switcher

Allows users to switch between companies they belong to. Shows current active company and a list of other accessible companies.

**Location:** `resources/views/components/company-switcher.blade.php` (to be created)

**Usage:** Place in sidebar header area, below logo

**Flux UI Components:**
- `flux:dropdown` - Dropdown container
- `flux:sidebar.profile` - Profile button with company name
- `flux:menu` - Company list container
- `flux:menu.item` - Individual company option
- `flux:avatar` - Company logo/initial avatar
- `flux:icon` - Check mark for active company

### Structure

```blade
<flux:dropdown position="bottom" align="start">
    <flux:sidebar.profile
        :name="$currentCompany?->name ?? 'Select Company'"
        icon:trailing="chevrons-up-down"
    />

    <flux:menu class="w-72">
        <div class="px-2 py-1.5 text-xs font-medium text-zinc-500">
            {{ __('Switch Company') }}
        </div>

        <flux:menu.separator />

        @forelse($companies as $company)
            <form method="POST" action="{{ route('company.switch', $company) }}">
                @csrf
                <flux:menu.item as="button" type="submit" class="w-full">
                    <div class="flex items-center gap-3">
                        <flux:avatar :name="$company->name" />
                        <div class="grid flex-1 text-start">
                            <flux:heading level="5" size="sm">{{ $company->name }}</flux:heading>
                            <flux:text size="xs">{{ $company->pivot->role ?? 'Member' }}</flux:text>
                        </div>
                        @if($company->id === $currentCompany?->id)
                            <flux:icon name="check" class="size-4" />
                        @endif
                    </div>
                </flux:menu.item>
            </form>
        @empty
            <flux:menu.item disabled>{{ __('No companies available') }}</flux:menu.item>
        @endforelse

        <flux:menu.separator />

        <flux:menu.item href="{{ route('company.create') }}" icon="plus">
            {{ __('Add Company') }}
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
```

### Data Requirements

**Passed to component:**
- `$currentCompany` - The user's currently active company (from `auth()->user()->currentCompany`)
- `$companies` - Collection of companies user belongs to (from `auth()->user()->companies`)

**Route for switching:**
```php
Route::post('company/{company}/switch', [CompanyController::class, 'switch'])->name('company.switch');
```

**Action for switching:**
```php
// App/Actions/SwitchCompany.php
class SwitchCompany
{
    public function __invoke(User $user, Company $company): RedirectResponse
    {
        // Validate user belongs to company
        // Update user's current_company_id
        // Redirect back
    }
}
```

### Placement in Sidebar

Add after logo in `resources/views/layouts/app/sidebar.blade.php`:

```blade
<flux:sidebar.header>
    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
    <flux:sidebar.collapse class="lg:hidden" />
</flux:sidebar.header>

<!-- Company Switcher -->
<x-company-switcher />

<flux:sidebar.nav>
    <!-- menu items -->
</flux:sidebar.nav>
```

---

## Footer

Currently not implemented as a separate layout component. Footer content can be added directly within the main content area using standard Blade templates or as a custom component.

---

## Laravel Menus Package (Optional)

For dynamic menu generation based on user roles/permissions from the database:

```bash
php artisan vendor:publish --tag=laravel-menus-config
php artisan vendor:publish --tag=laravel-menus-views
```

**Integration with Flux UI:**

Render generated menu items as `<flux:sidebar.item>` components:

```blade
<flux:sidebar.nav>
    @foreach(menu()->getItems() as $item)
        <flux:sidebar.item icon="{{ $item->icon }}" href="{{ $item->url }}">
            {{ $item->title }}
        </flux:sidebar.item>
    @endforeach
</flux:sidebar.nav>
```
