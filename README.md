# SITA Membership database

SITA Membership database using Laravel 10 and Jet stream inertia (vuejs)

## Dev environment with [docker4drupal](https://github.com/wodby/docker4drupal/releases)

This repository has been set up to work with docker compose. You need docker
and docker compose to use the commands below.

```
docker-compose up -d
```

Once installed you can access the dev site on port 8000. e.g. localhost:8000 or
sita-membership.docker.localhost:8000

**Common commands**

```
# start up dev environment
docker-compose up -d

# stop environment
docker-compose stop

# delete everything and start in a clean environment
docker-compose down -v

# check logs
docker-compose logs -f

# check logs for specific container
docker-compose logs -f php

# log into php container (this will allow use of drush and composer)
docker-compose exec php sh
