---
description: Create a GitHub issue and immediately set up the feature branch and WIP doc
---

# New Feature

Combines `/create-ticket` and `/start-feature` into one flow. All logic lives in those workflows — this one only orchestrates the handoff.

## Steps

1. The user provides a short title/description (e.g., `/new-feature "Add dark mode toggle"`).

2. Execute the full `/create-ticket` workflow using the provided title. Note the issue number from the output.

3. Without waiting for user input, immediately execute the full `/start-feature` workflow using the issue number from step 2.
