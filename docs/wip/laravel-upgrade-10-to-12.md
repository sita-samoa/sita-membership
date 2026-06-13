# Laravel Upgrade: 10 → 11 → 12

- **Status:** Pending

## Scope

Upgrade Laravel from version 10.8 to version 12 via intermediate version 11. This upgrade is necessary to stay on a supported version and receive security patches.

## Prerequisites

- [ ] Verify PHP 8.2+ is available in all environments (dev, staging, production)
- [ ] Review all package dependencies for compatibility
- [ ] Create database backup before starting
- [ ] Ensure test suite passes before beginning

## Phase 1: Upgrade Laravel 10 → 11

### Pre-Upgrade Tasks
- [ ] Update PHP requirement to ^8.2 in `composer.json`
- [ ] Review [Laravel 11 Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
- [ ] Check Jetstream 3 → 4 compatibility
- [ ] Check Sanctum 3 → 4 compatibility

### Core Upgrade Tasks
- [ ] Update `laravel/framework` from `^10.8` to `^11.0`
- [ ] Update `laravel/jetstream` from `^3.1` to `^4.0`
- [ ] Update `laravel/sanctum` from `^3.2` to `^4.0`
- [ ] Update `nunomaduro/collision` from `^7.0` to `^8.0`
- [ ] Update `spatie/laravel-ignition` from `^2.0` to `^3.0`
- [ ] Run `composer update`

### Application Structure Changes
- [ ] Review new slimmed application structure
- [ ] Update `bootstrap/app.php` for new structure
- [ ] Review `config/` directory changes (files may need consolidation)
- [ ] Verify route files location and structure

### Testing & Validation
- [ ] Run full test suite: `composer test`
- [ ] Run linting: `composer lint`
- [ ] Manual smoke testing of key features
- [ ] Verify Jetstream authentication flows
- [ ] Check Inertia.js integration

## Phase 2: Upgrade Laravel 11 → 12

### Pre-Upgrade Tasks
- [ ] Review [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [ ] Verify all packages support Laravel 12

### Core Upgrade Tasks
- [ ] Update `laravel/framework` from `^11.0` to `^12.0`
- [ ] Run `composer update`

### Testing & Validation
- [ ] Run full test suite: `composer test`
- [ ] Run linting: `composer lint`
- [ ] Manual smoke testing of key features

## Post-Upgrade Tasks

- [ ] Update `CHANGELOG.md` with upgrade notes
- [ ] Update technical documentation if needed
- [ ] Deploy to staging environment
- [ ] Deploy to production (coordinate with team)

## References

- [Laravel 11.x Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
- [Laravel 12.x Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Laravel 12.x Release Notes](https://laravel.com/docs/12.x/releases)
