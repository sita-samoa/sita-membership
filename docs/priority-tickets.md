# Priority Tickets

Prioritisation is based on ticket descriptions, not a fresh code audit.

## P0 — Immediate risk

1. [#431 Remove tracked secrets and environment files](https://github.com/sita-samoa/sita-membership/issues/431) — Potential credential exposure requires immediate audit and rotation.
2. [#429 Harden member resource authorization](https://github.com/sita-samoa/sita-membership/issues/429) — Identified authorization gaps may expose or alter another member's data.
3. [#430 Make membership transitions atomic and idempotent](https://github.com/sita-samoa/sita-membership/issues/430) — Prevents duplicate invoices and inconsistent membership records.
4. [#176 Member details get deleted when user acct is deleted](https://github.com/sita-samoa/sita-membership/issues/176) — Account deletion can destroy organisational records.
5. [#209 Require users with admin role to enable MFA](https://github.com/sita-samoa/sita-membership/issues/209) — Reduces the impact of compromised privileged accounts.
6. [#168 Store backups in S3](https://github.com/sita-samoa/sita-membership/issues/168) — Off-site backups are essential for recovery; verify current backup coverage first.

## P1 — Reliability and core operations

7. [#432 Repair contributor setup and project documentation](https://github.com/sita-samoa/sita-membership/issues/432) — Broken commands and conflicting documentation obstruct all development.
8. [#433 Add deterministic CI and frontend test coverage](https://github.com/sita-samoa/sita-membership/issues/433) — Makes subsequent security and workflow changes safer.
9. [#280 Require student ID supporting document](https://github.com/sita-samoa/sita-membership/issues/280) — Closes a membership eligibility-verification gap.
10. [#293 Auto Accept Endorsed Student Membership](https://github.com/sita-samoa/sita-membership/issues/293) — Removes an unnecessary manual step; implement through #430's transition model.
11. [#215 Hide sub reminder for free memberships](https://github.com/sita-samoa/sita-membership/issues/215) — Prevents incorrect payment communication.
12. [#189 Record payment receipts](https://github.com/sita-samoa/sita-membership/issues/189) — Improves payment reconciliation and auditability.
13. [#117 Assign New User to Existing Membership](https://github.com/sita-samoa/sita-membership/issues/117) — Resolves ownership of coordinator-created membership records.
14. [#228 Coordinator application reminders](https://github.com/sita-samoa/sita-membership/issues/228) — Reduces delays in application acceptance.
15. [#227 Executive endorsement reminders](https://github.com/sita-samoa/sita-membership/issues/227) — Reduces delays earlier in the approval pipeline.

## P2 — Valuable product improvements

16. [#214 Student signup enhancements](https://github.com/sita-samoa/sita-membership/issues/214) — Simplifies the student journey and complements #293.
17. [#182 Select preferred mailing/invoice details](https://github.com/sita-samoa/sita-membership/issues/182) — Improves communication and invoice accuracy.
18. [#165 Email Preference for Mailing List](https://github.com/sita-samoa/sita-membership/issues/165) — Useful, but likely duplicate scope within #182.
19. [#226 Profile completion reminders](https://github.com/sita-samoa/sita-membership/issues/226) — Could increase completed applications.
20. [#205 Xero integration](https://github.com/sita-samoa/sita-membership/issues/205) — Potentially valuable automation, but large and dependent on stable invoicing.
21. [#233 Member directory](https://github.com/sita-samoa/sita-membership/issues/233) — Provides member value but needs privacy requirements.
22. [#129 Display all member information](https://github.com/sita-samoa/sita-membership/issues/129) — Operationally useful, subject to strict authorization.
23. [#241 Configurable dashboard calculation period](https://github.com/sita-samoa/sita-membership/issues/241) — Improves reporting accuracy.
24. [#218 Sort users by creation date](https://github.com/sita-samoa/sita-membership/issues/218) — Small administrative efficiency improvement.
25. [#170 Filter verified/unverified users](https://github.com/sita-samoa/sita-membership/issues/170) — Helps administrators manage onboarding.
26. [#169 Notify admin after verification](https://github.com/sita-samoa/sita-membership/issues/169) — Supports onboarding but may add notification noise.
27. [#272 Add skills to membership details](https://github.com/sita-samoa/sita-membership/issues/272) — Promising directory feature, but requirements are unresolved.
28. [#271 Display member avatars](https://github.com/sita-samoa/sita-membership/issues/271) — Improves presentation but introduces storage/privacy considerations.
29. [#283 Confirm subscription reminder](https://github.com/sita-samoa/sita-membership/issues/283) — Small safeguard against accidental emails.
30. [#173 Explain membership types](https://github.com/sita-samoa/sita-membership/issues/173) — Low-cost signup usability improvement.
31. [#107 Document application statuses](https://github.com/sita-samoa/sita-membership/issues/107) — Helps users understand the workflow after #430 defines it.
32. [#193 Link to SITA regulations](https://github.com/sita-samoa/sita-membership/issues/193) — Small but important terms-and-conditions clarification.

## P3 — Consolidate, clarify, or close

- [#403 Update `.env` versions](https://github.com/sita-samoa/sita-membership/issues/403) — No description; clarify and likely merge into #431/#432.
- [#199 Upgrade to Laravel 11](https://github.com/sita-samoa/sita-membership/issues/199) — Obsolete because the project is already on Laravel 12.
- [#291 Contributing question](https://github.com/sita-samoa/sita-membership/issues/291) and [#243 contribution documentation](https://github.com/sita-samoa/sita-membership/issues/243) — Answer/close via #432.
- [#288 coverage](https://github.com/sita-samoa/sita-membership/issues/288), [#160 E2E tests](https://github.com/sita-samoa/sita-membership/issues/160), and [#65–70 UI/workflow tests](https://github.com/sita-samoa/sita-membership/issues/70) — Consolidate under #433, preserving their acceptance criteria.
- [#269 Production upload configuration](https://github.com/sita-samoa/sita-membership/issues/269) — Reassess the proposed 1 GB limit for security and operational impact.
- [#164 status components](https://github.com/sita-samoa/sita-membership/issues/164) — Maintenance improvement with little immediate user impact.
- [#267 SCH footer link](https://github.com/sita-samoa/sita-membership/issues/267), [#242 guide visuals](https://github.com/sita-samoa/sita-membership/issues/242), [#190 whitespace](https://github.com/sita-samoa/sita-membership/issues/190), [#128 name capitalization](https://github.com/sita-samoa/sita-membership/issues/128), [#121 toasts](https://github.com/sita-samoa/sita-membership/issues/121), [#85 breadcrumbs](https://github.com/sita-samoa/sita-membership/issues/85), and [#42 welcome-page change](https://github.com/sita-samoa/sita-membership/issues/42) — Low-risk UX work; schedule after core risks or use as onboarding tasks.
