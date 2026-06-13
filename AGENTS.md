# AGENTS.md — SITA Membership

Guidance for AI coding agents (Copilot, Cursor, Cascade, etc.) working in this repository.

---

## Project Overview

SITA Membership is a membership management system for the Samoa ICT Association (SITA).

- **Backend**: Laravel 11 (PHP 8.2+) with Inertia.js server-side adapter
- **Frontend**: Vue 3 + Inertia.js + Pinia, built with Vite
- **Styling**: Tailwind CSS 3 with Flowbite components
- **Database**: MySQL/MariaDB via Docker
- **Reverse proxy**: Caddy (production) / Traefik (local dev)
- **Containerisation**: Docker Compose

---

## Repository Layout

```
laravel/          Laravel application root (PHP + JS assets live here)
  app/            PHP application code (Actions, Enums, Models, etc.)
  database/       Migrations, factories, seeders
  resources/      Blade views, Vue components, JS entry points
  tests/          PestPHP test suites
  composer.json   PHP dependency manifest
  package.json    Node/JS dependency manifest
docker.mk         Docker Compose make targets (included by Makefile)
Makefile          Top-level make entry point
compose.yml       Base Docker Compose service definitions
compose.dev.yml   Dev overrides (Traefik SSL)
compose.prod.yml  Prod overrides (Caddy SSL)
docs/             Project documentation
```

---

## Tech Stack Versions

| Layer            | Technology                  | Version            |
| ---------------- | --------------------------- | ------------------ |
| Language         | PHP                         | ^8.2               |
| Framework        | Laravel                     | ^11.0              |
| Auth / Teams     | Laravel Jetstream           | ^5.0               |
| API auth         | Laravel Sanctum             | ^4.0               |
| SSR bridge       | Inertia.js Laravel          | ^1.0               |
| Frontend         | Vue 3                       | ^3.2               |
| Routing (client) | Inertia.js Vue 3            | ^1.0               |
| State            | Pinia                       | ^2.1               |
| Build            | Vite                        | ^6.4               |
| Styling          | Tailwind CSS                | ^3.1               |
| Components       | Flowbite / Flowbite-Vue     | ^2.3 / ^0.1        |
| Testing          | PestPHP + Pest Laravel      | ^2.0               |
| Error tracking   | Sentry (PHP + Vue)          | ^4.6 / ^8.33       |
| Code quality     | Laravel Pint, PHPCS, Rector | ^1.0 / ^3.7 / ^1.0 |
| JS linting       | ESLint + Prettier           | ^8.41 / ^2.8       |

---

## Development Environment

The project runs entirely inside Docker. There is **no local PHP or Node requirement** beyond Docker itself.

### Starting the stack

```bash
# Standard (no SSL)
make up

# Local dev with Traefik SSL
make dev

# Production with Caddy SSL
make prod
```

### Stopping / cleaning up

```bash
make stop        # Stop containers
make down        # Stop containers (alias)
make prune       # Remove containers + volumes
```

### Common tasks

```bash
# Open a shell in the php container
make shell

# Run an Artisan command
make artisan migrate
make artisan "db:seed --class=DatabaseSeeder"

# Run Composer
make composer install
make composer "require vendor/package"

# View logs
make logs              # all services
make logs php          # php service only
make logs-dev php      # dev environment, php service
make logs-prod php     # prod environment, php service

# List running containers
make ps
```

---

## Running Tests

All test commands execute inside the Docker container.

```bash
# Full test suite
make artisan test
# or directly via composer script
make composer test

# Single test filter
make composer test_single
```

PestPHP is the test runner. Test files live under `laravel/tests/`.

---

## Code Style & Linting

### PHP

```bash
# Check (no write)
make composer lint

# Auto-fix
make composer format
```

This runs Laravel Pint, PHP_CodeSniffer, and Rector in sequence.

### JavaScript / Vue

```bash
# Inside the container (npm)
npm run lint      # ESLint --fix
npm run format    # Prettier write
```

Pre-commit hooks (Husky + lint-staged + pretty-quick) run automatically on `git commit`.

---

## Agent Rules

1. **Do not modify** `compose.yml`, `compose.prod.yml`, or `Caddyfile` without explicit instruction — these affect production.
2. **Migrations are append-only** — never edit an existing migration file; always create a new one.
3. **Seeder classes** (`DatabaseSeeder`, `UsersTableSeeder`, `DemoUsersSeeder`, `MembersTableSeeder`) are used by the `composer build` and `composer dev` scripts — keep their class names stable.
4. **PHP version target is 8.2+** — use modern PHP syntax (readonly properties, enums, fibers where appropriate) but ensure compatibility with 8.2.
5. **Laravel 11 conventions** — both the slim `bootstrap/app.php` style and the traditional Kernel-based structure are valid. The project currently uses the Kernel-based approach with `Http/Kernel.php` and `Console/Kernel.php`.
6. **Frontend state** lives in Pinia stores under `resources/js/stores/`. Do not reach into Vue component internals for cross-component state.
7. **Inertia shared data** is the source of truth for server-to-client props — extend `HandleInertiaRequests` middleware rather than adding ad-hoc `view()->share()` calls.
8. **Avoid `any` in TypeScript / untyped JS** — annotate props, emits, and store state.
9. **Tests must pass** before opening a PR. Run `make composer test` locally.
10. **Secrets** — never commit `.env`, `.env.prod`, or any file containing credentials. Use `.env.example` as the template.
