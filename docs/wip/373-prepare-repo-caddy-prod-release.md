# Prepare repo for prod release by using Caddy container

- **Issue:** #373
- **Branch:** `feature/373-prepare-repo-caddy-prod-release`
- **Status:** Implemented

## Scope

Prepare the repository for production release by configuring the Caddy container as the reverse proxy.

## Tasks

- [x] Review and update Caddy configuration
- [x] Verify production Docker Compose setup
- [x] Test Caddy container locally with production overrides
- [x] Update documentation for production deployment

## Changes Made

### 1. Fixed Caddy Health Check

- Changed from `caddy version` (only checks binary exists) to `caddy validate --config /etc/caddy/Caddyfile`
- Now actually validates the Caddyfile configuration is valid

### 2. Added Proper Service Dependencies

- Added `depends_on` with `condition: service_started` for Caddy to ensure nginx starts first
- Updated crond dependencies to ensure mariadb and php are started first

### 3. Created Required Directory Structure

- Created `docker-init/data/caddy/` for persistent SSL certificate storage
- Fixed nested directory issue in `docker-init/config/caddy/`

### 4. Verified Production Deployment

- Successfully ran `make prod`
- All containers start correctly
- Caddy container reports healthy status
- SSL ports (80, 443) properly exposed

## Verification Results

| Component                   | Status                           |
| --------------------------- | -------------------------------- |
| Caddyfile syntax            | Valid                            |
| Caddy health check          | Healthy (uses config validation) |
| Container startup order     | Correct                          |
| SSL certificate persistence | Ready (data directory created)   |
| Production deployment       | Working                          |
