---
name: logiai-quality-gate
description: >-
  Run the mandatory QC checklist and route every LogiAI draft to auto-approval
  or Operator escalation. Use when the user says "QC check", "approve or
  escalate", "is this ready to publish", "quality check", "review draft",
  "LogiAI QC", "quality gate", or "editorial review". Applies an 18-point
  checklist, calculates a Quality Score, and determines the approval route:
  auto-approve, soft escalation, or hard escalation.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Quality Gate — Agent 7 + 8: QC Checklist & Escalation Routing

## When to Use This Skill

Use this skill when:

- A draft package from the drafting skill is ready for quality review
- The Operator requests a QC check on specific content
- Content needs re-review after revisions
- Pre-publication final check before the publishing skill takes over

This is a **Core** skill — required for Phase 1 minimum viable operations.

## Instructions

### Step 1 — Load Draft Package

Read the latest draft package(s) from workspace: `logiai-draft-*.md`

For each draft, also load:
- The verification report (`logiai-verified-*.md`) for Confidence Score and verification status
- The scoring output (`logiai-scored-*.md`) for priority class and escalation flags

### Step 2 — Run the 18-Point QC Checklist

Score each item as PASS (1 point), PARTIAL (0.5 points), or FAIL (0 points).

#### Content Accuracy (Items 1–4)

1. **Facts match verification report** — All facts in the draft align with the verified research object
2. **Attribution rules followed** — Partial-status stories use required attribution phrases on every affected claim
3. **No unsupported claims** — Draft contains no statements that go beyond what the verification report confirmed
4. **Figures are accurate** — Numbers, percentages, and dates match the original sources

#### Logistics Relevance (Items 5–7)

5. **Direct logistics impact clear** — The reader immediately understands why this matters for logistics/transport
6. **Three core questions answered** — What changed? Why does it matter? What should you try next?
7. **Audience fit confirmed** — Content is relevant to: sales teams, tender managers, buyers, operations planners, or logistics specialists

#### Editorial Standards (Items 8–12)

8. **Tone is correct** — Informed colleague, not analyst; collaborative, direct, precise
9. **No em-dashes** — Zero em-dashes in the entire draft
10. **Word count within limits** — Matches the format specification (Blog Post 400–800, Newsletter Lead 150–250, etc.)
11. **Headline is factual and specific** — States what changed, max 80 characters, no clickbait
12. **Grammar and clarity** — No spelling errors, awkward phrasing, or unclear sentences

#### SEO & Publishing (Items 13–15)

13. **SEO metadata complete** — Title (max 60 chars), meta description (max 155 chars), slug, category, tags all present
14. **Category mapping correct** — Assigned to the right LogiAI reader-facing category
15. **Image brief included** — Two image options with correct style (Realistic / Futuristic / Hybrid)

#### Mobile Readability (Items 16–17)

16. **Paragraphs are short** — No paragraph exceeds 4 sentences; subheads break content every 150–200 words
17. **Scannable structure** — Reader can understand the key message by reading only the headline, subheads, and first sentence of each section

#### Compliance Pre-Check (Item 18)

18. **No obvious compliance risks** — No unattributed criticism of named entities, no potential GDPR issues, no EU AI Act classification concerns visible in the content

### Step 3 — Calculate Quality Score

```
Quality Score = (Sum of all item scores / 18) × 100
```

Round to nearest integer.

### Step 4 — Determine Approval Route

Use this three-level routing model:

#### Route 1: Auto-Approve

**All conditions must be met:**
- Verification Status = Verified
- Quality Score >= 75
- No escalation flags (from scoring)
- No compliance risk flags (from Item 18)
- Standard topic (not regulatory, legal, or involving named-entity criticism)

**Action:** Draft proceeds directly to **logiAI-publishing** without Operator review.

#### Route 2: Soft Escalation

**Any of these conditions triggers soft escalation:**
- Quality Score 60–74
- Verification Status = Partial (regardless of QC score)
- Minor compliance concern noted in Item 18
- Story is editorially borderline (relevance or audience fit scored PARTIAL)

**Action:**
- Operator receives a brief note summarizing the issue and the recommended fix
- 2-hour response window
- If Operator approves: proceed to publishing
- If Operator rejects: return to drafting with feedback
- If no response within 2 hours: story is **held** (never auto-published on timeout)

#### Route 3: Hard Escalation

**Any of these conditions triggers hard escalation:**
- Quality Score < 60
- Verification Status = Unverified
- Escalation flag from scoring (regulatory, legal, named-entity criticism)
- Multiple FAIL items in Content Accuracy section
- GDPR, EU AI Act, or copyright concerns identified

**Action:**
- Operator review is **mandatory**
- No publication without explicit Operator approval
- Draft is held indefinitely until Operator acts
- Operator may: approve as-is, request revisions, or kill the story

### Step 5 — Produce QC Report

```
=== LOGIAI QC REPORT ===
Story ID: [From draft package]
Headline: [From draft package]
QC Date: [YYYY-MM-DD HH:MM CET]
Draft Format: [Blog Post / Newsletter Lead / etc.]

CHECKLIST RESULTS:
Content Accuracy:
  1. Facts match verification:    [PASS/PARTIAL/FAIL]
  2. Attribution rules followed:  [PASS/PARTIAL/FAIL]
  3. No unsupported claims:       [PASS/PARTIAL/FAIL]
  4. Figures accurate:            [PASS/PARTIAL/FAIL]

Logistics Relevance:
  5. Direct impact clear:         [PASS/PARTIAL/FAIL]
  6. Three core questions:        [PASS/PARTIAL/FAIL]
  7. Audience fit:                [PASS/PARTIAL/FAIL]

Editorial Standards:
  8. Tone correct:                [PASS/PARTIAL/FAIL]
  9. No em-dashes:                [PASS/PARTIAL/FAIL]
  10. Word count within limits:   [PASS/PARTIAL/FAIL]
  11. Headline factual/specific:  [PASS/PARTIAL/FAIL]
  12. Grammar and clarity:        [PASS/PARTIAL/FAIL]

SEO & Publishing:
  13. SEO metadata complete:      [PASS/PARTIAL/FAIL]
  14. Category mapping correct:   [PASS/PARTIAL/FAIL]
  15. Image brief included:       [PASS/PARTIAL/FAIL]

Mobile Readability:
  16. Short paragraphs:           [PASS/PARTIAL/FAIL]
  17. Scannable structure:        [PASS/PARTIAL/FAIL]

Compliance Pre-Check:
  18. No compliance risks:        [PASS/PARTIAL/FAIL]

QUALITY SCORE: [0–100]
VERIFICATION STATUS: [Verified / Partial / Unverified]
ESCALATION FLAGS: [From scoring — Yes/No]

APPROVAL ROUTE: [Auto-Approve / Soft Escalation / Hard Escalation]
ROUTE REASON: [One-line explanation of why this route was selected]

ISSUES TO ADDRESS: [If any FAIL or PARTIAL items — specific guidance for fixing]
```

### Step 6 — Save and Hand Off

- Save QC report to workspace as `logiai-qc-[YYYY-MM-DD]-[story-id].md`
- Auto-approved drafts feed directly to **logiAI-publishing**
- Escalated drafts are presented to the Operator with the QC report
- Every QC report is a permanent audit trail entry — never delete or modify after creation

## Critical Rules

- **The QC >= 75 threshold cannot be overridden by any agent** — only the Operator can approve below-threshold content via Hard Escalation
- **Soft Escalation timeout = hold, never auto-publish** — if the Operator does not respond, the story waits
- **Every QC report is immutable once saved** — corrections require a new QC cycle, not editing the old report
- **Item 18 (compliance pre-check) is a safety net, not a replacement for the full compliance skill** — the compliance skill runs in parallel for comprehensive coverage
- **No shortcuts** — even if a story "obviously" passes, every checklist item must be explicitly evaluated

## Notes

- The QC Gate is the last automated checkpoint before human-facing output
- Combined from Agent 7 (QC) and Agent 8 (Escalation) for Phase 1 single-operator efficiency
- The 18-point checklist is designed to catch the most common quality failures in automated content production
- QC reports accumulate as an audit trail — useful for the performance skill's analysis of first-review approval rates
