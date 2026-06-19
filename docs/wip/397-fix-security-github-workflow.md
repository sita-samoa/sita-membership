# fix security github workflow

- **Issue:** #397
- **Branch:** `feature/397-fix-security-github-workflow`
- **Status:** In Progress

## Scope

Security workflow should fail when a package requires a security update.

## Tasks

- [x] Inspect the current security workflow to see why it passes on security advisories.
- [x] Update the workflow to exit non-zero when `composer audit` or `npm audit` reports vulnerabilities.
- [x] Verify the YAML remains valid.
