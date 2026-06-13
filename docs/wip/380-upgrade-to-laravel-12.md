# Upgrade to Laravel 12

- **Issue:** #380
- **Branch:** `feature/380-upgrade-to-laravel-12`
- **Status:** In Progress
- **Related:** #379 · `docs/wip/laravel-10-structure-on-laravel-11.md` · `docs/wip/laravel-upgrade-10-to-12.md`

## Scope

The app is already on **Laravel 11** (framework `^11.0`, Jetstream `^5.0`, Sanctum `^4.0`) but still uses the **Laravel 10 application structure** (Kernel-based — see `docs/wip/laravel-10-structure-on-laravel-11.md`). This issue covers:

1. Migrate to the Laravel 11 slim app structure (prerequisite recommended before Laravel 12).
2. Upgrade `laravel/framework` to `^12.0` and all affected packages.

## Phase 1: Migrate to Laravel 11 Slim Structure

> Currently using the old Kernel-based structure. This is supported on Laravel 11 but should be resolved before upgrading to 12.

- [ ] Review [Laravel 11 New Application Structure](https://laravel.com/docs/11.x/structure)
- [ ] Migrate `app/Http/Kernel.php` → `bootstrap/app.php` `->withMiddleware()`
- [ ] Migrate `app/Console/Kernel.php` → `bootstrap/app.php` `->withSchedule()` / `routes/console.php`
- [ ] Migrate `app/Exceptions/Handler.php` → `bootstrap/app.php` `->withExceptions()`
- [ ] Consolidate service providers where applicable
- [ ] Update `bootstrap/app.php` to the slim style
- [ ] Run full test suite: `make composer test`
- [ ] Run linting: `make composer lint`
- [ ] Smoke test auth flows (Jetstream + Inertia)

## Phase 2: Upgrade Laravel 11 → 12

- [ ] Review [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [ ] Check all package compatibility with Laravel 12:
  - `laravel/jetstream` (currently `^5.0`)
  - `inertiajs/inertia-laravel` (currently `^1.0`)
  - `owen-it/laravel-auditing` (currently `^13.5`)
  - `spatie/laravel-backup` (currently `^8.1`)
  - `laraveldaily/laravel-invoices` (currently `^4.0`)
  - `maatwebsite/excel` (currently `^3.1`)
  - `tightenco/ziggy` (currently `^1.0`)
  - `sentry/sentry-laravel` (currently `^4.6`)
  - `spatie/laravel-ignition` (currently `^2.0`)
  - `driftingly/rector-laravel` (currently `^0.20.0`)
- [ ] Update `laravel/framework` from `^11.0` to `^12.0` in `composer.json`
- [ ] Update any packages requiring version bumps for Laravel 12 support
- [ ] Run `make composer "update"` inside the container
- [ ] Run full test suite: `make composer test`
- [ ] Run linting: `make composer lint`
- [ ] Smoke test key features (auth, member management, invoices, exports)

## Post-Upgrade

- [ ] Update `AGENTS.md` tech stack versions table
- [ ] Deploy to staging and validate
- [ ] Deploy to production (coordinate with team)

## References

- [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Laravel 11 Slim Structure Docs](https://laravel.com/docs/11.x/structure)
- `docs/wip/laravel-10-structure-on-laravel-11.md`
- `docs/wip/laravel-upgrade-10-to-12.md`
