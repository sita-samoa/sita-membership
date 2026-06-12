---
description: Create a GitHub issue from a brief description
---

# Create Ticket

Creates a new GitHub issue in the current repository with a structured body.

## Steps

1. The user provides a short title/description for the issue (e.g., `/create-ticket "Add dark mode toggle"`).

2. Run the issue creation script:
   // turbo

```bash
./scripts/gh-create-issue.sh "<title>"
```

3. Confirm the issue was created and note the issue number from the output (e.g., `#42`).

4. Tell the user: "Issue **#N** created. Run `/start-feature N` to begin work."
