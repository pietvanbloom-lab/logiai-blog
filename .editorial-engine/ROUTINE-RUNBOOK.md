# Unattended Cloud Run Runbook (LogiAI Production Stack)

Rules for every SCHEDULED (cron/Routine) run of the production stack. These
override any operator-present behavior described in the orchestrator skill.
Interactive sessions with the operator present are NOT bound by this file.

## 1. Artifacts first — a run must always leave a trace

Immediately after Phase 0 preflight (before Phase 1):

1. Create branch `claude/logiai-run-YYYYMMDD` from latest `main`.
2. Write the run log skeleton to `runs/YYYY-MM-DD_HHMM.log` (config snapshot,
   mode, start time), commit, and push the branch.
3. Open a draft PR titled `run: LogiAI daily YYYY-MM-DD` (or update it if it
   exists from an earlier attempt the same day).

Then, at the END of each phase: append the phase result to the run log,
commit, push. The final commit adds the cap-history row to
`runs/cap-history.csv` and the structured run summary to the PR body.

This ordering guarantees that a crashed, capped, or blocked run still leaves
its log and partial results on GitHub.

## 2. No interactive prompts

Never call AskUserQuestion (or any blocking operator prompt) in a scheduled
session; nobody is present to answer and the run will end without output.

Every item that would require operator approval (draft ready to publish,
soft/hard escalation, tracker status flip, irreversible action) is instead
**parked**:

- Add it to a `## Parked for operator approval` section in the run log and
  the PR body, with everything the operator needs to decide.
- Take no write action on the item itself. Drafts may be saved to
  `workspace/` and committed to the run branch; they are NOT published.

## 3. Abort behavior

On any cap hit, loop detection, drift-guard mismatch, missing credential,
or broken repo state: write the reason to the run log, commit, push, update
the PR body with `ABORTED: <reason>`, then stop. Never exit silently.

## 4. Unchanged hard rules

- Never publish to WordPress from a scheduled run (park instead).
- Never push to `main` — all run output goes to the run branch and PR.
- Never delete files, never flip tracker statuses.
- No em-dashes in any generated content.

## 5. Known environment limits (state honestly in the run log)

- If the WordPress/Gmail connectors are absent in the session, note it and
  park anything that needs them.
- If the WP App Password is unavailable (no environment secret), the
  authenticated WP REST steps are parked; public read endpoints still work.
