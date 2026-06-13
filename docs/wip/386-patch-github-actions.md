# Patch GitHub Actions to use supported action versions

- **Issue:** #386
- **Branch:** `feature/386-patch-github-actions`
- **Status:** In Progress

## Scope

Update GitHub Actions workflow files to use current supported action versions and migrate from deprecated Node 16 runners to Node 20.

## Required Updates

### `laravel.yml`

- [ ] `actions/checkout@v3` → `@v4`
- [ ] `actions/setup-node@v3` → `@v4`
- [ ] `shivammathur/setup-php@v2` → `@v3`

### `code_fixes.yml`

- [ ] `actions/checkout@v3` → `@v4`
- [ ] `actions/cache@v3` → `@v4`
- [ ] `shivammathur/setup-php@v2` → `@v3`

### `trivy.yml`

- [ ] `aquasecurity/trivy-action@v0.36.0` → `@v0.30.0` (or current version)

## Verification

- [ ] All workflows run successfully after updates
- [ ] No deprecation warnings in GitHub Actions logs
- [ ] PHP 8.2, 8.3, 8.4 matrix tests pass
- [ ] Node.js 20.x setup works correctly

## Notes

- This addresses GitHub Actions runner deprecation warnings for Node 16
- All current action versions are outdated and need updating
- No changes to application logic or dependencies
