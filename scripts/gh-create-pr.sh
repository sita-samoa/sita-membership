#!/usr/bin/env bash
#
# Create a GitHub pull request that links to an issue.
# Requires: gh (GitHub CLI, authenticated)
#
# Usage:
#   ./scripts/gh-create-pr.sh <issue_number>
#
# The PR title is derived from the most recent commit message.
# The PR body includes "Closes #<issue_number>" to auto-close the issue on merge.

set -euo pipefail

ISSUE="${1:?Usage: gh-create-pr.sh <issue_number>}"

if ! command -v gh &>/dev/null; then
  echo "Error: 'gh' (GitHub CLI) is required but not installed." >&2
  exit 1
fi

PR_TITLE=$(git log -1 --pretty=%s)
PR_BODY="Closes #${ISSUE}"

gh pr create --title "$PR_TITLE" --body "$PR_BODY"
