# Add security audit workflow for Composer and npm dependencies

- **Issue:** #388
- **Branch:** `feature/388-security-audit-workflow`
- **Status:** Implemented

## Scope

Create a new GitHub Actions workflow to run security audits on Composer (PHP) and npm (Node.js) dependencies. The workflow should run on push to `main` and on all pull requests, but should not block the CI pipeline.

## Background

Currently, the repository has no explicit security vulnerability checks for dependencies:

- `laravel.yml` — runs tests only
- `code_fixes.yml` — code formatting only
- `trivy.yml` — filesystem vulnerability scan (may catch some issues indirectly)

Missing: Direct `composer audit` and `npm audit` checks against known security advisories.

## Required Implementation

### Create `.github/workflows/security.yml`

- [x] Create new workflow file
- [x] Add `composer-audit` job (runs `composer audit --format=table`)
- [x] Add `npm-audit` job (runs `npm audit --audit-level=moderate`)
- [x] Configure to run on push to `main`
- [x] Configure to run on PRs to `main`
- [x] Set `continue-on-error: true` for both jobs (non-blocking)
- [x] Use `working-directory: ./laravel` for all steps
- [x] Use current action versions (`actions/checkout@v4`, `actions/setup-node@v4`, `shivammathur/setup-php@v2`)

## Verification

- [ ] Workflow file is valid YAML
- [ ] Workflow triggers correctly on PR
- [ ] `composer audit` runs and outputs results
- [ ] `npm audit` runs and outputs results
- [ ] Jobs do not block pipeline on failure

## Notes

- Jobs should be independent/parallel for faster execution
- Audit output should be visible in GitHub Actions logs
- No application code changes required
- Non-blocking by design — alerts developers without stopping merges
