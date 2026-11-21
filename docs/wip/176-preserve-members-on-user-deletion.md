# Member details get deleted when user acct is deleted

- **Issue:** #176
- **Branch:** `feature/176-preserve-members-on-user-deletion`
- **Status:** In Progress

## Scope

Preserve member details when a user deletes their account by retaining the existing soft deletion behavior and adding regression coverage for self-service account deletion. Retained members keep their original `user_id`.

## Selected Approach

The `User` model already uses `SoftDeletes`, backed by the existing `2026_09_18_070000_add_deleted_at_to_users_table.php` migration. Both self-service deletion and administrator deletion use the Jetstream `DeleteUser` action, which soft deletes the user while cleaning up teams, profile photos and API tokens.

No production code, public interface or schema changes are required. The existing soft deletion migration must be deployed. Permanent deletion, restoration, reassignment and notification handling are outside this change.

## Tasks

- [x] Review account deletion and the member–user relationship, including database constraints.
- [x] Choose existing soft deletion, retaining the original member ownership.
- [x] Verify the selected approach is already implemented; no new migration is needed.
- [x] Add self-service deletion regression coverage for two members, their unchanged details and ownership, and a supporting document record and file using fake storage.
- [x] Strengthen incorrect-password coverage to verify validation errors, continued authentication, an active account and unchanged member data.
- [x] Run focused account deletion, user management and authentication tests.
- [x] Run `make composer test` successfully.
- [ ] Obtain a passing `make composer lint` result; existing issues outside this change block it.

## Validation Results

Validated on 2026-10-13 using the PHP container and the existing `mariadb_testing` service, which was started for these checks.

- Focused tests: **25 passed**, 143 assertions.
- `make composer test`: **183 passed, 5 skipped**, 659 assertions. Skips cover disabled API features, a registration configuration scenario and an existing TODO.
- `make composer lint`: Pint passed across 228 files. PHPCS reported no errors but stopped on three existing line-length warnings in `MemberController.php` (lines 348 and 365) and `SubReminderTest.php` (line 123). The modified account deletion tests passed the style checks.
- Separate `vendor/bin/rector --dry-run`: reported an existing missing `declare(strict_types=1)` in `app/Notifications/Concerns/SkipsDeletedUsers.php`. No files were changed by this check.

The logout assertions explicitly target Fortify's `web` session guard, since the request guard can retain a cached user within the test request.
