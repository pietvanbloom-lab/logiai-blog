---
name: logiai-monitoring
description: >-
  Daily and weekly source monitoring plus signal consolidation for the LogiAI
  news blog. Use when the user says "run morning scan", "daily signal scan",
  "weekly editorial pack", "source monitoring", "story candidates", "news scan",
  "LogiAI scan", "editorial briefing", or "breaking signal". Combines Agent 2
  (Monitoring) and Agent 3 (Source Intelligence) for Phase 1 operations. Scans
  Tier 1 and Tier 2 logistics/transport sources, applies the direct impact
  filter, and outputs structured research objects for downstream scoring.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Monitoring — Agent 2 + 3: Source Monitoring & Signal Consolidation

## When to Use This Skill

Use this skill when:

- Running the daily morning scan (weekdays 07:00–08:00 CET)
- Preparing the weekly editorial pack (Fridays 15:00 CET)
- A breaking signal needs immediate processing
- The Operator requests a manual scan of specific sources or topics

This is a **Core** skill — required for Phase 1 minimum viable operations.

## Instructions

### Step 1 — Load Active Strategy

Check the workspace for the latest `logiai-strategy-brief-*.md` file. If none exists, use these defaults:

- **Theme weights:** AI in Operations 25%, Autonomous Vehicles 15%, Supply Chain Visibility 15%, AI in Sales/Solutions/Tender 15%, Startups & Tools 15%, Last-Mile 10%, Regulation & Market 5%
- **Source list:** Full Tier 1 + Tier 2 (see below)

### Step 2 — Scan Sources

Search across the defined source tiers using web search and URL fetching.

**Tier 1 Sources (always scan all):**
1. Reuters (logistics, shipping, supply chain sections)
2. FreightWaves
3. The Loadstar
4. Transport Topics
5. Supply Chain Dive
6. Trans.info
7. Logistics Manager
8. TechCrunch (logistics/supply chain/AI verticals)
9. Maersk Insights
10. ASCM (Association for Supply Chain Management)

**Tier 2 Sources (scan selectively based on active themes):**
- LinkedIn posts from logistics thought leaders
- Startup blogs and press releases
- Industry conference announcements
- Publicly available analyst snippets (McKinsey, Gartner, BCG)

**Search Strategy:**
- Use 3–5 parallel search queries covering the active theme clusters
- Include date filters: last 24 hours for daily scan, last 7 days for weekly pack
- Prioritize Tier 1 results over Tier 2
- For each promising result, fetch the full article to extract details

### Step 3 — Apply the Hard Filter

**Every signal must pass the direct logistics/transport impact test:**

Ask: "Does this story directly affect how logistics, transport, or supply chain operations are planned, executed, priced, or regulated?"

- **YES** → Include as candidate
- **MAYBE** → Include only if the logistics angle is clearly articulable in one sentence
- **NO** → Reject immediately, regardless of general AI or tech interest

**Automatic rejection triggers:**
- Pure tech news with no logistics application
- Generic AI capability announcements without industry impact
- Opinion pieces without new factual content
- Press releases that are purely promotional without operational substance
- Stories older than 7 days (unless ongoing regulatory or market development)

### Step 4 — Build Structured Research Objects

For each story that passes the filter, create a structured research object:

```
=== STORY CANDIDATE ===
ID: [YYYYMMDD-###]
Headline: [Factual headline, max 80 characters]
Source: [Publication name]
Source URL: [Direct link]
Date Published: [YYYY-MM-DD]
Source Tier: [1 or 2]
Theme Cluster: [Primary theme from weight matrix]
Secondary Themes: [If applicable]

SUMMARY (3–5 sentences):
[What happened, key facts, numbers if available]

WHY IT COUNTS (2–3 sentences):
[Direct logistics/transport impact — answer "why should a logistics professional care?"]

PRIMARY SOURCE STATUS:
[Original source identified / Company announcement / Third-party report / Unattributed]

MARKET CONTEXT (1–2 sentences):
[How this fits into broader industry trends]

CROSS-REFERENCES:
[Related recent stories or developments, if any]
```

### Step 5 — Assemble Output Package

**Daily Scan Output (weekdays):**
- 5–10 story candidates, ranked by estimated relevance
- Each as a complete structured research object
- Summary header: date, number of sources scanned, number of candidates selected, number rejected

**Weekly Editorial Pack (Fridays):**
- 7–10 story candidates selected from the week's best
- Includes any stories that gained momentum across the week
- Flags developing stories that may warrant follow-up
- Recommends 2–3 lead story candidates for the newsletter

**Breaking Signal (immediate):**
- Single story candidate with urgency flag
- Triggers only for: major regulatory announcements, significant M&A in logistics, critical supply chain disruptions, or safety/security events

### Step 6 — Save and Hand Off

- Save the output package to workspace as:
  - Daily: `logiai-candidates-daily-[YYYY-MM-DD].md`
  - Weekly: `logiai-candidates-weekly-[YYYY-MM-DD].md`
  - Breaking: `logiai-breaking-[YYYY-MM-DD-HHMM].md`
- Log scan metadata: sources checked, time taken, filter stats
- The candidate package feeds directly into **logiAI-scoring** for priority ranking

### Step 7 — Quality Checks

Before handing off:
- Verify all URLs are accessible and point to the claimed source
- Confirm no duplicate stories (same event from multiple sources = one candidate with cross-references)
- Ensure every candidate has a complete research object (no empty fields)
- Check date currency: nothing older than 7 days in daily pack, 14 days in weekly pack

## Output Cadence Summary

| Type | Schedule | Volume | Trigger |
|---|---|---|---|
| Daily Scan | Mon–Sat 07:00–08:00 CET | 5–10 candidates | Automatic / "run morning scan" |
| Weekly Pack | Friday 15:00 CET | 7–10 candidates | Automatic / "weekly editorial pack" |
| Breaking Signal | Immediate | 1 candidate | Automatic detection |

## Notes

- This skill combines Agent 2 (Monitoring) and Agent 3 (Source Intelligence) — the recommended merged form for single-operator Phase 1
- The hard filter is non-negotiable: no story proceeds without a clear logistics/transport impact
- Tier 1 source share target: at least 70% of candidates should come from Tier 1 sources
- When in doubt about a story's relevance, include it with a note — let the scoring skill decide
