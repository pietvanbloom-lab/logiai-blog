---
name: logiai-scoring
description: >-
  Score every LogiAI story candidate against the five-criteria relevance model
  and assign a processing priority class. Use when the user says "score story
  candidates", "rank stories", "prioritize signals", "run scoring", "LogiAI
  scoring", "story ranking", or "priority classification". Takes structured
  research objects from the monitoring skill and produces a ranked shortlist
  with scores, rationales, and priority class assignments.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Scoring — Agent 4: Relevance Scoring & Priority Classification

## When to Use This Skill

Use this skill when:

- A daily scan or weekly editorial pack has been produced by the monitoring skill
- The Operator manually submits story candidates for scoring
- Stories need re-scoring after strategy weight adjustments

This is a **Core** skill — required for Phase 1 minimum viable operations.

## Instructions

### Step 1 — Load Inputs

1. Read the latest candidate file from workspace: `logiai-candidates-daily-*.md` or `logiai-candidates-weekly-*.md`
2. Load the active strategy brief (`logiai-strategy-brief-*.md`) for current theme weights
3. If no strategy brief exists, use default weights

### Step 2 — Apply the Five-Criteria Scoring Model

Score every candidate on five criteria, each rated 0–100 with a mandatory one-line rationale:

| Criterion | Weight | What It Measures |
|---|---|---|
| **Relevance** | 0.30 | Direct connection to active LogiAI theme clusters. How clearly does this story map to AI in logistics/transport? |
| **Practical Value** | 0.25 | Actionable insight for the reader. Can a logistics professional do something different after reading this? |
| **Newsworthiness** | 0.20 | Timeliness and novelty. Is this genuinely new information, or a rehash of known developments? |
| **Market Impact** | 0.15 | Scale of effect on the logistics/transport market. Does this affect pricing, capacity, regulation, or competitive dynamics? |
| **Reader Fit** | 0.10 | Alignment with LogiAI's target audience: sales teams, tender managers, buyers, operations planners, and logistics specialists. |

### Step 3 — Calculate Final Score

```
Final Score = (Relevance × 0.30) + (Practical Value × 0.25) + (Newsworthiness × 0.20) + (Market Impact × 0.15) + (Reader Fit × 0.10)
```

Round to one decimal place.

### Step 4 — Assign Priority Class

| Score Range | Priority Class | Processing Action |
|---|---|---|
| **80–100** | Lead Story | Full blog post + newsletter lead candidate |
| **60–79** | Supporting Item | Newsletter section or short blog post |
| **40–59** | Spotlight | Brief mention, tool/startup format, or "worth watching" note |
| **0–39** | Reject | Processing stops — story is logged but not drafted |

### Step 5 — Flag Escalation Triggers

Regardless of score, flag any story that involves:

- EU AI Act regulatory developments
- Named companies with negative or critical framing
- Unverified claims about significant market events
- Legal or liability implications
- Data privacy / GDPR topics
- Content where LogiAI's editorial independence could be questioned

Flagged stories receive an `[ESCALATION FLAG]` marker and must go through Operator review even if they score above 80.

### Step 6 — Produce Scored Output

For each candidate, produce:

```
=== SCORED CANDIDATE ===
ID: [From monitoring output]
Headline: [From monitoring output]
Source: [From monitoring output]

SCORES:
  Relevance (0.30):       [Score]/100 — [One-line rationale]
  Practical Value (0.25): [Score]/100 — [One-line rationale]
  Newsworthiness (0.20):  [Score]/100 — [One-line rationale]
  Market Impact (0.15):   [Score]/100 — [One-line rationale]
  Reader Fit (0.10):      [Score]/100 — [One-line rationale]

FINAL SCORE: [Weighted total]/100
PRIORITY CLASS: [Lead Story / Supporting Item / Spotlight / Reject]
ESCALATION FLAG: [Yes/No — reason if Yes]
RECOMMENDED FORMAT: [Blog Post / Newsletter Lead / Newsletter Supporting / Spotlight / Reject]
```

### Step 7 — Rank and Assemble Shortlist

1. Sort all candidates by Final Score (descending)
2. Group by Priority Class
3. Within each class, recommend processing order
4. For the weekly pack: recommend which stories pair well together for newsletter assembly

Produce a summary table:

```
=== LOGIAI SCORING SUMMARY ===
Date: [YYYY-MM-DD]
Candidates Scored: [N]
Lead Stories: [N] (scores: [range])
Supporting Items: [N] (scores: [range])
Spotlights: [N] (scores: [range])
Rejected: [N]
Escalation Flags: [N]
```

### Step 8 — Save and Hand Off

- Save the scored output to workspace as `logiai-scored-[YYYY-MM-DD].md`
- The scored shortlist feeds directly into **logiAI-verification** for fact-checking
- Rejected stories are logged but not processed further

## Scoring Integrity Rules

- Every score must have a rationale — no score without explanation
- Scores must be reproducible: the same story scored twice should yield similar results (within +/- 5 points)
- Theme weights from the strategy brief override defaults — always check for the latest brief
- Never inflate scores to avoid rejects — a weak story scored generously wastes downstream resources
- When two stories cover the same event, score the one with the stronger primary source higher and note the overlap

## Notes

- The scoring model is designed to be auditable: anyone reviewing the output can understand why a story was ranked where it was
- Priority classes determine resource allocation — Lead Stories get full production treatment, Spotlights get minimal
- The 80-point threshold for Lead Story is deliberately high to maintain editorial quality
- Escalation flags override priority class routing — they add Operator review, not remove it
