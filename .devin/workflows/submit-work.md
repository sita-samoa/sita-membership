---
description: Commit staged changes, push, and create a pull request linking to the issue
---

# Submit Work

Commits current work, pushes the branch, and opens a pull request that references the issue. If there is nothing to commit, start at the PR creation step.

## Steps

1. The user provides a commit message (e.g., `/submit-work "feat: add dark mode toggle"`). The message must follow the Conventional Commits format defined in `agents.md`. If empty suggest one based on the changes.

2. Stage all changes:

```bash
git add -A
```

3. Commit with the provided message:

```bash
git commit -m "<commit_message>"
```

4. Push the branch to origin:

```bash
git push -u origin HEAD
```

5. Detect the issue number from the current branch name (the number after the prefix, e.g., `feature/42-dark-mode` → `42`).

6. Create the pull request using the script:

```bash
./scripts/gh-create-pr.sh <issue_number>
```

7. Tell the user: "PR created and linked to issue **#N**. Review at the URL from the output."
