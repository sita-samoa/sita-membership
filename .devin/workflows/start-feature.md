---
description: Create a branch from a ticket ID, create a WIP doc, and prepare a plan
---

# Start Feature

Sets up the working environment for a new feature tied to a GitHub issue.

## Steps

1. The user provides the issue number (e.g., `/start-feature 42`).

2. Fetch the issue title to derive the branch name:
   // turbo

```bash
gh issue view <issue_number> --json title --jq '.title'
```

3. Derive a kebab-case branch name from the issue title using the format: `feature/<issue_number>-<short-description>` (2–6 words, lowercase, hyphens only). Follow the branch naming rules in `agents.md`.

4. Create and switch to the new branch:

```bash
git fetch origin
git checkout -b feature/<issue_number>-<short-description> origin/main
```

5. Create a WIP doc at `docs/wip/<issue_number>-<short-description>.md` with this template:

```markdown
# <Issue title>

- **Issue:** #<issue_number>
- **Branch:** `feature/<issue_number>-<short-description>`
- **Status:** In Progress

## Scope

<Brief description from the issue>

## Tasks

- [ ] ...
```

6. Tell the user: "Branch `feature/<N>-<desc>` created. WIP doc at `docs/wip/<desc>.md`. Ready to work."
