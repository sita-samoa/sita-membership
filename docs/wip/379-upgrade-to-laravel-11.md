# upgrade to Laravel 11

- **Issue:** #379
- **Branch:** `feature/379-upgrade-to-laravel-11`
- **Status:** In Progress

## Scope

Upgrade Laravel from version 10.8 to version 11. This is the first phase of the overall upgrade path (10 → 11 → 12).

See related WIP document for full plan: `docs/wip/laravel-upgrade-10-to-12.md`

## Tasks

- [ ] Update PHP requirement to ^8.2 in `composer.json`
- [ ] Update `laravel/framework` from `^10.8` to `^11.0`
- [ ] Update `laravel/jetstream` from `^3.1` to `^4.0`
- [ ] Update `laravel/sanctum` from `^3.2` to `^4.0`
- [ ] Update `nunomaduro/collision` from `^7.0` to `^8.0`
- [ ] Update `spatie/laravel-ignition` from `^2.0` to `^3.0`
- [ ] Run `composer update`
- [ ] Review and update application structure changes
- [ ] Run test suite: `composer test`
- [ ] Run linting: `composer lint`
- [ ] Manual smoke testing
