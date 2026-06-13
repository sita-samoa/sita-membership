# upgrade to Laravel 11

- **Issue:** #379
- **Branch:** `feature/379-upgrade-to-laravel-11`
- **Status:** In Progress

## Scope

Upgrade Laravel from version 10.8 to version 11. This is the first phase of the overall upgrade path (10 → 11 → 12).

See related WIP document for full plan: `docs/wip/laravel-upgrade-10-to-12.md`

## Tasks

- [x] Update PHP requirement to ^8.2 in `composer.json`
- [x] Update `laravel/framework` from `^10.8` to `^11.0`
- [x] Update `laravel/jetstream` from `^3.1` to `^5.0`
- [x] Update `laravel/sanctum` from `^3.2` to `^4.0`
- [x] Update `nunomaduro/collision` from `^7.0` to `^8.1`
- [x] Update `spatie/laravel-ignition` from `^2.0` to `^2.0` (compatible with Laravel 11)
- [ ] Run `composer update`
- [x] Review and update application structure changes (Kernel structure preserved, sanctum config updated)
- [ ] Run test suite: `composer test`
- [ ] Run linting: `composer lint`
- [ ] Manual smoke testing
