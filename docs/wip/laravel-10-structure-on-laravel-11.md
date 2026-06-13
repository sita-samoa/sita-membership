# Laravel 10 Application Structure Running on Laravel 11

- **Status:** Pending

## Context

This app was upgraded to Laravel 11 while **keeping the Laravel 10 application structure**. This is explicitly supported — Laravel 11 has been tuned to work with both structures and the upgrade guide advises against migrating structure as part of the version upgrade.

Related to #379

## What This Means

The following files are still present and authoritative (not replaced by the new slim structure):

| File | Purpose |
|---|---|
| `app/Http/Kernel.php` | Middleware registration |
| `app/Console/Kernel.php` | Scheduled commands |
| `app/Exceptions/Handler.php` | Exception handling |
| `app/Providers/*.php` | Service providers |
| `bootstrap/app.php` | Binds kernels (old style) |

## Developer Friction to Watch For

1. **Laravel 11 docs use the new structure** — examples that say "add this to `bootstrap/app.php`" via `->withMiddleware()` or `->withExceptions()` do not apply here. The equivalent goes in `Kernel.php` or a service provider.

2. **Package upgrade guides** — same issue. Translate any new-structure instructions back to the Kernel/provider pattern before applying them.

3. **Community examples and AI suggestions** — most new Laravel 11+ snippets assume the slim structure. Always verify which structure an example targets before copying it.

## When to Migrate to the New Structure

Not required now. Consider doing it as a dedicated task before upgrading to Laravel 12, since the new structure is the direction the ecosystem is moving.
