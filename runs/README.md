# Run logs

Each daily/weekly production run writes:

- `YYYY-MM-DD_HHMM.log` — phase-by-phase run log with goal-drift self-checks
- `cap-history.csv` — one row per run: tool calls, wall-clock, USD estimate
- `YYYY-MM-DD_drift.log` — only when loop detection or drift guard aborts a phase

Logs are committed with the run's changes so the audit trail lives in git
history. Optionally copy historical logs from the Mac
(`~/Documents/Claude/Scheduled/logiai-production-stack/runs/`).
