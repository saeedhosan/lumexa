# Onboarding

This document describes the post-signup onboarding flow for new users and admins.

## Goal

After account creation, guide the user through the minimum required setup steps before they start using the application.

The experience should be:

- simple
- clean
- easy to understand
- role-aware

## Flow Overview

1. User creates an account.
2. User is redirected to the welcome page.
3. User clicks `Continue` to start onboarding.
4. User completes the onboarding page.
5. User is redirected to the onboarding complete page.

## Welcome Page

The welcome page should have a minimal, professional UI with a clear call to action.

### Content

- Greeting: `Hello, {user name}`
- Welcome message: `Welcome to {app name}`
- Supporting message: `You are almost ready with a few simple steps`
- Primary button label: `Continue`

### Behavior

- After successful account creation, redirect the user to this page.
- The `Continue` button should link to the onboarding page.

## Onboarding Page

The onboarding page should stay focused on the necessary actions only.

### General Requirements

- Keep the layout simple and clean.
- Show only the required form fields and actions.
- Provide a clear `Save and Continue` action at the bottom.
- Redirect to the onboarding complete page after successful submission.

### User Onboarding

For a standard user, the onboarding page may include:

- selecting a company
- requesting an invite to a multi-tenant workspace
- any other required setup steps for access

### Admin Onboarding

For an admin, the onboarding page may include:

- creating a company
- completing the minimum company setup

## Onboarding Complete

The onboarding complete page should be a simple, human-friendly summary that explains what the user can do next.

### Content

- confirmation that onboarding is complete
- a short guide based on the user role
- a clear next action into the app

### Behavior

- Show guidance that matches the user role.
- Keep the message brief and practical.

## UI and UX Notes

- Use a calm, polished visual hierarchy.
- Make the primary action obvious.
- Avoid clutter and unnecessary steps.
- Keep copy short and direct.
- Make the layout responsive on desktop and mobile.

## Acceptance Criteria

- New users are redirected to the welcome page after signup.
- The welcome page includes a greeting, short supporting text, and a `Continue` button.
- The onboarding page adapts to user role.
- The onboarding page includes a `Save and Continue` action.
- Successful onboarding redirects to the completion page.
- The completion page gives the user a simple next step.
