# Temporary npm security exceptions

Accepted on 2026-10-09. Review by **2026-11-08** (Pacific/Fiji).
The CI filter expires on that date, using UTC for its date comparison.

| Advisory | Root package | Affected dependency paths |
| --- | --- | --- |
| [GHSA-vfj7-8cjw-p6xm](https://github.com/advisories/GHSA-vfj7-8cjw-p6xm) | `braces` | `chokidar`, `micromatch`, `fast-glob`, Tailwind CSS, Flowbite-Vue |
| [GHSA-rj75-hqrm-r3gf](https://github.com/advisories/GHSA-rj75-hqrm-r3gf) | `postcss-selector-parser` | `postcss-nested`, `@tailwindcss/typography`, Tailwind CSS, Flowbite-Vue |

## Reason for deferral

Production serves compiled JavaScript and CSS. These dependency paths belong
to the Tailwind build pipeline. The remaining advisories concern denial of
service when processing malicious glob patterns or CSS selectors. The current
Tailwind configuration uses repository-controlled glob patterns and sources.
This exception assumes build tooling does not process untrusted CSS or patterns
and does not run in a production request path; this is not a deployment audit.

`braces` currently has no patched release. The selector parser is fixed in
7.1.6, but Tailwind 3 and its plugins require older major versions. Deferral
allows compatibility validation before overrides or a Tailwind migration.

## Scope and review

`scripts/npm-audit.cjs` runs the full audit and filters only the two advisory
IDs above, including packages whose entire vulnerability chain leads to them.
Other findings, expired exceptions, and audit errors fail the check. The raw
report is retained as a CI artifact. No packages or severity levels are globally
excluded. Run locally from `laravel` with `npm run audit:security`.

Review patches and dependency paths by the date above, or earlier if the build
starts consuming untrusted input or production starts executing this tooling.
Remove resolved exceptions; extend an exception only after reassessing exposure
and updating both this document and the script's review date.
