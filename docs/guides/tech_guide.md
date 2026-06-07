# Technical Guide

## Getting Started

### Dev environment with [docker4drupal](https://github.com/wodby/docker4drupal/releases)

This repository has been set up to work with docker compose. You need docker
and docker compose to use the commands below. You dont need to use docker.

```
# start up containers
make

# install dependencies
make composer install

# cp env.dev and configure - verify db configs
cp laravel/.env.dev laravel/.env

# run initialisation commands
make artisan key:generate

# run migrations and default data seed
make composer build

# To display user images and invoices run the following.
make artisan storage:link

# for scheduled events use the following command to process them
make artisan schedule:run

# for queued events use the following command to process them
make artisan queue:work
```

Access the Dev site on:

```
http://sita-membership.docker.localhost:8000
```

Create test accounts and dummy data (see [Test Accounts](#test-accounts))

```
make composer dev
```

## Test Accounts

Running `make composer dev` will create test accounts and dummy data for local dev.

```
# demo@example.com - user with no roles
# executive@example.com - user with executive role
# coordinator@example.com - user with coordinator role
# admin@example.com - user with admin role

# All accounts use "password" as its password
```

## Development Environment

### Coding Style and Etiquette

We have linting for PHP and JS which should take care of most things. Please be respectful when adding code comments and responding to feedback.

### Linting

```
# run in php container
composer lint # shows warnings and errors
composer lint_ci # shows only errors
composer format # tries to fix php problems

# run in node container
npm run lint # shows warnings and tries to fix
npm run format # tries to fix js/vue problems
```

### SSL Support on Dev (Traefik)

This project uses Traefik with Let's Encrypt SSL for local development, which provides easier debugging with a dashboard.

To run your dev with SSL support using Traefik, use the following command:

```
# start containers with Traefik
make dev

# or use the backward-compatible alias
make ssl

# stop containers
make stop
```

Access the Traefik dashboard at `https://traefik.sita-membership.docker.localhost:8080` (requires basic auth).

### Common Commands

```
# clear database and re-run migrations
make artisan migrate:fresh

# create a new model, controller and migration called Member
make artisan make:model -mrc Member

# start up dev environment
make

# stop environment
make stop

# delete everything and start in a clean environment
make down

# check logs
make logs

# check logs for php and node container
make logs php node

# check logs for dev environment
make logs-dev

# check logs for dev environment (specific services)
make logs-dev php nginx

# check logs for prod environment
make logs-prod

# check logs for prod environment (specific services)
make logs-prod php nginx

# log into php container (this will allow use php artisan)
make shell

# run tests
make composer test

# create pest test
make artisan "make:test UserTest --pest"
```

### Git Workflow Tools

#### Git Revise

Git revise can be installed to improve your commits and messaging. Run `bash .install-git-revise.sh`. Use `git revise -ie` to update commit messages in bulk.

#### Git Leaks

The repo will be scanned for secrets each time docker compose up is called. It will also be checked as part of Github actions. If there is a leak it will appear in `.gitleaks/findings.json` file.

## Production Deployment

### Environment Configuration

Update the Laravel `.env` file to the following values as needed:

- `local` - for local development
- `demo` - for a UAT or demo site (demo users cannot be deleted)
- `production` - for production site

### Initial Setup

Follow the Getting Started steps but use `.env.example` as the template for `.env` (i.e., `cp .env.example .env`).

Set the following environment variables:

- `APP_ENV=production` - Required for production mode
- `GOOGLE_ANALYTICS_GA4` - Ensures Google Analytics works correctly
- `MAIL_BACKUPS_TO_ADDRESS` - To be notified of backup statuses

### Caddy Configuration

This project uses Caddy with automatic Let's Encrypt SSL for production (simpler configuration than Traefik).

Update the following variables in `.env` for Caddy SSL support. Here `example.com` is used as an example domain:

```
PROJECT_BASE_URL=example.com
EMAIL=your@email.com
```

The Caddyfile in the project root automatically handles:

- SSL certificate generation and renewal via Let's Encrypt
- HTTP to HTTPS redirects
- Reverse proxy routing for all services

To deploy with Caddy:

```
make prod
```

SSL certificates are stored persistently in `docker-init/data/caddy/` and will survive container restarts.

#### Updating Caddyfile in Production

To update the Caddyfile without downtime:

```
# Edit Caddyfile locally
# Then reload configuration in the running container
docker exec <container_name> caddy reload
```

### Automated Cron Tasks

The production deployment includes a `crond` service that automatically runs the following tasks:

- **Every minute**: `php artisan schedule:run` - Processes Laravel scheduled tasks (backups, membership status updates, reminders)
- **Every 5 minutes**: `php artisan queue:work` - Processes queued jobs

No manual cron setup is required on the host server.

### ⚠️ Important Warning

**Never run `composer dev` on production.** This creates test accounts and dummy data. If you accidentally run it, reset by running `composer build` to ensure there are no test accounts on production.

## Integrations

### Gitpod

You can start coding using Gitpod.

First signup for a [Gitpod account](https://gitpod.io/login/), then click the link below:

[![Open in Gitpod](https://gitpod.io/button/open-in-gitpod.svg)](https://gitpod.io/#https://github.com/sita-samoa/sita-membership)

### Tugboat

Tugboat will create a staging environment for each PR for testing. With default logins for admin, coordinator, editor and executive using `composer dev`.

## Security & Configuration

### reCAPTCHA Setup

Register for a Google reCAPTCHA site key and secret:
https://www.google.com/recaptcha/admin/create

In the domains field enter the following:

```
sita-membership.docker.localhost
```

Once received, add your Google reCAPTCHA environment variables to `laravel/.env`:

```
GOOGLE_RECAPTCHA_SITE_KEY=YOUR_GOOGLE_RECAPTCHA_SITE_KEY
GOOGLE_RECAPTCHA_SECRET_KEY=YOUR_GOOGLE_RECAPTCHA_SECRET_KEY
```

## Maintenance

### Backups

```
# To take a backup run the following command
php artisan backup:run

# to clean out old backups
php artisan backup:clean
```

## Tips

Use bash aliases in your local dev. This repo includes an alias file you can use with `source .bash_aliases`

```
# docker compose aliases
alias dc="docker compose"
alias dup="docker compose up -d --remove-orphans"
alias dupl="docker compose up -d && docker compose logs php"
alias dphp="docker compose exec php sh"
alias dnode="docker compose exec node bash"
alias dl='docker compose logs -f' # eg dl nginx php for nginx and php logs
alias dstop="docker compose stop"

#docker4drupal aliases
alias mcomposer="make composer"
alias martisan="make artisan"
alias mlogs="make logs"
alias mshell="make shell"
```

You can also use make commands. Use `make help` to find a list of commands.

## Resources

- [Jet stream (inertia-vue)](https://jetstream.laravel.com/2.x/introduction.html#inertia-vue)
- [Laravel vite](https://laravel.com/docs/10.x/vite)
- [Github Project](https://github.com/orgs/sita-samoa/projects/1)
- [Icons](https://pictogrammers.com/library/mdi/)
- [SSL support](https://github.com/bubelov/traefik-letsencrypt-compose)
- [Laravel Backup](https://github.com/spatie/laravel-backup)
