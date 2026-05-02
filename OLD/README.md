# Laravel SaaS for MikroTik

A production-ready SaaS platform for managing MikroTik ISP networks, built with Laravel 11.

## Features
- **Multi-Tenancy**: Data isolation using `tenant_id` and Middleware.
- **MikroTik Integration**: Direct RouterOS API connection for managing PPPoE secrets and queues.
- **RESTful API**: Clean API for managing Tenants, Routers, and Clients.
- **Scheduler**: Automated suspension of expired subscriptions.

## Setup
1. **Requirements**: PHP 8.3, Composer, MySQL.
2. **Install Dependencies**:
   ```bash
   composer install
   ```
3. **Environment**:
   Copy `.env.example` to `.env` and configure DB credentials.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Database**:
   Run migrations:
   ```bash
   php artisan migrate
   ```
5. **Serve**:
   ```bash
   php artisan serve
   ```

## Architecture
- **TenantMiddleware**: Detects tenant from Auth user and binds `tenant_id` to container.
- **BelongsToTenant Trait**: Automatically scopes Eloquent queries to the current tenant.
- **MikroTikService**: Handles all communications with RouterOS (create secret, disable, enable).
- **Encryption**: Router passwords are stored encrypted using Laravel's encryption (AES-256-CBC).

## Scheduler
The `app:check-expired-subscriptions` command runs daily to suspend expired clients.
Add to crontab:
```bash
* * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

## API Documentation
See `api_examples.md` for request examples.
