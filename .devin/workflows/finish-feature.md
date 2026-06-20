---
description: Remove WIP doc and push the feature branch for review
---

# Finish Feature

Prepares a feature branch for final review and merge.

## Steps

1. The user runs `/finish-feature` (no arguments needed).

2. Detect the current branch name and extract the short description:

```bash
BRANCH=$(git branch --show-current)
SHORT_DESC=$(echo "$BRANCH" | sed 's/^[^/]*\///')
```

3. Remove the WIP doc:

```bash
WIP_DOC="docs/wip/${SHORT_DESC}.md"
git rm "$WIP_DOC"
```

4. Generate a conventional commit message and commit the WIP doc removal:

```bash
COMMIT_MESSAGE="chore: finish ${SHORT_DESC}"
git commit -m "$COMMIT_MESSAGE"
```

5. Push the branch to origin:

```bash
git push -u origin HEAD
```

6. Build a link to the PR or compare page:

```bash
PR_URL=$(gh pr view --json url --jq .url 2>/dev/null)
if [ -z "$PR_URL" ]; then
  REPO_URL=$(git remote get-url origin | sed 's/\.git$//' | sed 's/^git@github.com:/https:\/\/github.com\//')
  PR_URL="${REPO_URL}/compare/main...${BRANCH}"
fi
```

7. Tell the user: "WIP doc removed and pushed to $BRANCH. Review the PR at $PR_URL, squash & merge, and close it. Then run `/cleanup-branch` to delete the local branch."
