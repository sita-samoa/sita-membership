# Prepare repository for production release using Caddy container

- **Issue:** #374
- **Branch:** `feature/374-caddy-prod-release`
- **Status:** In Progress

## Scope

Prepare the repository for production release using Caddy container.

## Tasks

- [x] Analyze current Caddy configuration and production setup
- [x] Review existing production deployment files (compose.prod.yml, Caddyfile, etc.)
- [x] Remove `on_demand_tls` block from Caddyfile (multi-tenant, not needed for single domain)
- [x] Add security headers to Caddyfile (HSTS, X-Frame-Options, X-Content-Type-Options, X-XSS-Protection, Referrer-Policy)
- [x] Add health check and resource limits to Caddy service in compose.prod.yml
- [x] Update .env.prod-sample: replace "TRAEFIK SSL" section with "CADDY SSL" and add `CADDY_TAG`
- [x] Update tech_guide.md: document security headers, health check, and add troubleshooting section
- [ ] Test production deployment with `make prod`
