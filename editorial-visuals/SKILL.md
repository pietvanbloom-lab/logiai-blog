# LogiAI Editorial Visuals — Skill

Drop-in HTML snippets for LogiAI articles. Each snippet is a self-contained
`<figure>` you paste into a WordPress **Custom HTML block**. No JavaScript,
no build step, no external assets except the Google Fonts import (Playfair
Display, Lora, Inter, Source Code Pro).

## When to reach for a visual

| Story shape | Use |
|---|---|
| "Before vs. after" across 2–6 categories | `bar.html` |
| A metric trending over time | `line.html` |
| One number that carries the article | `stat.html` |
| A direct quote from a source | `pullquote.html` |
| A 3–6 phase journey or rollout | `timeline.html` |
| System diagram: agent + tools + data flow | `arch.html` |
| Spatial layout, paths, before/after route | `map.html` |
| Two-axis quadrant with many points | `scatter.html` |
| Vendor or option comparison, many criteria | `cmp.html` |
| Linear process chain, 4–6 steps | `flow.html` |
| Many-to-many flow with proportional bands | `sankey.html` |
| 2×2 strategic decision matrix | `matrix.html` |
| Annotated UI screenshot with numbered callouts | `annotated.html` |
| 3–4 KPIs side by side as a dashboard card row | `kpi.html` |
| Ranked list of 3–8 items on a single metric | `hbar.html` |
| Part-of-whole: 2–4 segments of a total | `donut.html` |

If none fit, write the visual into the article as body copy. Don't force a
chart onto a story that doesn't have one.

## Language

Each snippet ships with both German and English copy. The active language is
chosen by the `lang` attribute on `<html>` (or any wrapping element):

```html
<html lang="de">  <!-- DE shown, EN hidden -->
<html lang="en">  <!-- EN shown, DE hidden -->
```

WordPress sets `<html lang>` from the site language; multilingual plugins
(Polylang, WPML) set it per post. No further work needed.

## Universal rules

- **Don't change** `viewBox`, the `.lv-*` class names, or the Google Fonts import.
- **Don't add** a third data series, a fourth color, or emoji.
- **Don't translate** — both DE and EN strings are already in the markup;
  edit both.
- **Do change**: titles, decks, captions, axis labels, source attribution,
  numbers, kicker text, the `<span class="num">` index.
- **Numbers**: use German formatting (`12.400`, `1,4 km`) inside `[data-de]`,
  English formatting (`12,400`, `1.4 km`) inside `[data-en]`.
- **Accent color** (`#057dbc`) carries the "what to look at" job. One
  highlighted thing per visual, never two.

---

## bar.html — KPI comparison (grouped bar)

**Use when:** 2–6 categories, two values each (before/after, A/B,
baseline/target). The last category can be highlighted as a "Mean" with a
dashed separator and lighter fill.

**Edit:** `<h3>`, `.deck`, x-labels, eight `<rect>` heights, eight value
labels, annotation, `.foot`.
**Geometry:** y-axis 0–250, height = `(v/250)*224`, top = `238 - height`.
**Don't:** add a third series, exceed 6 categories.

---

## line.html — Trend over time (two-series line)

**Use when:** a metric tracked over a sequence with a moment worth annotating.

**Edit:** `<h3>`, `.deck`, two legend labels, two `<path>` strings, five
x-tick labels, annotation, two endpoint dots.
**Geometry:** viewBox 760×280, `x = 42 + (i/(n-1))*702`, `y = 242 - (v/30)*226`.
**Don't:** add a third line, change the y-axis range.

---

## stat.html — One big number

**Use when:** a single number anchors the article.

**Edit:** `.big`, `.ctx`, three `.meta` cells, `.foot`.
**Don't:** put more than one big number, exceed three meta cells.

---

## pullquote.html — Direct quotation

**Use when:** a source said one thing worth giving real estate. 12–25 words.

**Edit:** `<blockquote>` (no quote marks), `.name`, `.role`, `.ctx`, `.foot`.
**Don't:** include attribution inside the quote, use ALL CAPS.

---

## timeline.html — Phased rollout

**Use when:** 3–6 phase journey with dated beats.

**Edit:** `<h3>`, `.deck`, each `.item`: `.q`, `.t`, `.d`. Mark final phase
as `.item.last` for the accent tick.
**Geometry:** for 3 or 5 items change `grid-template-columns` to match.
**Don't:** mix months and quarters, exceed six phases, add icons.

---

## arch.html — System architecture diagram

**Use when:** explaining an agent + tools + data flow. Three columns
(input → agent → tools), output box, audit trail.

**Edit:** column headers, the agent box title + subtitle, the three tool
box names + subtitles, the input box, the output box, the audit ledger
box, edge labels (`Preis?`, `frei?`, `sicher?`), legend.
**Geometry:** viewBox 760×380. Fixed columns at x=180 / x=440. Boxes are
`<rect>` with explicit x/y/w/h; inline `<text>` follows each rect.
**Don't:** add a fourth tool (use a generic "Other tools" box), draw
icons inside boxes, deepen the agent box past two label lines.

---

## map.html — Spatial layout with paths

**Use when:** before/after of a physical route, warehouse pick path, fleet
detour. Two paths (baseline dashed, optimized solid accent) overlaid on a
shelf grid.

**Edit:** legend distances, the two `<path d="...">` strings, the 14
numbered pick circles' `cx`/`cy`, axis caption ("Aisle A · 28 m"), savings
label top-right, `.foot`.
**Geometry:** 7×5 grid of shelf rects (`94.86 × 52`, gaps 8px). Pick
indices 1–14 are routed by row in baseline (snake), by nearest-neighbor
in optimized.
**Don't:** add a third path, change the grid dimensions, swap the start
marker color.

---

## scatter.html — Two-axis quadrant with many points

**Use when:** comparing 8–14 options on two metrics (accuracy vs cost,
effort vs value). Points sized by a third dimension.

**Edit:** axis labels, the dozen `<circle>` + label pairs (mark the
"winners" as `fill="#057dbc"`), median crosshair position, sweet-spot
bracket, four quadrant labels, size legend.
**Geometry:** viewBox 760×380. Plot area `54..742` × `24..308`. Y is
linear over the labelled range; X is log-scaled with five decade ticks.
Point: `x = 54 + xv*688`, `y = 308 - ((yv-ymin)/yrange)*284`,
`r = effort * 0.85 + 4`.
**Don't:** highlight more than three points, omit the size legend, place
labels outside the plot area.

---

## cmp.html — Vendor / option comparison table

**Use when:** evaluating 2–4 named options across many criteria, grouped
into 2–4 sections (Performance / Integration / Commercial, etc.).

**Edit:** column headers (`<th class="col">`), `td.grp` group titles,
each row's `td.k` criterion + `td.v` cells. Mark the winning cell per row
with `class="v best"` — at most one per row, ideally not always the same
column.
**Don't:** more than four columns, more than ten rows total, more than
one `best` per row.

---

## flow.html — Process chain

**Use when:** linear 4–6-step process where some steps are automated and
some are human-in-the-loop. Dark fill = automated, white fill = human.

**Edit:** `<h3>`, `.deck`, legend, each step's `Step N` label + title +
sub-label. For 5 steps change the layout from 6 (`stepW=120`) to 5
(`stepW=144`); recompute x positions.
**Don't:** branch the chain (use `arch.html` instead), exceed six steps,
use the accent fill (it's reserved for the step number).

---

## sankey.html — Many-to-many flow with proportional bands

**Use when:** showing how volume splits from N origins to M destinations
(supplier → region, channel → cohort). Bands are proportional to volume.
The largest target band uses accent fill.

**Edit:** node labels left and right, source totals, target totals,
ribbon paths (each is a four-point bezier defined by source band y-range
and target band y-range), bottom caption.
**Geometry:** viewBox 760×420. Source nodes at `x=130, w=8`; target nodes
at `x=622, w=8`. Inner height 348 minus per-axis gaps; height scale =
`innerH / total`. Ribbons sweep from `(138, sy)` to `(622, ty)` with
control points at `x=380`.
**Don't:** add a middle column (use stacked sankey only when needed),
exceed five source × five target, color more than the dominant target.

---

## matrix.html — 2×2 strategic decision

**Use when:** classic Make/Buy, Build/Defer, Now/Later quadrant. One
quadrant gets the accent fill (the recommended cell).

**Edit:** axis labels (`xh` and `yh`), four `.q` blocks: tag + title +
body. The accent quadrant should be the one your article actually
recommends.
**Don't:** more than four cells, more than one accented cell, axis
labels longer than three words.

---

## annotated.html — UI screenshot with numbered callouts

**Use when:** explaining "how this product surfaces information"
(decision page, agent reasoning UI, dashboard). A simulated browser
window on the left, four numbered callouts on the right.

**Edit:** the URL in the title bar, `.ord` (order summary), the primary
recommendation card (`.lbl`, `.car`, `.meta`), the `.why` reasoning
paragraph, the two `.alt` alternatives, the action buttons, and the
four numbered notes on the right.
**Geometry:** the four `.co` callout circles are absolutely-positioned
on the UI mockup; `.notes .item` rows on the right share the same
numbering. Max four callouts.
**Don't:** mock real product UIs you don't have permission to depict,
exceed four callouts, use real screenshots inside this snippet.

---

## kpi.html — KPI card row

**Use when:** 3–4 key metrics displayed side by side as a dashboard callout
(on-time delivery, forecast accuracy, cost per order, acceptance rate).

**Edit:** `<h3>`, each `.card`: `.lbl`, `.val`, `.delta` (add class `up` for
green / `dn` for red). Cards are a CSS grid; four is default, three works
without changes.
**Don't:** exceed four cards, put more than one `.val` per card, use the
accent color on `.delta` (up/down have their own semantic colors).

---

## hbar.html — Horizontal bar ranking

**Use when:** a ranked list of 3–8 items on a single metric (barriers,
cost drivers, survey responses). The top item is accent-filled; the rest use
ink.

**Edit:** `<h3>`, `.deck`, each row's `<text>` label + `<rect>` width + value
label. Top bar is `fill="#057dbc"`, second bar `fill="#1a1a1a"`, subsequent
bars may use `fill="#4a4a4a"` (lighter).
**Geometry:** viewBox 700×280. Label area ends at x=186; bar area runs
x=194 to x=638 (444px = 100%). Bar height 22px, rows 50px apart starting
at y=33. Width = `(pct/100)*444`.
**Don't:** exceed eight rows, add a second data series, sort ascending.

---

## donut.html — Distribution / part-of-whole

**Use when:** 2–4 segments of a whole (market share, digitization level,
mode split). One segment is highlighted in accent; use ink for the largest
"other" segment.

**Edit:** `<h3>`, `.deck`, three `<circle>` elements: adjust
`stroke-dasharray` (first number = `circumference * pct`) and
`stroke-dashoffset` (cumulative prior lengths, negated). Update center label
(`.big` + `.ctx`), legend text and values, `.foot`.
**Geometry:** cx=152, cy=148, r=82, stroke-width=42.
Circumference = 515 px. Dashoffset for segment N = -(sum of all prior
segment lengths).
**Don't:** exceed four segments, change ring geometry, add a legend icon
other than the color swatch rect.

---

## Workflow for an automated agent

1. Read the article draft. Identify the dominant story shape (comparison,
   trend, headline number, quote, rollout, system, route, quadrant,
   table, process, flow, decision, UI explanation).
2. Pick the matching snippet from `visuals/`. If multiple fit, prefer
   simpler reads: `stat` > `pullquote` > `kpi` > `bar` > `hbar` >
   `donut` > `matrix` > `cmp` > `line` > `timeline` > `flow` >
   `arch` > `scatter` > `map` > `sankey` > `annotated`.
3. Open the file, copy its full contents (including `<style>`).
4. Paste into a WordPress Custom HTML block at the right spot.
5. Edit the marked spots in place. Both `[data-de]` and `[data-en]`
   must be updated; never delete one language.
6. Verify the figure renders at ~760px column width before publishing.

## File layout

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
    ├── arch.html        ← agent + tools architecture
    ├── map.html         ← warehouse pick path before/after
    ├── scatter.html     ← accuracy-vs-cost quadrant
    ├── cmp.html         ← vendor comparison table
    ├── flow.html        ← linear process chain
    ├── sankey.html      ← origin → destination flow
    ├── matrix.html      ← 2×2 decision matrix
    ├── annotated.html   ← UI screenshot with callouts
    ├── kpi.html         ← 3–4 KPI cards side by side
    ├── hbar.html        ← horizontal bar ranking
    └── donut.html       ← part-of-whole distribution
```

Snippets are independent — copying one does not require the others.
