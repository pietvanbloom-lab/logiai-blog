---
name: logiai-drafting
description: >-
  Transform verified LogiAI story objects into publication-ready content in
  LogiAI's tone, format, and editorial standards. Use when the user says "write
  blog post", "draft newsletter section", "create article", "LogiAI draft",
  "write story", "content drafting", "prepare article", or "draft from verified
  stories". Produces blog posts, newsletter leads, supporting items, and
  startup spotlights with SEO metadata, headline variants, and image briefs.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Drafting — Agent 6: Content Production

## When to Use This Skill

Use this skill when:

- Verified story candidates are ready to be turned into publication content
- The Operator requests a draft for a specific topic or story
- A breaking story needs rapid drafting
- Content needs to be re-drafted after QC feedback

This is a **Core** skill — required for Phase 1 minimum viable operations.

## Styleguide Compliance (binding, READ FIRST)

**Master file:** `<repo>/CONTENT-STYLEGUIDE.md`

Before drafting any content, load the master styleguide. It defines voice, the 70/20/10 stilmix (Stratechery 70%, Pragmatic Engineer 20%, Republik 10%), the three Vorlagen pro Stück-Typ (Deal-Analyse, Tech-Erklärstück, Trend-Stück), the Don'ts-Galerie, and the pre-publish checklist. Every draft must comply.

### Hard Rules (ohne Ausnahme, auch ohne Master-File-Lookup)

1. Keine em-dashes (`—`). Nirgendwo. Stattdessen: Komma, Semikolon, Doppelpunkt, Punkt, oder Klammern.
2. Keine en-dashes (`–`) im Fließtext. Nur in Zahlenbereichen erlaubt: `2024–2026`, `Q1–Q4`. Sonst Bindestrich (`-`).
3. Keine Emojis im redaktionellen Text. Erlaubt in Social-Teasern und Newsletter-Eyebrows, nicht im Artikel.
4. Keine generischen KI-Floskeln. Verboten: "im Zeitalter von", "Game-Changer", "revolutionär", "transformative", "deep dive", "robust", "leverage", "synergistisch", "End-of-Day", "an der Spitze der Innovation".
5. Keine doppelte Verneinung als Stil. "Not unimportant", "nicht unwichtig" ist Schwächen-Verstecken. Wenn etwas wichtig ist, sag es.
6. Kein "in der heutigen schnelllebigen Welt". Wenn der Einstieg austauschbar ist, gehört er gelöscht.
7. Keine LinkedIn-Sätze. Sätze, die wie ein Tipp aus einem Karriere-Newsletter klingen ("Und das ist genau, was Führungskräfte heute brauchen."), sind sofort zu streichen.
8. Keine deutsche Substantivierungsorgie. "Die Umsetzung der Implementierung der Optimierung" ist Tod durch Genitiv. Aktiv, konkret, Verben.
9. Kein Brief-Header im veröffentlichten Text. Interne Brief-Metadaten gehören ins Planungsdokument, niemals in den Artikel-Body. Verboten im Body: `Pillar:`, `Stilmix-Zuordnung`, `Target word count`, `Featured image:`, `Image Brief`, `Internal links: Post N`, sowie die Modus-Begriffe `Analyse-Hausstil`, `Erklär-Modus`, `Haltungs-Modus`, `Schluss-Verdikt`, `Setup-Punchline`. Ein einziger solcher Marker ist ein Auto-Fail. Pflicht-Check vor jedem Publish: `python3 "<repo>/brief-leak-guard.py" --post N` (Exit 0 = clean). Ursprung: Post 493 Toyota, 2026-05-29, kompletter Brief-Block stand live.

(Master locates "Keine Pressemitteilungs-Echos" in Don't 6, nicht Hard Rules. Vendor-Behauptungen einordnen bleibt verbindlich, fällt aber in Don'ts-Galerie.)

### Stilmix bestimmen (vor dem ersten Satz)

| Anteil | Modus | Wann | Vorbild |
|---|---|---|---|
| 70% | **Analyse-Hausstil** | Deals, Markt-Stücke, Trend-Stücke mit These | Stratechery |
| 20% | **Erklär-Modus** | Tech-Erklärstücke, HowTos, Prompt Templates, Vendor-Vergleiche | Pragmatic Engineer |
| 10% | **Haltungs-Modus** | Branchen-Skandale, Compliance, Aufmacher mit Position | Republik |

Strukturelle DNA pro Modus:

- **Analyse:** Setup-Punchline-Aufmacher → These früh → Beweis-Absätze → Implikations-Absatz ("Das heißt für Operator / Verlader / Vendor:")
- **Erklär:** Zwei-Sätze-Definition → drei nummerierte Kernpunkte mit Bold-Leads → praktisches Signal → konkreter nächster Schritt
- **Haltung:** Stark-These im ersten oder zweiten Satz → Aufbau mit Härte → konkrete Namen + Zahlen + Folgen → Verdikt am Schluss

Vorlagen A, B, C im Master-File Abschnitt 11. Jeder Draft folgt einer Vorlage.

### Tonalität in einem Satz

LogiAI ist analytisch (nicht akademisch), scharf (nicht zynisch), meinungsstark (nicht belehrend), trocken-witzig (nicht albern). Humor nur als trockene Pointe am Satzende, maximal einmal pro Stück.

## Instructions

### Step 1 — Load Inputs

1. Read the latest verification report: `logiai-verified-*.md`
2. Only draft stories with status **Verified** or **Partial**
3. For Partial stories, load the specific attribution requirements — they are binding
4. Check the priority class to determine the correct content format

### Step 2 — Select Content Format

Based on Priority Class and content rotation schedule:

| Priority Class | Primary Format | Alternative Format |
|---|---|---|
| Lead Story (80–100) | Full Blog Post (400–800 words) | Newsletter Lead (150–250 words) |
| Supporting Item (60–79) | Newsletter Supporting Item (50–100 words) | Short Blog Post (300–500 words) |
| Spotlight (40–59) | Tool/Startup Spotlight (40–70 words) | Brief mention in roundup |

Also consider the daily content rotation:
- Mon / Wed / Fri: Deep articles (800–1200 words)
- Tuesday: Prompt of the Day
- Thursday: Quick Tip / Job Aid
- Saturday: Industry Roundup

### Step 3 — Draft in the Correct Format

#### Format A: Blog Post (400–800 words, or 800–1200 for deep articles)

Structure:
1. **Headline** — Factual, specific, max 80 characters. No clickbait. State what changed.
2. **Intro paragraph** (2–3 sentences) — What happened and why it matters for logistics professionals
3. **3–5 subheaded sections** — Each subhead is a clear statement, not a question. Each section delivers one key point with supporting evidence.
4. **Practical Implications** — What should the reader consider, try, or watch? Concrete and actionable.
5. **Closing** (1–2 sentences) — Forward-looking without speculation. Link to broader trend if relevant.
6. **SEO Metadata Block** (see Step 5)

#### Format B: Newsletter Lead Story (150–250 words)

Structure:
1. **What happened** (1–2 sentences)
2. **Why it matters** (2–3 sentences) — Direct logistics/transport impact
3. **Logistics impact** (1–2 sentences) — What changes for practitioners

#### Format C: Newsletter Supporting Item (50–100 words)

Structure:
1. **Headline** (1 line)
2. **Context** (2–3 sentences)
3. **Practical takeaway** (1 sentence)

#### Format D: Tool/Startup Spotlight (40–70 words)

Structure:
1. **What it does** (1 sentence)
2. **Who uses it** (1 sentence)
3. **Why relevant now** (1 sentence)

### Step 4 — Apply LogiAI Editorial Standards

**The Three Core Questions — Every draft must answer:**
1. What changed?
2. Why does it matter?
3. What should you try next?

**Tone and Voice:**
- Write as an informed colleague, not an analyst or journalist
- Collaborative, direct, precise
- No unnecessary adjectives or filler
- No em-dashes (use commas, semicolons, or sentence breaks instead)
- Management-ready language: could be forwarded to a VP without editing
- Active voice preferred

**Attribution Rules (mandatory for Partial status stories):**
- Apply every attribution requirement from the verification report
- Use phrases: "according to [source]", "[company] claims", "unconfirmed reports suggest"
- Never present a [CLAIM] or [PROJECTION] as an established [FACT]

**Source Citation:**
- Cite sources inline using natural language: "as reported by [Source Name]"
- Include source URLs in the metadata block
- Minimum 2 cited sources per blog post

### Step 5 — Generate Supporting Assets

For every draft, produce:

**3 Headline Variants:**
1. Factual/descriptive (default)
2. Impact-focused (emphasizes consequence)
3. Action-oriented (emphasizes what to do)

**One-Sentence Teaser:**
- Max 160 characters
- Summarizes the core insight for social sharing or newsletter preview

**SEO Metadata Block:**
```
SEO Title: [Max 60 characters]
Meta Description: [Max 155 characters]
Primary Category: [From LogiAI category system]
Tags: [3–5 relevant tags]
Slug: [URL-friendly version of headline]
```

**Tag Suggestions:**
- Map to LogiAI's five reader-facing categories: Deals & Tenders, Ops & Planning, Visibility & Customer Experience, Rules Risk & Strategy, Tools Startups & Playbooks
- Add 2–3 topic tags (e.g., AI Agents, Warehouse Robotics, Demand Forecasting)

**Featured Image Brief:**
- Produce 2 image option descriptions following the LogiAI image style guide:
  - **REALISTIC PHOTOGRAPHIC** for: operational, company, strategy, and infrastructure topics
  - **FUTURISTIC ILLUSTRATION** for: tools, prompts, and emerging technology topics
  - **HYBRID** for: Visibility & Customer Experience topics
- Each description: 2–3 sentences describing the scene, composition, and mood
- Format: 16:9 aspect ratio

### Step 6 — Compile Draft Package

Assemble the complete draft package:

```
=== LOGIAI DRAFT PACKAGE ===
Story ID: [From verification report]
Tracker ID: [Action Tracker task this draft closes when published, e.g. T093. Use "none" if this draft does not satisfy an existing tracker task. Optional; enables auto-flip-on-publish per the tracker-autoflip spec.]
Draft Date: [YYYY-MM-DD]
Format: [Blog Post / Newsletter Lead / Supporting Item / Spotlight]
Verification Status: [Verified / Partial]
Confidence Score: [From verification report]
Word Count: [Actual count]

--- HEADLINE VARIANTS ---
1. [Factual]
2. [Impact-focused]
3. [Action-oriented]

--- TEASER ---
[One-sentence teaser]

--- DRAFT CONTENT ---
[Full formatted draft]

--- SEO METADATA ---
[SEO block]

--- IMAGE BRIEF ---
Option A: [Description]
Option B: [Description]

--- ATTRIBUTION NOTES ---
[If Partial: list all attribution phrases used and why]

--- SOURCES ---
[Numbered list of all sources with URLs]
```

### Step 7 — Save and Hand Off

- Save the draft package to workspace as `logiai-draft-[YYYY-MM-DD]-[story-id].md`
- The draft package feeds directly into **logiAI-quality-gate** for QC review
- If multiple drafts are produced (e.g., full daily output), save an index file: `logiai-drafts-index-[YYYY-MM-DD].md`

## Critical Rules

- Never publish-ready without QC review — drafts are always "pending approval"
- Attribution requirements from verification are non-negotiable
- Word count limits are hard limits, not guidelines
- The three core questions must be answerable from the draft — if any is missing, the draft is incomplete
- No em-dashes anywhere in the content
- Image briefs are mandatory for every blog post draft

## Notes

- The drafting skill is the creative engine of the pipeline, but it operates within strict editorial guardrails
- Format selection follows priority class unless the Operator overrides
- For the daily content rotation, the format may be adjusted (e.g., a Lead Story on a Tuesday becomes a Prompt-style article if the rotation calls for it)
- When drafting from Partial-status stories, the tone should be slightly more cautious, using more attribution phrases
