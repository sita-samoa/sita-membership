WIP: Laravel CI / Node matrix summary

Summary

- Goal: run Node on multiple versions similar to the existing PHP matrix.
- Decision made: implement option 1 — add Node version matrix entries to the existing tests job.

What was changed

- Added node: [20, 22, 24] to the workflow matrix.
- Updated the job name to include both PHP and Node versions.
- Changed actions/setup-node to use node-version: ${{ matrix.node }}.

Checklist

- [x] Reviewed current .github/workflows/laravel.yml
- [x] Added Node versions 20, 22, 24 to the matrix
- [x] Updated setup-node to use matrix.node
- [x] Confirmed job name reflects PHP / Node matrix

Current status

- The tests job now runs for every combination of PHP (8.2, 8.3, 8.4) and Node (20, 22, 24).
- No caching or matrix filters added yet.

Next steps (suggested)

- Add dependency caching for Composer and npm to speed up runs.
- Consider separate Node-only job if PHP×Node combinations become too slow or unnecessary.
- Optionally use matrix include/exclude to limit combinations.
- Add matrix documentation in the workflow file for future maintainers.

Quick local run commands

- Install PHP and Node locally, then from the repo root:
  - cd laravel
  - composer install
  - npm ci
  - npm run build
  - php artisan key:generate
  - vendor/bin/pest

Notes

- This is a work-in-progress summary. Update this file with decisions or follow-up actions as you iterate on the CI workflow.
