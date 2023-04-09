# SITA Membership database

SITA Membership database using Laravel 10 and Jet stream inertia (vuejs)

## Dev environment with [docker4drupal](https://github.com/wodby/docker4drupal/releases)

This repository has been set up to work with docker compose. You need docker
and docker compose to use the commands below.

```
# start up containers
docker compose up -d

# log in to php container
docker compose exec php sh

# switch to laravel folder
cd laravel

# run initialisation commands
php artisan migrate
php artisan key:generate
```

Once installed you can access the dev site on port 8000. e.g. localhost:8000 or
sita-membership.docker.localhost:8000

Click "Register" and register a new account.

Use it to log in.

**Common commands**

```
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


```

**Tips**

Use aliases

```
# docker compose aliases
alias dc="docker compose"
alias dup="docker compose up -d && docker compose logs php"
alias dphp="docker compose exec php sh"
alias dnode="docker compose exec node bash"
alias dstop="docker compose stop"
