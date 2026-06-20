---
description: Delete the local branch after the PR has been merged
---

# Cleanup Branch

Deletes the local feature branch after its PR has been merged on GitHub.

## Steps

1. The user runs `/cleanup-branch` (no arguments needed).

2. Detect the current branch name:

```bash
BRANCH=$(git branch --show-current)
```

3. If the current branch is `main`, abort and tell the user to switch to the feature branch first or provide the branch name:

```bash
if [ "$BRANCH" = "main" ]; then
  echo "You are on main. Switch to the feature branch you want to delete, or provide the branch name."
  exit 1
fi
```

4. Switch to `main` and update it:

```bash
git checkout main
git pull origin main
```

5. Verify the feature branch is merged before deleting it:

```bash
if git branch --merged main | grep -q "$BRANCH"; then
  git branch -d "$BRANCH"
else
  echo "Branch $BRANCH is not merged into main yet. Delete it manually with git branch -D $BRANCH if you are sure."
fi
```

6. Tell the user: "Branch $BRANCH deleted. You're back on main."
