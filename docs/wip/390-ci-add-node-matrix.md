# CI: add Node matrix (20,22,24) to laravel.yml

- **Issue:** #390
- **Branch:** `feature/390-ci-add-node-matrix`
- **Status:** In Progress

## Scope

Add Node versions 20, 22 and 24 to the existing GitHub Actions workflow's matrix so tests run across PHP × Node combinations. Consider caching Composer and npm later.

## Tasks

- [ ] Confirm CI run times are acceptable (9 matrix jobs expected)
- [ ] Add Composer & npm caching
- [ ] Add matrix include/exclude or separate Node job if needed
- [ ] Open PR linking to issue #390

## Notes

Derived from issue title and local changes. Files adjusted to allow branch checkout: moved some untracked WIP files out of .github/workflows to `wip/`.
