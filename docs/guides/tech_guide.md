# Technical Guide

## Getting started

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
sita-membership.docker.localhost:8000
```

Create test accounts and dumy data (see [Test accounts](#test-accounts))

```
make composer dev
```

## Test accounts

Running `make composer dev` will create test accounts and dummy data for local dev.

```
# demo@example.com - user with no roles
# executive@example.com - user with executive role
# coordinator@example.com - user with coordinator role
# admin@example.com - user with admin role

# All accounts use "password" as its password
```

## Coding style and etiqueue

We have linting for PHP and JS which should take care of most things. Please be respectful when adding code comments and responding to feedback.

## Linting

```
# run in php container
composer lint # shows warnings and errors
composer lint_ci # shows only errors
composer format # tries to fix php problems

# run in node container
npm run lint # shows warnings and tries to fix
npm run format # tries to fix js/vue problems
```

## Backups

```
# To take a backup run the following command
php artisan backup:run

# to clean out old backups
php artisan backup:clean
```

## Git leaks

The repo will be scanned for secrets each time docker compose up is called. It
will also be checked as part of Github actions. If there is a leak it will
appear in .gitleaks/findings.json file.

## SSL support on dev

To run your dev with SSL support use the following command

```
# start containers
docker compose -f docker-compose.yml -f docker-compose.ssl.yml up -d

# stop containers
docker compose -f docker-compose.yml -f docker-compose.ssl.yml stop
```

## Environments

Update the laravel .env file to the following values as needed

- local - for local development
- demo - for a uat or demo site (demo users cannot be deleted)
- production - for production site

## On Production

Follow Getting started steps but use .env.example as the template for .env (ie cp .env.example .env).

Also set **APP_ENV**=production and **GOOGLE_ANALYTICS_GA4**. This will ensure Google Analytics works correctly.

Set **MAIL_BACKUPS_TO_ADDRESS** to be notified of backup statuses.

Also if using SSL update the following variables accordingly in .env. Here
example.com is used as an example domain

```
DOMAIN=example.com
EMAIL=your@email.com
CERT_RESOLVER=letsencrypt
```

If you run composer dev on production make sure to reset it by running composer build to ensure there are no test accounts on pord.

Ensure that the following commands are run on a cron see https://laravel.com/docs/10.x/scheduling#running-the-scheduler

Also set **APP_ENV**=production and **GOOGLE_ANALYTICS_GA4**. This will ensure Google Analytics works correctly.

# run every 5 minutes - for running queues

```
php artisan queue:work database --tries=1 --max-time=30 --stop-when-empty
```

### Google Analytics

Register for a google recaptcha site key and secret
https://www.google.com/recaptcha/admin/create

In the domains field enter the following:

`sita-membership.docker.localhost`

Once received, add your google recaptcha environment variables to laravel/.env

```
GOOGLE_RECAPTCHA_SITE_KEY=YOUR_GOOGLE_RECAPTCHA_SITE_KEY
GOOGLE_RECAPTCHA_SECRET_KEY=YOUR_GOOGLE_RECAPTCHA_SECRET_KEY

```

If you run composer dev on production make sure to reset it by running composer build to ensure there are no test accounts.

Ensure that the following commands are run on a cron see https://laravel.com/docs/10.x/scheduling#running-the-scheduler

```
# run every minute - for scheduled tasks

php artisan schedule:run

# run every 5 minutes - for running queues

php artisan queue:work database --tries=1 --max-time=30 --stop-when-empty

```

### Google Analytics

Register for a google recaptcha site key and secret
https://www.google.com/recaptcha/admin/create

In the domains field enter the following:

`sita-membership.docker.localhost`

Once received, add your google recaptcha environment variables to laravel/.env

```
GOOGLE_RECAPTCHA_SITE_KEY=YOUR_GOOGLE_RECAPTCHA_SITE_KEY
GOOGLE_RECAPTCHA_SECRET_KEY=YOUR_GOOGLE_RECAPTCHA_SECRET_KEY
```

## Gitpod Integration

You can start coding using Gitpod.

First Signup for a [Gitpod account](https://gitpod.io/login/), then click the link below:

[![Open in Gitpod](https://gitpod.io/button/open-in-gitpod.svg)](https://gitpod.io/#https://github.com/sita-samoa/sita-membership)

## Tugboat Integration

Tugboat will create a staging environment for each PR for testing. With default
logins for admin, coordinator, editor and executive using composer dev.

## Git revise

Git revise can be install to improve your commits and messaging run `bash .install-git-revise.sh`. Run `git revise -ie` to update commit messages in bulk.

## Common commands

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

# log into php container (this will allow use php artisan)
make shell

# run tests
make composer test

# create pest test
make artisan "make:test UserTest --pest"
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

You can also use make commands. Use `make help` to find a list.

## Resources

- [Jet stream (inertia-vue)](https://jetstream.laravel.com/2.x/introduction.html#inertia-vue)
- [Laravel vite](https://laravel.com/docs/10.x/vite)
- [Github Project](https://github.com/orgs/sita-samoa/projects/1)
- [Icons](https://pictogrammers.com/library/mdi/)
- [SSL support](https://github.com/bubelov/traefik-letsencrypt-compose)
- [Laravel Backup](https://github.com/spatie/laravel-backup)
