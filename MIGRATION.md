# LogiAI Production Stack — GitHub Migration Guide

Goal: the entire LogiAI production stack (skills, config, memory, guards,
approval flow) runs from this repo, like the Business Intelligence Hub. A
cloud Routine clones the repo daily and executes the pipeline; your Mac no
longer needs to be on.

## What moved into the repo

| Repo location | Contents | Source |
|---|---|---|
| `.editorial-engine/portal.yaml` | Portal config (themes, sources, gates, adapter, approval) | new |
| `skills/logiai-*/` | The 11 LogiAI pipeline skills, paths rewritten to repo-relative | Cowork skills |
| `skills/logiai-production-stack/` | Orchestrator SKILL.md + config.yml | **placeholders — import from Mac** |
| `memory/logiai_published.md` | Publish history | **template — import from Mac** |
| `tracker/` | LogiAI Action Tracker.html | **empty — import from Mac** |
| `tools/` | editorial-visual-inline.py, visual-style-guard.py | **missing — import from Mac** |
| `runs/` | Run logs + cap-history.csv | fresh (old logs optional) |
| `workspace/` | Per-run working files (candidates, drafts, QC reports) | fresh |

Path convention: `<repo>/...` in the skills means "relative to the root of
this repository", wherever it is cloned.

## Step 1 — Import your Mac-local files (one-time, ~2 minutes)

Run in Terminal on the Mac (adjust the clone path if yours differs):

```bash
cd /path/to/your/clone/of/logiai-blog
git pull

LAB="/Users/maxxposs/Documents/Logistics LAB"
STACK="$HOME/Documents/Claude/Scheduled/logiai-production-stack"

# Orchestrator + runtime config (overwrite the placeholders)
cp "$STACK/SKILL.md"  skills/logiai-production-stack/SKILL.md
cp "$STACK/config.yml" skills/logiai-production-stack/config.yml

# Publish history + tracker
cp /path/to/memory/logiai_published.md   memory/logiai_published.md
cp "/path/to/LogiAI Action Tracker.html" "tracker/LogiAI Action Tracker.html"

# Publish guard scripts
cp "$LAB/editorial-visual-inline.py" tools/
cp "$LAB/visual-style-guard.py"      tools/

# Optional: historical run logs
cp -r "$STACK/runs/." runs/ 2>/dev/null || true

# Styleguide freshness: if the LAB master is newer than the repo copy
cp "$LAB/CONTENT-STYLEGUIDE.md" CONTENT-STYLEGUIDE.md

git add -A && git commit -m "import: Mac-local production stack state" && git push
```

After importing, open the copied `skills/logiai-production-stack/SKILL.md`
and `config.yml` and replace any remaining absolute Mac paths
(`/Users/maxxposs/...`, `~/Documents/Claude/...`) with the repo-relative
equivalents used everywhere else (`<repo>/...`, `runs/`, `memory/`,
`tracker/`). Asking Claude on the Mac to "rewrite local paths in these two
files to repo-relative" is enough. Also delete the IMPORT-ME notice block at
the top of the orchestrator placeholder if `cp` kept any of it.

## Step 2 — Enable the cloud Routine

A Claude Code Remote Routine named **"LogiAI Production Stack — Daily"** was
created **disabled**. It fires a fresh cloud session each day, clones this
repo, and runs the pipeline in `daily` mode with the hard-stop approval gate.

Enable it once Step 1 is done (say "enable the LogiAI daily routine" in any
Claude session, or use the Routines UI).

Schedule note: the Routine cron is in UTC. `0 5 * * *` = 07:00 CEST (summer).
For winter (CET), change it to `0 6 * * *`.

## Step 3 — Transition and retire the Mac task

1. Let the cloud Routine run 2-3 days in parallel with your judgement.
2. Verify: run log committed under `runs/`, drafts parked as WordPress drafts,
   approval requests arriving (Gmail label / inline), no auto-publishes.
3. Pause or delete the Cowork scheduled task
   `logiai-production-stack-daily` on the Mac.
4. From then on the Mac copies under `~/Documents/Claude/Scheduled/` and
   `Logistics LAB` are backups; the repo is the single source of truth.

## Hard rules (unchanged by the migration)

- NEVER auto-publish to WordPress. Every draft waits for operator approval.
- NEVER push to `main` without operator approval (runs commit to run branches
  or ask first).
- NEVER delete files or flip tracker statuses without confirmation.
- No em-dashes in any generated content.
- Publishing with editorial visuals requires `tools/editorial-visual-inline.py`
  and `tools/visual-style-guard.py` to be present and to exit 0.
