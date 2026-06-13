---
<<<<<<< Updated upstream
description: Return to main branch and clean up the WIP doc
=======
description: Return to main branch and clean up the WIP doc 
>>>>>>> Stashed changes
---

# Finish Feature

Cleans up after a feature branch.

## Steps

1. The user runs `/finish-feature` (no arguments needed).

2. Remove the WIP doc:

```bash
rm docs/wip/<issue_number>-<short-description>.md
```

3. Stage all changes:

```bash
git add -A
```

4. Commit with the provided message:

```bash
git commit -m "<commit_message>"
```

5. Push the branch to origin:

```bash
git push -u origin HEAD
```

6. Detect the current branch name and extract the short description (e.g., `feature/42-dark-mode` → `dark-mode`).

7. Switch back to `main`:

```bash
git checkout main
```

8. Delete the local feature branch:

```bash
git branch -d feature/<issue_number>-<short-description>
```

9. Tell the user: "Branch cleaned up. WIP doc removed. You're back on `main`."
