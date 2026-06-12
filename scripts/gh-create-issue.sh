#!/usr/bin/env bash
#
# Create a GitHub issue in the current repository.
# Requires: gh (GitHub CLI, authenticated)
#
# Usage:
#   ./scripts/gh-create-issue.sh "Issue title"
#   ./scripts/gh-create-issue.sh "Issue title" "Optional body text"

set -euo pipefail

TITLE="${1:?Usage: gh-create-issue.sh \"<title>\" [\"<body>\"]}"
BODY="${2:-}"

if ! command -v gh &>/dev/null; then
  echo "Error: 'gh' (GitHub CLI) is required but not installed." >&2
  exit 1
fi

if [ -n "$BODY" ]; then
  gh issue create --title "$TITLE" --body "$BODY"
else
  gh issue create --title "$TITLE" --body ""
fi
