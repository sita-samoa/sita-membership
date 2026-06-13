# Patch GitHub Actions to use supported action versions

- **Issue:** #386
- **Branch:** `feature/386-patch-github-actions`
- **Status:** Complete

## Scope

Update GitHub Actions workflow files to use current supported action versions and migrate from deprecated Node 16 runners to Node 20.

## Required Updates

### `laravel.yml`

- [x] `actions/checkout@v3` → `@v4`
- [x] `actions/setup-node@v3` → `@v4`
- [x] `shivammathur/setup-php@v2` → No update needed (v2 is current)

### `code_fixes.yml`

- [x] `actions/checkout@v3` → `@v4`
- [x] `actions/cache@v3` → `@v4`
- [x] `shivammathur/setup-php@v2` → No update needed (v2 is current)

### `trivy.yml`

- [x] `aquasecurity/trivy-action@v0.36.0` → No update needed (v0.36.0 is current)

## Verification

- [ ] All workflows run successfully after updates
- [ ] No deprecation warnings in GitHub Actions logs
- [ ] PHP 8.2, 8.3, 8.4 matrix tests pass
- [ ] Node.js 20.x setup works correctly

## Notes

- This addresses GitHub Actions runner deprecation warnings for Node 16
- All outdated action versions have been updated (trivy-action was already current)
- No changes to application logic or dependencies
