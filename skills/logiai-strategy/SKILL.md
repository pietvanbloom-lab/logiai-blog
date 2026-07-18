---
name: logiai-strategy
description: >-
  Translate operator input into operative steering parameters for all downstream
  LogiAI agents. Use when the user says "update strategy", "set focus list",
  "weekly editorial priorities", "adjust theme weights", "strategy brief",
  "editorial steering", or "LogiAI strategy review". Generates a structured
  strategy brief containing active theme focus list, scoring weight confirmation,
  escalation criteria, source watchlist, and production logic. Feeds monitoring
  keywords and scoring weights to downstream skills.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Strategy — Agent 1: Strategy & Priority Steering

## When to Use This Skill

Use this skill when the Operator needs to:

- Set or update the weekly editorial focus and theme priorities
- Adjust scoring weights for the relevance model
- Define or modify the source watchlist
- Set escalation criteria for sensitive topics
- Generate a strategy brief that steers all downstream agents
- Review and confirm the production logic for the upcoming cycle

Recommended cadence: weekly review (~10 minutes), or ad-hoc when strategic priorities shift.

## Instructions

### Step 1 — Collect Operator Input

Ask the Operator (or read from the latest strategy session) for updates on:

1. **Theme Focus List** — Which themes should receive priority this cycle?
2. **Weight Adjustments** — Any changes to the scoring weight matrix?
3. **Source Changes** — New sources to add or existing sources to deprioritize?
4. **Escalation Triggers** — Any new sensitive topics, companies, or regulations to flag?
5. **Production Logic** — Changes to publication cadence, format mix, or channel emphasis?

If the Operator provides no updates, use the current defaults.

### Step 2 — Apply Default Theme Weight Matrix

Unless the Operator overrides, use this default matrix:

| Theme Cluster | Default Weight |
|---|---|
| AI in Operations | 25% |
| Autonomous Vehicles | 15% |
| Supply Chain Visibility | 15% |
| AI in Sales, Solutions & Tender Management | 15% |
| Startups & Tools | 15% |
| Last-Mile | 10% |
| Regulation & Market | 5% |

Total must always equal 100%. If the Operator adjusts one weight, redistribute the delta proportionally across remaining themes unless explicitly instructed otherwise.

### Step 3 — Confirm Source Watchlist

Maintain two tiers:

**Tier 1 Sources (Primary — always monitored):**
- Reuters, FreightWaves, The Loadstar, Transport Topics
- Supply Chain Dive, Trans.info, Logistics Manager
- TechCrunch (logistics/supply chain vertical)
- Maersk Insights, ASCM

**Tier 2 Sources (Secondary — monitored selectively):**
- LinkedIn (industry thought leaders, company pages)
- Startup blogs and press releases
- Industry conference proceedings
- Analyst reports (McKinsey, Gartner, BCG when publicly available)

Flag any new sources the Operator wants to add. Remove or demote sources only with explicit Operator approval.

### Step 4 — Define Escalation Criteria

Default escalation triggers (always active):

- EU AI Act developments (full enforcement August 2026)
- Stories involving named companies with negative framing
- Unverified claims about market-moving events
- Regulatory enforcement actions
- Data privacy / GDPR implications
- Stories where LogiAI's editorial independence could be questioned

The Operator may add or remove triggers. Log all changes.

### Step 5 — Generate Strategy Brief

Produce a structured strategy brief in this format:

```
=== LOGIAI STRATEGY BRIEF ===
Cycle: [Week number / Date range]
Updated: [Timestamp]

1. THEME FOCUS LIST
   [Ranked list with weights]

2. SCORING WEIGHTS (confirmed / adjusted)
   Relevance: 0.30
   Practical Value: 0.25
   Newsworthiness: 0.20
   Market Impact: 0.15
   Reader Fit: 0.10

3. SOURCE WATCHLIST
   Tier 1: [List]
   Tier 2: [List]
   New additions: [If any]
   Removals: [If any]

4. ESCALATION CRITERIA
   [Active triggers]

5. PRODUCTION LOGIC
   Daily cadence: Mon–Sat, 08:00 CET
   Content rotation: Mon/Wed/Fri deep articles | Tue prompt | Thu quick tip | Sat roundup
   Sunday: Rest day
   Newsletter: Optional weekly digest (blog-first model)

6. OPERATOR NOTES
   [Any free-form guidance]
```

### Step 6 — Save and Distribute

- Save the strategy brief to the workspace as `logiai-strategy-brief-[date].md`
- Update the Google Sheets tracker if connected
- The strategy brief content feeds directly into:
  - **logiAI-monitoring** → source selection and keyword focus
  - **logiAI-scoring** → weight matrix and theme priorities
  - **logiAI-compliance** → escalation trigger list

### Step 7 — Confirm with Operator

Present the strategy brief to the Operator for final confirmation. No downstream processing begins until the Operator confirms or the previous cycle's brief remains active.

## Output Format

The strategy brief is the primary output. It must be:
- Machine-readable (structured markdown)
- Human-scannable (clear headers, tables)
- Timestamped and versioned
- Stored in workspace for audit trail

## Notes

- The strategy skill is an **Extended** skill — not required for Phase 1 minimum viable operations
- If no strategy brief exists, downstream skills use hardcoded defaults
- The Operator is the only human role; all other processing is autonomous
- Theme weights influence scoring but do not override the five-criteria model
