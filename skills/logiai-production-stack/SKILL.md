# LogiAI Production Stack — Main Orchestrator (IMPORT PENDING)

> **IMPORT-ME:** This is a placeholder. The real 8-phase orchestrator lives on
> the operator's Mac at
> `~/Documents/Claude/Scheduled/logiai-production-stack/SKILL.md`.
> Copy it over this file (see `MIGRATION.md`, step 1) and delete this notice.

Until the import happens, a run that reaches this file must fall back to the
generic orchestrator: `editorial-engine-daily-stack` with
`.editorial-engine/portal.yaml` as the active portal. The generic pipeline
covers strategy refresh, monitoring, scoring, verification, drafting,
quality-gate, and the hard-stop approval gate.

## Contract (kept identical after import)

- Phases 0-8, `daily` mode runs 0, 1, 2, 3, 4, 5, 7.
- Runtime config: `skills/logiai-production-stack/config.yml` (same folder).
- Run logs: `runs/YYYY-MM-DD_HHMM.log`, caps appended to `runs/cap-history.csv`.
- Publish history: `memory/logiai_published.md`.
- Tracker: `tracker/LogiAI Action Tracker.html`.
- Hard rules: never auto-publish, never push to main without approval, no
  em-dashes, no silent tracker status flips.
