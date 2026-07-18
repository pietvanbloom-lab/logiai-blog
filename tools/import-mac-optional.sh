#!/bin/bash
# One-shot import of the remaining optional Mac-local items into the repo.
# Run on the Mac, from the root of your logiai-blog clone:
#   bash tools/import-mac-optional.sh
set -e

LAB="/Users/maxxposs/Documents/Logistics LAB"
STACK="$HOME/Documents/Claude/Scheduled/logiai-production-stack"

[ -f .editorial-engine/portal.yaml ] || { echo "Run from the logiai-blog repo root."; exit 1; }
git pull

copied=""
missing=""

grab () {  # grab <source> <destination>
  if [ -e "$1" ]; then
    mkdir -p "$(dirname "$2")"
    cp -R "$1" "$2"
    copied="$copied\n  $2"
  else
    missing="$missing\n  $1"
  fi
}

# 1. Featured-image generation script (Phase 6)
grab "$LAB/fal_generate.py" "tools/fal_generate.py"

# 2. Editorial strategy context (Phase 0)
grab "$HOME/Documents/Claude/memory/logiai_project.md" "memory/logiai_project.md"
[ -e memory/logiai_project.md ] || grab "$LAB/memory/logiai_project.md" "memory/logiai_project.md"

# 3. Approval Board widget template (Phase 5, interactive sessions)
grab "$STACK/approval-board-template.html" "skills/logiai-production-stack/approval-board-template.html"

# 4. Historical run logs and KPI reports
grab "$STACK/runs/." "runs/"
grab "$LAB/reports/." "reports/"

echo "----------------------------------------"
[ -n "$copied" ]  && echo -e "Copied:$copied"
[ -n "$missing" ] && echo -e "NOT FOUND (adjust the path in this script and re-run):$missing"

git add -A
git commit -m "import: optional Mac-local items (fal_generate, project memory, approval board, history)"
git push
echo "Done. Optional items are now on GitHub."
