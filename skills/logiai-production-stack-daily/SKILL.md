---
name: logiai-production-stack-daily
description: Daily run of the LogiAI production stack at 07:00 CET. Action Tracker triage + editorial pipeline + memory hygiene. Hard-stops before publish for operator approval.
cron: 0 7 * * *
mode: daily
---

# LogiAI Production Stack — Daily Run

This is the scheduled wrapper. The actual logic lives in `<repo>/skills/logiai-production-stack/SKILL.md` and `config.yml`.

## What to Do When This Task Fires

Run the LogiAI Production Stack in `daily` mode.

**Required reading first (in order):**

1. `<repo>/skills/logiai-production-stack/config.yml` — runtime config: model assignments per phase, hard caps, drift guard, loop detection. Parse caps for `daily` mode.
2. `<repo>/skills/logiai-production-stack/SKILL.md` — full 8-phase orchestrator instructions.

**Mode:** `daily` (run phases 0, 1, 2, 3, 4, 5, 7; skip 6 unless operator approves in Phase 5; skip 8 performance review).

**Hard rules (NEVER violate):**

- NEVER auto-publish to WordPress. Hard-stop in Phase 5 with AskUserQuestion for every draft.
- NEVER git push to main without operator approval.
- NEVER delete files without explicit confirmation.
- NEVER flip an Action Tracker task to status "Done" without operator approval.
- NO em-dashes in any generated content (per global CLAUDE.md rule).

**Cap enforcement:**

Track cumulative tool calls, wall-clock seconds, estimated USD per phase and total. Use the model assignment from `config.yml > phase_models`. If any cap from `config.yml > caps.daily` is hit, stop the current phase, save state, log to `runs/{date}_{time}.log`, surface a summary to operator.

**Goal-drift guard:**

Before each phase, write a 1-2 sentence self-check to the run log: `Phase {N}: goal='...', current_action='...', aligned=yes/no`. If `no`, stop and escalate.

**Loop detection:**

If the same tool with substantially the same arguments fires more than 4 times within a phase, abort the phase and log to `{date}_drift.log`.

**Outputs each run:**

1. Run log at `<repo>/runs/YYYY-MM-DD_HHMM.log`
2. Cap history row appended to `<repo>/runs/cap-history.csv`
3. Updated `memory/logiai_published.md` for any newly published articles
4. Updated `LogiAI Action Tracker.html` for any tracker-task status changes the operator approved
5. Structured chat summary using the format defined in main SKILL.md

**Phase 5 approval gate:**

Use `AskUserQuestion` to present every draft ready-for-publish, every soft-escalated draft, and every proposed Action Tracker status flip. No write actions proceed without explicit operator answer.

Start with Phase 0 (Pre-flight). Read config.yml first. Proceed through phases as defined in main SKILL.md.
