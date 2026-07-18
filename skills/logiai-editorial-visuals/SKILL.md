---
name: logiai-editorial-visuals
description: >-
  Insert self-contained HTML visual snippets into LogiAI articles. Use when
  the user says "add a chart", "insert a visual", "add a stat block", "add a
  pull quote", "add a timeline", "add a KPI row", "add a bar chart", "add a
  ranking", "add a donut", "add a comparison table", "add an architecture
  diagram", "add a flow chart", "add a sankey", "add a scatter", "add a matrix",
  "add a map", "add a callout", "editorial visual", or when an article draft
  contains a comparison, trend, headline number, direct quote, phased rollout,
  system diagram, route map, ranking, distribution, or process chain that would
  benefit from a visual treatment. Picks the right snippet from the
  editorial-visuals library, populates it with article data, and outputs
  ready-to-paste WordPress Custom HTML block code.
metadata:
  author: LogiAI
  version: '2.0'
---

# LogiAI Editorial Visuals — Agent: Visual Insertion

Drop-in HTML snippets for LogiAI articles. Each snippet is a self-contained
`<figure>` you paste into a WordPress **Custom HTML block**. No JavaScript,
no build step, no external assets except the Google Fonts import (Playfair
Display, Lora, Inter, Source Code Pro). All snippets are bilingual DE/EN and
fully mobile-responsive.

## Styleguide Compliance (applies to all labels, captions, alt-texts)

**Master file:** `<repo>/CONTENT-STYLEGUIDE.md`

A chart is editorial content. Every label, axis title, legend, caption, pull-quote, and alt-text is subject to the same rules as body copy.

### Hard Rules für Visuals

1. **Keine em-dashes (`—`)** in Titles, Subtitles, Axis-Labels, Legends, Captions, Annotations, Tooltips. Ersatz: Komma, Doppelpunkt, Punkt, Klammern.
2. **Keine en-dashes (`–`)** außer in Zahlenbereichen auf Achsen (`2024-2026` ist ok, "Cost-saving" nicht).
3. **Keine Emojis** in Visual-Elementen. Eyebrow-Tags wie "Stat" oder "Trend" als Text setzen, nicht als Symbol.
4. **Keine generischen KI-Floskeln** in Captions oder Titles. Verbotsliste (Master Hard Rule 4): "im Zeitalter von", "Game-Changer", "revolutionär", "transformative", "deep dive", "robust", "leverage", "synergistisch", "End-of-Day", "an der Spitze der Innovation".
5. **Keine PR-Sprache** in Legend-Labels. "Marktführer" → "Anbieter mit höchstem Marktanteil". "Innovativ" → konkreter Begriff oder streichen.

### Zahlenformate (sprachkonsistent zur Artikelsprache)

- **Deutscher Artikel:** Tausendertrennzeichen = Punkt, Dezimalkomma. Beispiele: `12.400`, `1,4 km`, `73,5%`.
- **Englischer Artikel:** Tausendertrennzeichen = Komma, Dezimalpunkt. Beispiele: `12,400`, `1.4 km`, `73.5%`.
- **Prozent:** Im Fließtext "12 Prozent" oder "12%", in Charts immer `%`-Symbol.
- **Keine fake-präzisen Zahlen** (`ca. 73,4%`). Bei Schätzung: "rund 70%". Wenn es exakt ist: exakte Quelle in der Caption.

### Caption- und Source-Regeln

- **Caption ist ein vollständiger Satz** mit eigener These, kein Stichwort-Tag.
- **Source-Line** unter jeder Grafik: Quelle, Datum, n bei Studien. Format: "Quelle: BCG Survey Januar 2026, n=180 Logistikdienstleister, DACH + Nordamerika."
- **Vendor-Sponsored-Markierung** verpflichtend wenn Studie vom Anbieter selbst kommt.

### Alt-Text-Regeln

Beschreibend, kein Keyword-Stuffing. "Balkendiagramm: Marktanteile Visibility-Plattformen 2026, project44 führt mit 34%", nicht "AI logistics future innovation transformation chart".

### Title- und Pullquote-Regeln

- **Chart-Title** ist eine These oder ein konkretes Faktum, keine Frage und kein "Trend" allein. Schlecht: "Visibility-Markt im Wandel". Gut: "project44 verliert Marktanteile an Spezialisten, 2024-2026".
- **Pullquote-Snippet:** 12-25 Wörter, mit Distanz formuliert oder direkt zitiert + Quelle. Keine PR-Phrasen unkommentiert lassen.

## When to Reach for a Visual

| Story shape | Use |
|---|---|
| "Before vs. after" across 2–6 categories | `bar.html` |
| A metric trending over time | `line.html` |
| One number that carries the article | `stat.html` |
| A direct quote from a source | `pullquote.html` |
| A 3–6 phase journey or rollout | `timeline.html` |
| 3–4 KPIs side by side as a dashboard card row | `kpi.html` |
| Ranked list of 3–8 items on a single metric | `hbar.html` |
| Part-of-whole: 2–4 segments of a total | `donut.html` |
| System diagram: agent + tools + data flow | `arch.html` |
| Spatial layout, paths, before/after route | `map.html` |
| Two-axis quadrant with many points | `scatter.html` |
| Vendor or option comparison, many criteria | `cmp.html` |
| Linear process chain, 4–6 steps | `flow.html` |
| Many-to-many flow with proportional bands | `sankey.html` |
| 2×2 strategic decision matrix | `matrix.html` |
| Annotated UI screenshot with numbered callouts | `annotated.html` |

If none fit, write the visual into the article as body copy. Don't force a
chart onto a story that doesn't have one.

**Priority when multiple snippets fit** (simpler reads better):
`stat` > `pullquote` > `kpi` > `bar` > `hbar` > `donut` > `matrix` > `cmp` >
`line` > `timeline` > `flow` > `arch` > `scatter` > `map` > `sankey` > `annotated`

## Instructions

### Step 1 — Identify the Visual Type

Read the article draft. Look for the dominant story shape:
- **Headline number** (one statistic anchors the article) → `stat`
- **Direct quote** (12–25 words, named source) → `pullquote`
- **Dashboard metrics** (3–4 KPIs side by side) → `kpi`
- **Grouped comparison** (before/after, A/B across 2–6 categories) → `bar`
- **Ranking** (3–8 items on one metric) → `hbar`
- **Distribution** (part-of-whole, 2–4 segments) → `donut`
- **Trend** (metric over time with annotatable moment) → `line`
- **Phased rollout** (3–6 named phases with dates) → `timeline`
- **Process chain** (4–6 linear steps, human vs. automated) → `flow`
- **System diagram** (agent + tools + data flow) → `arch`
- **Vendor/option table** (2–4 options, many criteria) → `cmp`
- **2×2 decision** (Make/Buy, Build/Defer quadrant) → `matrix`
- **Quadrant scatter** (8–14 options on two axes) → `scatter`
- **Route / spatial** (warehouse pick path before/after) → `map`
- **Origin→destination flow** (proportional bands) → `sankey`
- **UI explanation** (annotated screenshot with callouts) → `annotated`

If no shape fits: skip the visual, write the insight as body copy.

### Step 2 — Read the Snippet

Open the matching file from `editorial-visuals/visuals/` and copy its full
contents including `<style>`.

### Step 3 — Edit the Snippet

Apply article data to all marked spots.

**Universal rules:**
- Never change `viewBox`, `.lv-*` class names, or the Google Fonts import
- Never add a third data series, a fourth color, or emoji
- Always update both `[data-de]` and `[data-en]` spans — never delete one language
- German formatting inside `[data-de]`: `12.400`, `1,4 km`
- English formatting inside `[data-en]`: `12,400`, `1.4 km`
- Accent color `#057dbc` carries the "what to look at" signal — one highlighted
  element per visual, never two

---

### bar.html — KPI Comparison

**Edit:** `<h3>`, `.deck`, x-labels, `<rect>` heights/y-offsets, value labels,
annotation, `.foot`.
**Geometry:** y-axis 0–250. Height = `(v/250)*224`. Top = `238 - height`.
**Don't:** add a third series, exceed 6 categories.

---

### line.html — Trend Over Time

**Edit:** `<h3>`, `.deck`, two legend labels, two `<path d="...">`, five
x-tick labels, annotation, two endpoint dots.
**Geometry:** viewBox 760×280. `x = 42 + (i/(n-1))*702`. `y = 242 - (v/30)*226`.
**Don't:** add a third line, change the y-axis range.

---

### stat.html — One Big Number

**Edit:** `.big`, `.ctx`, three `.meta` cells, `.foot`.
**Don't:** put more than one big number, exceed three meta cells.

---

### pullquote.html — Direct Quotation

**Edit:** `<blockquote>` (no quote marks), `.name`, `.role`, `.ctx`, `.foot`.
**Don't:** include attribution inside the quote, use ALL CAPS.

---

### timeline.html — Phased Rollout

**Edit:** `<h3>`, `.deck`, each `.item`: `.q`, `.t`, `.d`. Mark final phase
`.item.last`.
**Geometry:** for 3 or 5 items change `grid-template-columns` to match.
**Don't:** mix months and quarters, exceed six phases, add icons.

---

### kpi.html — KPI Card Row

**Edit:** `<h3>`, each `.card`: `.lbl`, `.val`, `.delta` (class `up`=green /
`dn`=red). Four cards default; three works without layout changes.
**Don't:** exceed four cards, more than one `.val` per card.

---

### hbar.html — Horizontal Bar Ranking

**Edit:** `<h3>`, `.deck`, each row's label `<text>` + `<rect>` width + value.
Top bar `fill="#057dbc"`, rest `fill="#1a1a1a"` or `"#4a4a4a"`.
**Geometry:** viewBox 700×280. Bar area x=194 to x=638 (444px = 100%).
Bar height 22px, rows 50px apart from y=33. Width = `(pct/100)*444`.
**Don't:** exceed eight rows, sort ascending, add a second series.

---

### donut.html — Distribution / Part-of-Whole

**Edit:** `<h3>`, `.deck`, three `<circle>` elements: `stroke-dasharray`
first number = `515 * pct`, `stroke-dashoffset` = -(sum of all prior lengths).
Update center label, legend text/values, `.foot`.
**Geometry:** cx=152, cy=148, r=82, stroke-width=42. Circumference = 515 px.
**Don't:** exceed four segments, change ring geometry.

---

### arch.html — System Architecture

**Edit:** column headers, agent box title/subtitle, three tool box names,
input/output boxes, audit box, edge labels, legend.
**Don't:** add a fourth tool, draw icons, deepen the agent box past two lines.

---

### map.html — Spatial Route / Pick Path

**Edit:** legend distances, two `<path>` strings, 14 pick circle positions,
axis caption, savings label, `.foot`.
**Don't:** add a third path, change grid dimensions.

---

### scatter.html — Two-Axis Quadrant

**Edit:** axis labels, `<circle>` + label pairs (winners `fill="#057dbc"`),
crosshair, sweet-spot bracket, quadrant labels, size legend.
**Don't:** highlight more than three points, omit size legend.

---

### cmp.html — Vendor / Option Comparison Table

**Edit:** column `<th class="col">`, group `td.grp` titles, row `td.k` +
`td.v` cells. Mark winning cell with `class="v best"` — at most one per row.
**Don't:** more than four columns, more than ten rows, more than one `best`
per row.

---

### flow.html — Process Chain

**Edit:** `<h3>`, `.deck`, legend, each step's label + title + sub-label.
Dark fill = automated, white fill = human.
**Don't:** branch the chain (use `arch.html`), exceed six steps.

---

### sankey.html — Many-to-Many Flow

**Edit:** node labels, source/target totals, ribbon paths (four-point bezier),
caption. Dominant target band uses accent fill.
**Geometry:** viewBox 760×420. Source nodes x=130; target nodes x=622.
Control points at x=380.
**Don't:** exceed five source × five target, color more than the dominant band.

---

### matrix.html — 2×2 Decision Matrix

**Edit:** axis labels, four `.q` blocks: tag + title + body. One quadrant
gets accent fill — the one your article actually recommends.
**Don't:** more than four cells, more than one accented cell.

---

### annotated.html — UI Screenshot with Callouts

**Edit:** URL, order summary `.ord`, recommendation card, `.why` reasoning,
`.alt` alternatives, action buttons, four numbered callout notes.
**Don't:** mock real product UIs you don't have permission to depict, exceed
four callouts.

---

### Step 4 — Output

Output the complete, edited snippet as a code block. Include the full
`<style>` tag and the `<figure>` element. Ready to paste into a WordPress
Custom HTML block (manual paste by an admin keeps the `<style>` block).

**For automated / REST publishing (the production stack), inline first.**
WordPress can drop the `<style>` block on a REST publish, which renders the
visual as unstyled plain text (post-600 drift, 2026-06-29). Before the visual
goes into a REST post body, convert it to inline-styled, English-only HTML:

```
python3 "<repo>/tools/editorial-visual-inline.py" <snippet.html> --lang en -o <inlined.html>
```

The inliner resolves `:lang()` bilingual spans to English, bakes CSS custom
properties (`var(--accent)` to `#057dbc`) into literals, inlines every rule as a
`style="..."` attribute, and strips the `<style>` block. Then run
`visual-style-guard.py` on the body before publishing. Never POST a
`<style>`-block visual via REST.

### Step 5 — Placement Note

State where in the article the visual belongs:
- `stat`, `pullquote`, `kpi`: immediately after the paragraph introducing the number/quote/metrics
- `bar`, `hbar`, `line`, `donut`, `scatter`: after the section discussing the data
- `timeline`, `flow`: at the end of the rollout or process section
- `arch`, `map`, `sankey`: after the section explaining the system or route
- `cmp`, `matrix`: at the start or end of a decision/comparison section
- `annotated`: immediately after the paragraph introducing the UI

## File Layout

```
editorial-visuals/
├── SKILL.md
├── README.md
└── visuals/
    ├── bar.html         ← grouped bar comparison
    ├── line.html        ← two-series trend
    ├── stat.html        ← one big number
    ├── pullquote.html   ← direct quotation
    ├── timeline.html    ← 4-phase rollout
    ├── kpi.html         ← 3–4 KPI cards side by side
    ├── hbar.html        ← horizontal bar ranking
    ├── donut.html       ← part-of-whole distribution
    ├── arch.html        ← agent + tools architecture
    ├── map.html         ← warehouse pick path before/after
    ├── scatter.html     ← accuracy-vs-cost quadrant
    ├── cmp.html         ← vendor comparison table
    ├── flow.html        ← linear process chain
    ├── sankey.html      ← origin → destination flow
    ├── matrix.html      ← 2×2 decision matrix
    └── annotated.html   ← UI screenshot with callouts
```

Snippets are independent — using one does not require the others.

## Critical Rules

- **Bilingual always** — both DE and EN strings must be present and updated
- **No bending existing snippets** — if the story needs something the system
  doesn't cover, flag it rather than modifying geometry or adding series
- **Accent color is fixed at `#057dbc`** — no exceptions without design sign-off
- **No animation, no hover states, no decorative SVG** — editorial system rule
- **Mobile-first** — all snippets are responsive; SVG-heavy ones scroll
  horizontally on screens narrower than 480px
