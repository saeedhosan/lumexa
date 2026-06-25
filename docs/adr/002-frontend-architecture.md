# ADR-002: Frontend Architecture — Livewire + Flux UI + Alpine

**Status:** Accepted  
**Date:** 2026-06-24

## Context

Lumexa needs dynamic, reactive UI (dashboard charts, onboarding wizard, company switching, modals) without maintaining a separate JavaScript SPA. The team has deep PHP expertise and wants to minimize context-switching between backend and frontend.

## Decision

Use **Laravel Livewire v4** as the reactive frontend framework, paired with **Flux UI** (Livewire-native component library) and **Alpine.js** for client-side interactions.

## Consequences

- **Positive:** All UI logic stays in PHP. No Vue/React build step, no API endpoints for page data, no client-side routing. Flux UI provides pre-built, accessible, Livewire-optimized components (modals, tables, sidebars, forms).
- **Negative:** Livewire has higher server memory usage per connection than an SPA. Complex drag-and-drop or animation-heavy UIs are harder to implement.
- **Trade-off:** For an admin dashboard, Livewire's developer velocity outweighs its server cost. If a public-facing app with high traffic were needed, a JavaScript SPA would be preferable.

## Alternatives Considered

- **React/Vue SPA:** Better for highly interactive UIs, but requires a full API layer, authentication token management, and separate deployment.
- **Inertia.js:** Good middle ground — keeps server-side routing but requires React/Vue knowledge.
- **Alpine-only:** Too limited for complex state like multi-step wizards and real-time updates.
