---
name: logiai-performance
description: >-
  Measure LogiAI publication performance against KPI targets and generate
  learning signals for upstream agents. Use when the user says "weekly KPI
  report", "metrics review", "performance analysis", "LogiAI metrics",
  "open rate analysis", "content performance", "KPI dashboard", or "optimization
  recommendations". Generates structured weekly performance reports, story-level
  analysis, and automated optimization signals.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Performance — Agent 10: KPI Measurement & Learning Signals

## When to Use This Skill

Use this skill when:

- The weekly performance review is due (every Sunday post-newsletter dispatch)
- The Operator requests a metrics review or KPI check
- A monthly theme performance analysis is needed
- Optimization recommendations are triggered by metric thresholds

This is an **Extended** skill — add once the core 6-skill workflow is stable.

## Instructions

### Step 1 — Collect Performance Data

Gather data from all available sources:

**Blog Performance (from WordPress / Google Analytics / Jetpack):**
- Page views per post
- Average time on page
- Bounce rate
- Traffic sources (organic, social, direct, referral)
- Top-performing posts (by views, engagement)

**Newsletter Performance (from Mailchimp / email platform):**
- Open Rate
- Click-Through Rate (CTR)
- Unsubscribe Rate
- Bounce Rate (email)
- Subscriber growth/decline

**Pipeline Performance (from workspace audit trail files):**
- On-Time Publication rate
- First-Review Approval rate (% of drafts auto-approved on first QC pass)
- Tier 1 Source Share (% of published stories sourced from Tier 1)
- Production Cycle time (days from monitoring to publication)
- Rejection rate and common rejection reasons

### Step 2 — Compare Against KPI Targets

| KPI | Target | Current | Status |
|---|---|---|---|
| Newsletter Open Rate | >= 25% | [Measured] | [On Track / Below / Above] |
| Newsletter CTR | >= 8% | [Measured] | [On Track / Below / Above] |
| Unsubscribe Rate | <= 2% | [Measured] | [On Track / Below / Above] |
| On-Time Publication | 100% | [Measured] | [On Track / Below] |
| First-Review Approval | >= 80% | [Measured] | [On Track / Below / Above] |
| Tier 1 Source Share | >= 70% | [Measured] | [On Track / Below / Above] |
| Production Cycle | <= 6 days | [Measured] | [On Track / Below / Above] |

### Step 3 — Story-Level Performance Analysis

For each story published in the reporting period:

```
Story: [Headline]
Published: [Date]
Format: [Blog Post / Newsletter Lead / etc.]
Theme Cluster: [Primary theme]
Priority Class: [Lead / Supporting / Spotlight]
QC Score: [From QC report]

Blog Performance:
  Page Views: [N]
  Avg. Time on Page: [seconds]
  Bounce Rate: [%]

Newsletter Performance (if included):
  Click Rate: [%] (for this specific story link)

Verdict: [High Performer / Average / Below Average]
```

### Step 4 — Generate Automated Optimization Recommendations

Trigger recommendations when specific thresholds are crossed:

| Trigger Condition | Recommendation |
|---|---|
| Open Rate < 20% | Subject line A/B testing; review headline patterns of top-performing issues |
| CTR < 5% | Review story selection criteria; increase practical value emphasis in scoring |
| Unsubscribe Rate > 3% | Audit content relevance; check if theme mix matches subscriber expectations |
| First-Review Approval < 70% | Review common QC failures; adjust drafting guidelines for recurring issues |
| Tier 1 Source Share < 60% | Tighten monitoring filters; reduce Tier 2 source weight |
| Production Cycle > 8 days | Identify bottleneck stage; check for stuck escalations or delayed approvals |
| Bounce Rate > 60% (blog) | Review headline accuracy; check if content delivers on the headline promise |

Each recommendation includes:
- The metric that triggered it
- Specific suggested action
- Expected impact
- Priority (act now / monitor / informational)

### Step 5 — Generate Learning Signals

Format optimization insights as structured signals that can feed back into the Strategy skill:

```
=== LEARNING SIGNAL ===
Signal Type: [Theme Performance / Format Performance / Source Quality / Engagement Pattern]
Period: [Date range]
Finding: [One-sentence description]
Evidence: [Supporting data points]
Recommendation: [Specific action for strategy adjustment]
Priority: [High / Medium / Low]
```

Examples:
- "AI in Operations stories consistently outperform other clusters by 2x in page views — consider increasing weight"
- "Spotlight format generates 40% lower engagement than Supporting Items — consider merging into Supporting format"
- "Stories sourced from FreightWaves receive 30% more clicks than average — prioritize in monitoring"

### Step 6 — Monthly Theme Performance Analysis

Once per month, analyze theme cluster performance over the full month:

```
=== MONTHLY THEME ANALYSIS ===
Period: [Month / Year]

| Theme Cluster | Stories Published | Avg. Page Views | Avg. CTR | Trend |
|---|---|---|---|---|
| AI in Operations | [N] | [N] | [%] | [Rising / Stable / Declining] |
| Autonomous Vehicles | [N] | [N] | [%] | [Rising / Stable / Declining] |
| Supply Chain Visibility | [N] | [N] | [%] | [Rising / Stable / Declining] |
| AI in Sales/Solutions | [N] | [N] | [%] | [Rising / Stable / Declining] |
| Startups & Tools | [N] | [N] | [%] | [Rising / Stable / Declining] |
| Last-Mile | [N] | [N] | [%] | [Rising / Stable / Declining] |
| Regulation & Market | [N] | [N] | [%] | [Rising / Stable / Declining] |

SUSTAINED ENGAGEMENT CLUSTERS: [Themes with consistent above-average performance]
DRIFT CLUSTERS: [Themes with declining engagement despite consistent coverage]
EMERGING OPPORTUNITIES: [Themes showing growth potential based on trend data]
```

### Step 7 — Produce Weekly Performance Report

```
=== LOGIAI WEEKLY PERFORMANCE REPORT ===
Report Date: [YYYY-MM-DD]
Reporting Period: [Start — End date]

KPI DASHBOARD:
[Table from Step 2]

TOP PERFORMERS:
1. [Headline] — [Key metric]
2. [Headline] — [Key metric]
3. [Headline] — [Key metric]

OPTIMIZATION ALERTS:
[Any triggered recommendations from Step 4]

LEARNING SIGNALS:
[Signals from Step 5]

PIPELINE HEALTH:
  Stories Monitored: [N]
  Stories Scored: [N]
  Stories Verified: [N]
  Stories Drafted: [N]
  Stories Published: [N]
  Rejection Rate: [%]
  Avg. Production Cycle: [N days]

OPERATOR ACTION ITEMS:
[Specific items requiring Operator attention]
```

### Step 8 — Save and Distribute

- Save the weekly report to workspace as `logiai-performance-weekly-[YYYY-MM-DD].md`
- Save monthly analysis as `logiai-performance-monthly-[YYYY-MM].md`
- Learning signals feed back into **logiAI-strategy** for the next cycle's weight adjustments
- Present the report to the Operator for review

## Notes

- The performance skill closes the feedback loop: publication results inform strategy adjustments
- Data availability depends on connected analytics tools (Google Analytics, Mailchimp, WordPress stats)
- If analytics data is not yet available (new blog), use pipeline metrics (approval rates, production cycle) as early indicators
- The monthly theme analysis is the primary input for strategic weight adjustments
- Performance reports accumulate as a longitudinal dataset for trend analysis
