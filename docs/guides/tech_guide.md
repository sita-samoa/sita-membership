# Technical Guide

## Getting started

### Dev environment with [docker4drupal](https://github.com/wodby/docker4drupal/releases)

This repository has been set up to work with docker compose. You need docker
and docker compose to use the commands below. You dont need to use docker.

```
# start up containers
docker compose up -d

# log in to php container
docker compose exec php sh

# switch to laravel folder
cd laravel

# install dependencies
composer install

# cp env.dev and configure
# verify db configs
cp .env.dev .env

# run initialisation commands
php artisan migrate
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=UsersTableSeeder
php artisan key:generate

# or you can run this (it will recreate the database each time)
composer build

# and this command to setup demo users and members for dev (must run after composer build)
composer dev

# register for a google recaptcha site key and secret here
https://www.google.com/recaptcha/admin/create

# in the domains field enter the following
sita-membership.docker.localhost

# once received, add your google recaptcha environment variables to laravel/.env
GOOGLE_RECAPTCHA_SITE_KEY=YOUR_GOOGLE_RECAPTCHA_SITE_KEY
GOOGLE_RECAPTCHA_SECRET_KEY=YOUR_GOOGLE_RECAPTCHA_SECRET_KEY

# for scheduled events use the following command to process them
php artisan schedule:run

```

* Once installed you can access the dev site on:

```
sita-membership.docker.localhost:8000
```

* Click "Register" and register a new account then use it to log in.
* OR - run `composer dev` to set up test accounts

## Test accounts

This will create test accounts and dummy data for local dev.

```
composer dev

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

## User images

To display them run the following comand to create a symlink

```
php artisan storage:link
```

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

* local - for local development
* demo - for a uat or demo site (demo users cannot be deleted)
* production - for production site

## On Production

Ensure that the following commands are run on a cron see https://laravel.com/docs/10.x/scheduling#running-the-scheduler

```
# run every minute - for scheduled tasks
php artisan schedule:run

# run every 5 minutes - for running queues
php artisan queue:work database --tries=1 --max-time=30 --stop-when-empty

```

Also set APP_ENV=production and GOOGLE_ANALYTICS_GA4. This will ensure Google Analytics works correctly.

Set MAIL_BACKUPS_TO_ADDRESS to be notified of backup statuses.

Also if using SSL update the following variables accordingly in .env. Here
example.com is used as an example domain

```
DOMAIN=example.com
EMAIL=your@email.com
CERT_RESOLVER=letsencrypt
```

## Common commands

```
# clear database and re-run migrations
php artisan migrate:fresh

# create a new model, controller and migration called Member
php artisan make:model -mrc Member

# start up dev environment
docker compose up -d

# stop environment
docker compose stop

# delete everything and start in a clean environment
docker compose down -v

# check logs
docker compose logs -f

# check logs for specific container
docker compose logs -f php

# log into php container (this will allow use php artisan)
docker compose exec php sh

# run tests
./vendor/bin/pest

# create pest test
php artisan make:test UserTest --pest

```

## Tips

Use bash aliases in your local dev

```
# docker compose aliases
alias dc="docker compose"
alias dup="docker compose up -d"
alias dlup="docker compose up -d && docker compose logs php"
alias dphp="docker compose exec php sh"
alias dnode="docker compose exec node bash"
alias dlnode="docker compose logs -f node"
alias dstop="docker compose stop"
```

## Resources

* [Jet stream (inertia-vue)](https://jetstream.laravel.com/2.x/introduction.html#inertia-vue)
* [Laravel vite](https://laravel.com/docs/10.x/vite)
* [Github Project](https://github.com/orgs/sita-samoa/projects/1)
* [Icons](https://pictogrammers.com/library/mdi/)
* [SSL support](https://github.com/bubelov/traefik-letsencrypt-compose)
* [Laravel Backup](https://github.com/spatie/laravel-backup)
