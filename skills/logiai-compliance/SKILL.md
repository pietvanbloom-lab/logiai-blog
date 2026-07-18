---
name: logiai-compliance
description: >-
  Ensure correct taxonomy, EU AI Act compliance, GDPR adherence, copyright
  protection, and full audit trail integrity for LogiAI content. Use when the
  user says "compliance check", "assign taxonomy tags", "EU AI Act review",
  "GDPR check", "audit trail", "taxonomy review", "LogiAI compliance",
  "copyright check", or "editorial independence review". Runs five compliance
  checks per story and manages the LogiAI taxonomy system and immutable audit
  trail.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Compliance — Agent 11 + 12: Taxonomy, Governance & Audit Trail

## When to Use This Skill

Use this skill when:

- A story needs compliance clearance before or during publication
- Taxonomy tags need to be assigned or verified
- The Operator requests an EU AI Act or GDPR compliance review
- Audit trail integrity needs to be checked
- A new entity (company, product, regulation) needs to be registered in the taxonomy

This is an **Extended** skill — add once the core 6-skill workflow is stable. Compliance runs in parallel with QC, not as a sequential blocker for standard stories.

## Instructions

### Step 1 — Load Story Context

For each story requiring compliance review, load:
- The draft package (`logiai-draft-*.md`)
- The verification report (`logiai-verified-*.md`)
- The QC report (`logiai-qc-*.md`) if available
- Any escalation notes from the scoring output

### Step 2 — Run Five Compliance Checks

#### Check 1: EU AI Act Classification

Assess whether the story involves AI systems that fall under EU AI Act categories (full enforcement August 2026):

- **Unacceptable Risk** — AI systems banned under the Act (social scoring, real-time biometric surveillance in public spaces)
- **High Risk** — AI in critical infrastructure, transport safety, employment decisions
- **Limited Risk** — AI with transparency obligations (chatbots, deepfakes)
- **Minimal Risk** — Most AI applications with no specific obligations

For each story:
- Identify if any AI system mentioned falls into a regulated category
- Flag if the story discusses deployment of high-risk AI in logistics/transport
- Note compliance implications for LogiAI's readers
- **If relevant:** add a brief EU AI Act context note for the Operator

#### Check 2: GDPR / Data Protection

- Does the story reference personal data processing?
- Are any individuals named or identifiable?
- Does the story discuss surveillance, tracking, or monitoring technologies?
- Are data transfer issues (EU-US, cross-border) mentioned?

**Flag if:** personal data processing, employee monitoring, or location tracking is central to the story.

#### Check 3: Copyright

- Is the content original or does it closely follow a single source's structure/language?
- Are direct quotes properly attributed and within fair use limits?
- Are any images, charts, or data visualizations from third parties properly credited?
- Does the draft contain any passages that could be considered paraphrasing too closely?

**Rule:** LogiAI content must be original analysis, not rewritten press releases. If more than 30% of a draft mirrors a single source's structure and phrasing, flag for rewrite.

#### Check 4: Source Attribution

- Are all sources cited in the text?
- Are source URLs included in the metadata?
- Is the attribution level appropriate for the verification status?
  - Verified stories: standard citation
  - Partial stories: enhanced attribution with qualifying language
- Are any claims presented without attribution that should have it?

**Rule:** Every factual claim must be traceable to a named source. No orphan facts.

#### Check 5: Editorial Independence

- Does the story read as independent analysis or as promotion for a company/product?
- Are competing perspectives represented where relevant?
- Is there any actual or perceived conflict of interest?
- Does the content maintain LogiAI's position as a neutral observer?

**Flag if:** the story reads as a press release rewrite, promotes a single vendor without context, or could damage LogiAI's credibility as an independent source.

### Step 3 — Assign Compliance Status

| Status | Criteria | Action |
|---|---|---|
| **Clear** | All 5 checks pass | Proceed with publication |
| **Conditional** | 1–2 checks flagged with manageable issues | Proceed with specific modifications noted |
| **Blocked** | Critical flag on any check | Hold for Operator review; do not publish |

### Step 4 — Apply Taxonomy

Assign the full LogiAI taxonomy to each story:

#### Primary Theme Clusters (select 1 primary, up to 2 secondary)

| Cluster | Sub-Topics |
|---|---|
| AI in Operations | Route optimization, demand forecasting, warehouse automation, predictive maintenance, capacity planning |
| Autonomous Vehicles | Self-driving trucks, delivery drones, autonomous ships, platooning, regulatory frameworks |
| Supply Chain Visibility | Track-and-trace, IoT sensors, digital twins, control towers, real-time monitoring |
| AI in Sales, Solutions & Tender Management | AI-driven pricing, tender scoring, RFP automation, customer analytics, CRM intelligence |
| Startups & Tools | New platforms, funding rounds, product launches, tool reviews, innovation labs |
| Last-Mile | Urban delivery, micro-fulfillment, crowdsourced logistics, returns handling, sustainability |
| Regulation & Market | EU AI Act, trade policy, antitrust, market consolidation, labor regulations |

#### Entity Register

For companies, products, and regulations mentioned:
- Use consistent naming (e.g., always "Maersk" not "A.P. Moller-Maersk" unless the legal entity context requires it)
- Register new entities with: full name, short name, type (company/product/regulation/organization), first mention date

#### Geography Tags (select all that apply)

- EU
- DACH (Germany, Austria, Switzerland)
- North America
- Asia-Pacific
- Global
- Other (specify)

#### Audience Tags (select all that apply)

- Operations
- Sales & Solutions
- Tender Management
- Executive
- Tech Practitioner

### Step 5 — Build Audit Trail Entry

For every published story, create a complete, immutable audit trail entry:

```
=== LOGIAI AUDIT TRAIL ===
Story ID: [Unique identifier]
Headline: [Published headline]
Published: [YYYY-MM-DD HH:MM CET]

PIPELINE TIMESTAMPS:
  Monitored: [YYYY-MM-DD HH:MM]
  Scored: [YYYY-MM-DD HH:MM] — Score: [N]/100, Class: [Priority Class]
  Verified: [YYYY-MM-DD HH:MM] — Status: [Verified/Partial], Confidence: [N]/100
  Drafted: [YYYY-MM-DD HH:MM] — Format: [Blog Post/Newsletter/etc.]
  QC Reviewed: [YYYY-MM-DD HH:MM] — QC Score: [N]/100
  Approval Route: [Auto-Approve / Soft Escalation / Hard Escalation]
  Operator Signature: [Name + timestamp if escalated, "N/A — Auto" if auto-approved]
  Compliance Cleared: [YYYY-MM-DD HH:MM] — Status: [Clear/Conditional/Blocked]
  Published: [YYYY-MM-DD HH:MM] — Channel: [WordPress / Newsletter / Both]

TAXONOMY APPLIED:
  Primary Theme: [Cluster]
  Secondary Themes: [If any]
  Geography: [Tags]
  Audience: [Tags]
  Entities: [List of entities mentioned]

COMPLIANCE RESULTS:
  EU AI Act: [Clear / Flagged — details]
  GDPR: [Clear / Flagged — details]
  Copyright: [Clear / Flagged — details]
  Attribution: [Clear / Flagged — details]
  Independence: [Clear / Flagged — details]

CORRECTION HISTORY:
  [None / List of corrections with dates and descriptions]

AUDIT INTEGRITY:
  Created: [Timestamp]
  Hash: [MD5 or SHA-256 of entry content for tamper detection]
  Status: [Immutable — no modifications permitted after creation]
```

### Step 6 — Save and Archive

- Save compliance report to workspace as `logiai-compliance-[YYYY-MM-DD]-[story-id].md`
- Save audit trail entry to workspace as `logiai-audit-[YYYY-MM-DD]-[story-id].md`
- Audit trail entries are **immutable** — never modify after creation
- If a correction is needed, append to the correction history, do not edit the original entry
- Compliance reports feed into the quality gate and publishing skills as a parallel input

## Audit Trail Integrity Rules

- **Every published story must have an audit trail entry** — no exceptions
- **Entries are immutable** — once created, the original entry is never edited
- **Corrections are additive** — new information goes into the Correction History section
- **Hash verification** — each entry includes a content hash for tamper detection
- **Retention** — audit trail entries are retained indefinitely

## Notes

- The compliance skill runs in parallel with QC, not sequentially — it should not delay standard-risk stories
- EU AI Act full enforcement begins August 2026; flag any story about AI deployment in transport that may fall under High Risk classification
- The taxonomy system ensures consistent categorization across all content, enabling meaningful performance analysis by theme
- Entity register consistency is critical for brand mentions, SEO, and reader trust
- Editorial independence is LogiAI's most important long-term asset — protect it aggressively
