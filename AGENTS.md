# SITA Membership

## Project Overview

Membership management system for the Samoa ICT Association (SITA). Tracks member registrations, renewals, and organisational data for Samoa's ICT sector.

## Technology Stack

- PHP ^8.2 · Laravel ^12 · Jetstream ^5 · Inertia ^2
- Vue 3 ^3.2 · Pinia ^2.1 · Vite ^8
- Tailwind CSS ^3.1 · Flowbite / Flowbite-Vue
- MySQL/MariaDB · Docker Compose
- Node ^20 · PestPHP ^3

## Architecture

```text
Vue 3 (SPA pages) → Inertia.js → Laravel Controllers → Models → MySQL
```

- **Inertia.js** bridges server and client — no separate API layer for the UI.
- **Shared data** flows via `HandleInertiaRequests` middleware (source of truth for server→client props).
- **Frontend state** lives in Pinia stores (`resources/js/stores/`). Components do not share state directly.
- **Kernel-based structure** — uses `Http/Kernel.php` and `Console/Kernel.php` (not slim `bootstrap/app.php`).

## Critical Rules

1. Never modify `compose.yml`, `compose.prod.yml`, or `Caddyfile` without explicit instruction.
2. Migrations are append-only — never edit an existing migration; always create a new one.
3. Keep seeder class names stable (`DatabaseSeeder`, `UsersTableSeeder`, `DemoUsersSeeder`, `MembersTableSeeder`) — CI depends on them.
4. Never commit `.env`, `.env.prod`, or any file with credentials. Use `.env.example` as template.
5. No `any` types — annotate all props, emits, and store state.
6. Never commit to `main` branch. Use feature branches.

## Coding Standards

- PHP 8.2+ syntax: readonly properties, enums, match expressions.
- Extend `HandleInertiaRequests` for shared data — no `view()->share()`.
- Cross-component state must go through Pinia, not component internals.
- Conventional Commits: `<type>[scope]: <description>` (imperative, lowercase, ≤50 chars).
- Branch naming: `<type>/<short-description>` (kebab-case, 2–6 words).

## Testing Requirements

- All tests must pass before opening a PR.
- Add tests for business logic changes.
- PestPHP is the runner. Tests live in `laravel/tests/`.

## Commands

```bash
make up                  # Start stack (no SSL)
make dev                 # Start with Traefik SSL
make shell               # Shell into PHP container
make artisan test        # Run full test suite
make composer test       # Alias for tests
make composer lint       # Check style (Pint + PHPCS + Rector)
make composer format     # Auto-fix style
npm run lint             # ESLint --fix
npm run format           # Prettier write
```

## Known Pitfalls

- Husky pre-commit hooks run lint-staged on commit — bypass with `--no-verify` only when necessary.
- `composer build` and `composer dev` scripts invoke seeders by class name — renaming breaks CI.
- Both Kernel-based and slim bootstrap exist in Laravel 12 docs; this project uses Kernel-based.

## Decision Priorities

1. Correctness
2. Security
3. Maintainability
4. Developer experience
5. Performance

## Change Workflow

**Before coding:**

1. Read relevant files and understand affected modules.
2. Identify if change touches infra files (requires explicit approval).

**After coding:**

1. Run `make composer test`.
2. Run `make composer lint`.
3. Summarise what changed, why, and any risks.
