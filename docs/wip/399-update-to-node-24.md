# Update to Node 24

- **Issue:** #399
- **Branch:** `feature/399-update-to-node-24`
- **Status:** In Progress

## Scope

Upgrade the project's Node.js version requirement from the current version to Node 24 across all relevant configuration files (`.nvmrc`, `package.json` engines, Docker/compose files, CI workflows, etc.).

## Tasks

- [ ] Identify current Node version constraints across all config files
- [ ] Update `.nvmrc` or `.node-version` to `24`
- [ ] Update `engines.node` in `laravel/package.json`
- [ ] Update Node version in Docker/compose files
- [ ] Update Node version in GitHub Actions CI workflows
- [ ] Update Tugboat config if applicable
- [ ] Run `npm install` and resolve any peer dependency issues
- [ ] Run full test suite and linting to confirm no regressions
