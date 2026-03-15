# UI Layouts

This document describes the layout components available in the application.

## App Layout

The main application layout with sidebar navigation.

- **Component**: `layouts::app`
- **Usage**: `<x-layouts::app :title="__('Page Title')"> content </x-layouts::app>`
- **Props**:
  - `title` (optional) - Page title displayed in the header

The app layout internally uses `layouts::app.sidebar` which provides:
- Responsive sidebar with Flux UI components
- Mobile header with hamburger menu
- User profile dropdown (desktop & mobile)

## Auth Layout

The authentication layout for login, register, and other auth pages.

- **Component**: `layouts::auth`
- **Usage**: `<x-layouts::auth> content </x-layouts::auth>`

This layout wraps `layouts::auth.simple` which provides a centered card design.

## Sidebar

Built into the app layout using Flux UI components:

- **Logo**: Via `x-app-logo` component
- **Navigation**: `flux:sidebar.nav` with `flux:sidebar.item` for menu items
- **Groups**: `flux:sidebar.group` for organizing menu items
- **User Menu**: `x-desktop-user-menu` component for profile/logout

## Mobile Header

The mobile header appears on smaller screens:
- Sidebar toggle button (`flux:sidebar.toggle`)
- User profile dropdown with settings and logout options
