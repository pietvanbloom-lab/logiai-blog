---
name: logiai-verification
description: >-
  Check source robustness, factual accuracy, date currency, and claim
  separability before drafting begins. Use when the user says "verify this
  story", "fact-check", "confidence score", "source verification", "check
  claims", "validate story", "LogiAI verification", or "verify before drafting".
  Runs five structured checks on scored story candidates and assigns a
  Confidence Score and verification status (Verified / Partial / Unverified).
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Verification — Agent 5: Fact-Check & Source Verification

## When to Use This Skill

Use this skill when:

- Scored story candidates are ready for fact-checking before drafting
- The Operator requests manual verification of a specific story or claim
- A story has an escalation flag and needs extra scrutiny
- Re-verification is needed after new information surfaces

This is a **Core** skill — required for Phase 1 minimum viable operations.

## Instructions

### Step 1 — Load Scored Candidates

Read the latest scored output from workspace: `logiai-scored-*.md`

Process only candidates with Priority Class = Lead Story, Supporting Item, or Spotlight. Skip Rejected stories.

Process in priority order: Lead Stories first, then Supporting Items, then Spotlights. Stories with escalation flags get extra scrutiny regardless of class.

### Step 2 — Run Five Structured Checks

For each story candidate, execute these five checks sequentially:

#### Check 1: Primary Source Identification

- Can you identify the original source of the key claims? (company announcement, regulatory filing, earnings report, official statement)
- Is the article citing a primary source directly, or is it reporting on another publication's report?
- **Pass criteria:** At least one claim traces to a named primary source with a verifiable URL or document reference

#### Check 2: Cross-Source Validation

- Can you find at least 2 independent sources reporting the same core facts?
- "Independent" means different editorial organizations, not syndicated copies of the same wire story
- Search for the key claims across multiple sources to verify consistency
- **Pass criteria:** Minimum 2 independent sources confirm the central facts

#### Check 3: Date and Currency Check

- When was the primary source published?
- Is the information current? (Maximum 7 days for stories labelled "current" or "breaking")
- Are any cited statistics or figures from a recent reporting period?
- **Pass criteria:** Primary source published within 7 days; cited data from most recent available period

#### Check 4: Fact vs. Claim Separation

- Which statements in the story are verified facts vs. company claims vs. analyst opinions vs. projections?
- Label each key statement: [FACT], [CLAIM], [OPINION], [PROJECTION]
- Are any claims presented as facts without sufficient evidence?
- **Pass criteria:** Clear separation exists; no unattributed claims presented as established facts

#### Check 5: Figures Traced to Original Source

- For every number, percentage, dollar amount, or quantitative claim: can you trace it to the original source?
- Are the figures accurately represented (not taken out of context, rounded correctly)?
- **Pass criteria:** All key figures traceable to named sources

### Step 3 — Assign Verification Status

Based on check results:

| Status | Criteria | Downstream Action |
|---|---|---|
| **Verified** | All 5 checks pass | Auto-approve pathway eligible |
| **Partial** | 3–4 checks pass, gaps are identifiable and manageable | Publish with explicit attribution: "according to X", "company claims", "unconfirmed reports suggest" |
| **Unverified** | Fewer than 3 checks pass, or contradictions found, or no primary source | **DO NOT PUBLISH** — escalate to Operator or return for re-research |

### Step 4 — Calculate Confidence Score

Assign a Confidence Score (0–100) based on check results:

| Check Result | Points |
|---|---|
| Check 1 pass | +25 |
| Check 2 pass | +25 |
| Check 3 pass | +20 |
| Check 4 pass | +15 |
| Check 5 pass | +15 |

**Partial credit:** If a check partially passes (e.g., one independent source instead of two), award half points.

### Step 5 — Document Attribution Requirements

For **Partial** status stories, specify exactly which claims need explicit attribution in the draft:

```
ATTRIBUTION REQUIREMENTS:
- Claim: "[specific claim]" → Attribute to: [source name]
- Claim: "[specific claim]" → Mark as: [CLAIM / OPINION / PROJECTION]
- Figure: "[specific number]" → Source: [original source] | Status: [confirmed / unconfirmed]
```

These requirements are binding for the drafting skill — the writer must follow them.

### Step 6 — Produce Verification Report

For each story:

```
=== VERIFICATION REPORT ===
Story ID: [From scoring output]
Headline: [From scoring output]
Priority Class: [From scoring output]
Verification Date: [YYYY-MM-DD HH:MM CET]

CHECK RESULTS:
  1. Primary Source:     [PASS / PARTIAL / FAIL] — [One-line finding]
  2. Cross-Source:       [PASS / PARTIAL / FAIL] — [One-line finding]
  3. Date/Currency:      [PASS / PARTIAL / FAIL] — [One-line finding]
  4. Fact vs. Claim:     [PASS / PARTIAL / FAIL] — [One-line finding]
  5. Figures Traced:     [PASS / PARTIAL / FAIL] — [One-line finding]

VERIFICATION STATUS: [Verified / Partial / Unverified]
CONFIDENCE SCORE: [0–100]

ATTRIBUTION REQUIREMENTS: [If Partial — list specific requirements]
ESCALATION NOTES: [If Unverified — explain why and recommend next steps]

SOURCES CONSULTED:
  - [Source 1 name + URL]
  - [Source 2 name + URL]
  - [Additional sources...]
```

### Step 7 — Save and Hand Off

- Save the verification report to workspace as `logiai-verified-[YYYY-MM-DD].md`
- Verified and Partial stories feed into **logiAI-drafting**
- Unverified stories are returned to the Operator with the escalation notes
- No story below Verified status may be auto-approved (this rule cannot be overridden by any agent)

## Critical Rules

- **Never downgrade a check to pass when it should fail** — integrity of the verification chain depends on honest assessment
- **Unverified = DO NOT PUBLISH** — this is absolute; only the Operator can override after review
- **Partial status requires specific attribution instructions** — vague "use caution" is not sufficient
- **Confidence Score is derived from check results only** — never adjust it based on how "important" the story seems
- **Every check must have a finding** — no blank checks, even for straightforward stories

## Notes

- Verification is the quality firewall between research and publication
- The 5-check structure ensures consistency across all stories regardless of topic
- For stories with escalation flags: apply extra scrutiny on Checks 1, 2, and 4
- If a previously Verified story receives contradicting information post-verification, it must be re-verified before any update is published
