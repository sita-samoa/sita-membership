# Preserve Membership Records When Users Are Deleted

## Context

GitHub issue [#176](https://github.com/sita-samoa/sita-membership/issues/176) reports that deleting a user account also deletes the associated membership data. The current `members.user_id` foreign key uses `ON DELETE CASCADE`, and both the Jetstream self-service deletion action and the administrator user controller ultimately perform a hard delete.

The selected retention policy is to **soft-delete the user**. The user row and its foreign-key links remain in the database, while Laravel treats the account as deleted for authentication and normal user queries. Membership records and their dependent records must remain intact.

## Requirements

### Functional requirements

1. Self-service account deletion must soft-delete the user instead of removing the database row.
2. Administrator-initiated user deletion must use the same soft-deletion lifecycle as self-service deletion.
3. Deleting a user must preserve every membership owned by that user, including qualifications, referees, work experience, supporting documents, mailing preferences, membership-status history, rejection history, invoices, and audits.
4. A deleted user must no longer appear in normal user queries, the user-management list, team membership, or authentication results.
5. Existing personal access tokens must be revoked, profile-photo cleanup must still occur, and team associations/owned personal teams must be cleaned up consistently for both deletion entry points.
6. Active code paths must treat a soft-deleted membership owner as unavailable: they must not throw a null-reference error or enqueue a new notification to that account.
7. A membership-status history entry must retain its `user_id` link to a soft-deleted actor and be able to resolve that actor for historical/audit display when explicitly requested.
8. Already-queued membership notifications must decline delivery if their notifiable user has been soft-deleted before the worker handles them.
9. The deleted user's email remains reserved by the existing unique constraint. Registration, restoration, permanent deletion, anonymisation, and reassignment of an old membership are outside this change.

### Non-functional requirements

- The deletion operation must remain transactional so partial token, team, photo, or account cleanup cannot leave an inconsistent result.
- No live credentials or personal data may be added to fixtures or committed files.
- The change must work with the repository's supported MySQL/MariaDB database and its test database configuration.
- Existing behavior for active users must remain unchanged.

## Constraints

- Use PHP 8.2+ and Laravel 12 conventions.
- Add a new migration; do not edit `2014_10_12_000000_create_users_table.php` or any other existing migration.
- Do not modify `compose.yml`, `compose.prod.yml`, or `Caddyfile`.
- Keep existing seeder class names unchanged.
- Preserve the existing `members.user_id` and `member_membership_statuses.user_id` foreign keys. Soft deletion deliberately avoids triggering their hard-delete behavior.
- Do not add a restore or force-delete endpoint as part of this issue.
- Do not expose soft-deleted users through ordinary `User` queries or authentication.
- Keep `docs/priority-tickets.md` in the working tree; it is intentional and unrelated to the application fix.

## Architecture

### Persistence

Add an append-only migration that adds a nullable `deleted_at` timestamp and index to the `users` table through Laravel's `softDeletes()` schema helper. Its rollback removes that column. No membership foreign key needs to be rewritten because a soft delete is an `UPDATE`, not a database `DELETE`.

Add Laravel's `SoftDeletes` trait to `App\Models\User`. The trait's global scope ensures normal queries and the custom Fortify authentication lookup exclude deleted accounts automatically. `withTrashed()` must only be used for explicit retention assertions or historical/audit relationships.

### Deletion lifecycle

Keep `App\Actions\Jetstream\DeleteUser` as the single account-deletion service. It remains responsible for the transaction, team cleanup, profile-photo cleanup, token revocation, and the final `$user->delete()`, which becomes a soft delete after the model change.

Change `UserController::destroy()` to delegate to that same deletion service after its existing demo-user, super-user, and authorization checks. This removes the behavioral difference between administrator deletion and self-service deletion.

### Relationships and runtime behavior

Keep `Member::user()` scoped to active users. After deletion it should return `null`, preventing deleted accounts from being treated as active owners. Member records remain available to authorized administrators using the member's own stored identity fields.

Update `MemberMembershipStatus::user()` to include soft-deleted users with `withTrashed()`. This relationship represents the historical actor, not a currently active notification recipient.

Centralize notification eligibility rather than scattering unchecked `member->user->notify(...)` calls:

- Guard controller, repository, scheduled-reminder, and invoice-job notification dispatch when the membership has no active user.
- Add a small reusable notification concern for user-directed queued notifications whose `via()` method returns no channels when the notifiable user is soft-deleted. Apply it to membership notifications that can outlive the account through the queue.
- Continue generating and retaining membership/invoice records when required by the business workflow; only delivery to the deleted account is suppressed.

## Implementation Steps

1. Create a migration adding `deleted_at` to `users`, with a reversible `down()` method.
2. Add `SoftDeletes` to `User` and confirm ordinary queries plus Fortify's custom authentication callback exclude deleted rows.
3. Inject or resolve the shared Jetstream deletion action in `UserController::destroy()` and use it instead of calling `User::delete()` directly.
4. Preserve active-owner semantics on `Member::user()` and allow `MemberMembershipStatus::user()` to resolve soft-deleted historical actors.
5. Add null/active-user guards to direct membership notification paths in `MemberController`, `MemberMembershipStatusRepository`, and `ProcessInvoice`.
6. Add and apply a reusable queued-notification guard so a notification queued before deletion is skipped after deletion.
7. Expand `DeleteAccountTest` to assert the user is soft-deleted, membership records and dependent records survive, foreign-key identifiers are unchanged, and cleanup still occurs.
8. Expand `UserManagerTest` to cover administrator soft deletion, consistent cleanup, hidden deleted users, and preserved memberships.
9. Add focused tests showing that deleted users cannot authenticate, are absent from normal user lists, remain resolvable through explicit `withTrashed()`/history access, and receive neither new nor already-queued membership notifications.
10. Run the full test and lint workflows and address regressions without changing unrelated infrastructure.

## Test Scenarios

- A user without a membership can delete their own account and becomes soft-deleted.
- A user with one or more memberships can delete their account; all memberships and representative dependent records remain unchanged.
- A user who previously created a membership-status history row can be deleted without a foreign-key failure, and the history row still resolves its deleted actor explicitly.
- An administrator can delete another user through `/users/{user}` with the same retention and cleanup behavior.
- A soft-deleted user is absent from `User::all()`, user-management pagination, team membership, and login authentication.
- The deleted row is present through `User::withTrashed()` and reports `trashed() === true`.
- API tokens and team links owned by the deleted user no longer grant access or appear active.
- Membership actions and scheduled reminder processing do not fail when the member owner is deleted and do not dispatch mail to that owner.
- A queued membership notification created before deletion produces no delivery channels after the recipient is deleted.
- Active users continue to authenticate, appear in lists, own memberships, and receive notifications as before.
- Incorrect-password account deletion still leaves the user and membership active.
- Demo-user and super-user administrator deletion protections remain effective.

## Success Criteria

- Account deletion sets `users.deleted_at` and does not physically remove the user row.
- No membership or membership-dependent record is deleted as a consequence of account deletion.
- Both self-service and administrator deletion follow one transactional cleanup path.
- Deleted accounts cannot authenticate, use API tokens, remain on teams, appear in standard user listings, or receive membership notifications.
- Membership history remains queryable and can identify a soft-deleted actor through its explicit historical relationship.
- The new focused tests pass.
- `make composer test` passes in full.
- `make composer lint` passes without introducing unrelated formatting changes.
- `git diff --check` reports no whitespace errors.

## Risks and Mitigations

- **Retained personal data:** Soft deletion intentionally retains account and membership data. Mitigate by keeping deleted users hidden by default and treating anonymisation/permanent erasure as a separate policy-driven feature.
- **Queued mail after deletion:** Laravel may restore serialized models without normal scopes. Mitigate at delivery time by returning no notification channels for a trashed notifiable.
- **Inconsistent admin deletion:** The current administrator path bypasses Jetstream cleanup. Mitigate by routing both entry points through the shared deletion action.
- **Accidental reactivation:** Do not use `withTrashed()` in authentication or ordinary user/member ownership queries. Restrict it to explicit audit/history behavior.
- **Email reuse ambiguity:** The unique email remains reserved. Document this behavior and defer restoration or replacement-account policy to a separate issue.
