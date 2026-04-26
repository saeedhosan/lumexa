# Testing Documentation

## Feature tests

### Auth

**tests/Feature/Auth/AuthenticationTest.php**

- login screen can be rendered
- users can authenticate using the login screen
- users can not authenticate with invalid credential
- users with two factor enabled are redirected to two factor challenge
- users can logout

**tests/Feature/Auth/RegistrationTest.php**

- registration screen can be rendered
- user cannot register with invalid data
- registered user can access dasbhoard
- new users can register

**tests/Feature/Auth/PasswordResetTest.php**

- reset password link screen can be rendered
- reset password link can be requested
- reset password screen can be rendered
- password can be reset with valid token

**tests/Feature/Auth/EmailVerificationTest.php**

- email verification screen can be rendered
- email can be verified
- email is not verified with invalid hash
- email can be verified without authenticated
- verified user visiting verification link is redirected without event again

**tests/Feature/Auth/TwoFactorChallengeTest.php**

- two factor challenge can be rendered
- two factor challenge redirects to login when not authenticated

**tests/Feature/Auth/PasswordConfirmationTest.php**

- confirm password screen can be rendered
- guest user cannot visit to the confirm password screen

### Settings

**tests/Feature/Settings/TwoFactorAuthenticationTest.php**

- two factor settings page can be rendered
- two factor settings page requires password confirmation when enabled
- two factor settings page returns forbidden response when two factor is disabled
- two factor authentication disabled when confirmation abandoned between requests

**tests/Feature/Settings/ProfileUpdateTest.php**

- profile page is displayed
- profile information can be updated
- email verification status is unchanged when email address is unchanged
- user can delete their account
- correct password must be provided to delete account

**tests/Feature/Settings/PasswordUpdateTest.php**

- password can be updated
- correct password must be provided to update password

### Apps

**tests/Feature/Apps/ServiceTest.php**

- customer can access services index page
- customer can access services create page
- services index displays all services regardless of company

**tests/Feature/Apps/LeadTest.php**

- guests are redirected to the login page for leads index
- guests are redirected to the login page for leads create
- guests are redirected to the login page for leads show
- guests are redirected to the login page for leads edit
- authenticated users can access leads index
- authenticated users can access leads create
- authenticated users can access leads show
- leads index displays search functionality
- leads index displays leads
- leads index supports sorting by title
- leads show displays lead details page
- leads show displays lead lists

**tests/Feature/Apps/ActivityTest.php**

- guests are redirected to the login page for index
- guests are redirected to the login page for create
- guests are redirected to the login page for show
- guests are redirected to the login page for edit
- authenticated users can access index
- authenticated users can access activities create
- authenticated users can access activities show
- authenticated users can access activities edit
- activities index displays page
- activities create page displays form

**tests/Feature/Apps/CampaignTest.php**

- guests are redirected to the login page for campaigns index
- guests are redirected to the login page for campaigns create
- guests are redirected to the login page for campaigns show
- guests are redirected to the login page for campaigns edit
- authenticated users can access campaigns index
- authenticated users can access campaigns create
- authenticated users can access campaigns show
- authenticated users can access campaigns edit
- campaigns index displays page
- campaigns create page displays form

**tests/Feature/Apps/CompanyTest.php**

- guests are redirected to the login page for companies index
- guests are redirected to the login page for companies create
- guests are redirected to the login page for companies show
- guests are redirected to the login page for companies edit
- authenticated users can access companies index
- authenticated users can access companies create
- authenticated users can access companies show
- authenticated users can access companies edit
- companies index displays user companies
- companies show displays company details

### Admin

**tests/Feature/Admin/ServiceTest.php**

- can render services page
- shows services list
- shows service details in table

**tests/Feature/Admin/InviteTest.php**

- can render the invite list screen
- can render the send invite form
- can add existing user to company as super admin
- shows error for user not found
- can add user to company as admin with company access
- cannot add user to company without access
- validates required fields when sending invite
- validates email format
- adds existing user directly to company if they exist but have no access
- shows error for user already has access to company
- shows only accessible company users for admin

**tests/Feature/Admin/ReportTest.php**

- can render reports page
- shows user and company totals
- shows users by type breakdown
- admin only sees their company data

### API

**tests/Feature/Api/LeadApiTest.php**

- api leads index returns success
- api leads index returns json structure

### Components

**tests/Feature/Components/LeadTableListTest.php**

- lead lists displays search input
- lead lists displays lead list table when records exist
- lead lists displays empty state when no records
- lead lists search filters by first name
- lead lists search filters by last name
- lead lists search filters by email
- lead lists search filters by phone
- lead lists can sort by status
- lead lists toggles sort direction on repeated sort
- lead lists delete removes lead list record
- lead lists search resets pagination

**tests/Feature/Components/LeadTableItemTest.php**

- lead items displays search input
- lead items displays leads table when leads exist
- lead items displays empty state when no leads
- lead items search filters results
- lead items can sort by title
- lead items can sort by status
- lead items can sort by created_at
- lead items toggles sort direction on repeated sort
- lead items delete removes lead
- lead items search resets pagination

**tests/Feature/Components/LeadImportTest.php**

- lead import modal can be opened
- lead import validates required fields
- lead import validates title is required
- lead import validates file is required
- lead import validates file type
- lead import accepts valid csv file
- lead import resets form after successful import

**tests/Feature/Components/SwitchCompanyTest.php**

- company switch displays current company name
- company switch displays select company text when no company selected
- company switch shows all user companies
- company switch shows check icon on current company
- company switch can switch to another company
- company switch does not switch if company not associated with user

### Models

**tests/Feature/Models/LeadListTest.php**

- creates a lead list with factory
- creates a lead list with specific status
- creates a lead list for a specific lead
- belongs to a lead
- has valid contact information
- can check lead list status
- can identify final status
- can identify non-final status
- creates lead lists with all status types

**tests/Feature/Models/LeadTest.php**

- creates a lead with factory
- creates a lead with specific status
- creates a lead with specific user and company
- belongs to a user
- belongs to a company
- has many lead lists
- can check lead status
- creates leads with all status types
- scopes leads by company for authenticated user
- returns empty collection when user has no company

### Dashboard

**tests/Feature/DashboardTest.php**

- guests are redirected to the login page
- authenticated users can visit the dashboard

**tests/Feature/ExampleTest.php**

- returns a successful response

## Unit tests

**tests/Unit/Logging/DatabaseLoggerTest.php**

- DatabaseLogger - returns monolog logger with database handler
- DatabaseHandler - writes log record to database
- DatabaseHandler - writes log with user_id from context
- AddCallerIntrospection - adds processors to monolog logger
- AddCallerIntrospection - handles non-monolog loggers gracefully

**tests/Unit/ExampleTest.php**

- that true is true

---

# Recommended Additional Tests for Production

The following test suites are recommended to ensure high security, bug-free production usage:

## Security & Authorization Tests

### Role-Based Access Control (RBAC)

- super admin can view all users
- admin can only view users in their company
- unauthorized users cannot access admin routes
- users cannot access other company data
- delete operations restricted to super admin only

**Proposed test files:**

- `tests/Feature/Security/RBACTest.php`
- `tests/Feature/Policies/UserPolicyTest.php`
- `tests/Feature/Policies/LeadPolicyTest.php`
- `tests/Feature/Policies/CompanyPolicyTest.php`

## Admin Module Tests

### User Management

- admin can create new users
- admin can assign users to companies
- admin cannot grant super admin privileges
- super admin can delete any user
- user search functionality works
- pagination works correctly
- user status can be changed

**Proposed test files:**

- `tests/Feature/Admin/UserTest.php`

### Company Management

- super admin can create companies
- admin can view their company
- company update works correctly
- company deletion works
- company-users relationship management

**Proposed test files:**

- `tests/Feature/Admin/CompanyTest.php`

### Billing & Invoicing

- invoice list displays correctly
- invoices can be created
- invoice PDF generation (if applicable)
- billing history displays correctly

**Proposed test files:**

- `tests/Feature/Admin/BillingTest.php`
- `tests/Feature/Admin/InvoiceTest.php`

### Plans & Services Management

- plans can be listed
- plans can be created/updated/deleted
- service versioning works
- service activation/deactivation

**Proposed test files:**

- `tests/Feature/Admin/PlanTest.php`
- `tests/Feature/Admin/ServiceManagementTest.php`

## API Security Tests

### Authentication

- unauthenticated requests are rejected
- invalid tokens are rejected
- expired tokens are rejected
- proper error messages returned

### Rate Limiting

- API rate limit is enforced
- rate limit headers are present
- exceeded limit returns proper error

### Input Validation

- invalid JSON is rejected
- required fields are validated
- field types are validated
- maximum lengths are enforced

**Proposed test files:**

- `tests/Feature/Api/LeadApiSecurityTest.php`
- `tests/Feature/Api/LeadApiValidationTest.php`

## Form Validation Tests

### Request Validation

- StoreUserRequest validates correctly
- UpdateUserRequest validates correctly
- custom validation rules work
- error messages are returned

**Proposed test files:**

- `tests/Unit/Http/Requests/StoreUserRequestTest.php`
- `tests/Unit/Http/Requests/UpdateUserRequestTest.php`

## File Upload Security Tests

### Lead Import

- file type validation works (csv only)
- file size limit is enforced
- malformed CSV is handled gracefully
- large file processing doesn't timeout
- duplicate detection works

**Additional tests for:**

- `tests/Feature/Components/LeadImportSecurityTest.php`

## Company Switching Tests

### Security

- users cannot switch to unauthorized companies
- session is properly updated
- redirect works correctly after switch

**Additional tests for:**

- `tests/Feature/Components/SwitchCompanySecurityTest.php`

## Audit Logging Tests

### Database Logger

- all CRUD operations are logged
- log entries contain correct data
- user ID association works
- log retention policy works

**Proposed test files:**

- `tests/Feature/Admin/LogsTest.php`
- `tests/Unit/Logging/AuditLogTest.php`

## Performance Tests

### Database Queries

- no N+1 query problems
- proper indexing is used
- paginated queries are efficient

**Proposed test files:**

- `tests/Unit/Performance/QueryOptimizationTest.php`

## Integration Tests

### Multi-User Scenarios

- concurrent company switches don't conflict
- multipleadmin operations work correctly

### Full Workflow Tests

- user registration -> company creation -> lead creation
- invite user flow
- password reset -> login flow

**Proposed test files:**

- `tests/Feature/Integration/UserRegistrationFlowTest.php`
- `tests/Feature/Integration/InviteUserFlowTest.php`

## Browser/E2E Tests (Optional)

- complete login flow
- complete registration flow
- lead creation workflow
- company switch workflow

**Proposed test files:**

- `tests/Browser/LeadWorkflowTest.php`
- `tests/Browser/AuthenticationFlowTest.php`