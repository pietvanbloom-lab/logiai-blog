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

---

## bar.html — KPI comparison (grouped bar)

**Use when:** 2–6 categories, two values each (before/after, A/B,
baseline/target). The last category can be highlighted as a "Mean" with a
dashed separator and lighter fill — keep that pattern if you have one.

**Edit:** `<h3>` title, `.deck`, the four `<text>` x-labels, the eight
`<rect>` heights/y-offsets, the eight value labels above each bar, the
annotation text and its leader line, the `.foot` source and caption.

**Geometry:** y-axis is 0–250, height = `(value/250) * 224`, top = `238 - height`.

**Tiny example — DE:** "Pick-Path-Optimierung senkt Laufwege um 23%" with
sites A/B/C/D + Mittel; baseline 212/198/184/226/205, with-agent 164/153/171/158/162.

**Don't:** add a third series, exceed 6 categories, change the accent.

---

## line.html — Trend over time (two-series line)

**Use when:** a metric tracked over a sequence (weeks, quarters, releases),
ideally with a moment in the middle worth annotating.

**Edit:** `<h3>` title, `.deck`, the two legend labels, the two `<path d="...">`
strings, the five x-axis tick labels (`KW01`, `KW14`, ...), the annotation
text and dashed marker x-position, the two endpoint dot positions and labels.

**Geometry:** viewBox 760×280, x = `42 + (i/(n-1)) * 702`, y = `242 - (v/30) * 226`.

**Tiny example — DE:** "Von MAPE 18% auf 9% in vier Quartalen" — ARIMA
baseline staying near 18%, LLM ensemble dropping from 18 to 9, both spiking
at week 22 (Black Friday) where the annotation lives.

**Don't:** add a third line, change the y-axis range, remove the Black Friday
marker if your story has any seasonality moment to call out.

---

## stat.html — One big number

**Use when:** a single number anchors the article and you want it big.
Headline-adjacent or pull-callout.

**Edit:** the `.big` number itself, `.ctx` sentence below it, the three
`.meta` cells (label + value pairs — sites, throughput, observation window
by default), `.foot` source and caption.

**Tiny example — DE:** "23%" with "weniger Laufweg pro Auftrag, nachdem
ein Routing-Agent in drei DACH-Lagern scharfgeschaltet wurde." Meta cells:
Standorte = 3, Aufträge/Tag = 12.400, Beobachtung = 90 Tage.

**Don't:** put more than one big number, exceed three meta cells, use the
accent color anywhere except the big number.

---

## pullquote.html — Direct quotation

**Use when:** a source said one thing worth giving its own real estate.
12–25 words. Longer quotes belong in the body.

**Edit:** the `<blockquote>` text (no quote marks — the giant `&ldquo;`
provides them), `.name`, `.role`, `.ctx` (one-sentence background on when
the quote was said), `.foot` source.

**Tiny example — DE:** „Der Agent hat nicht das Modell gewonnen, er hat
den Dispatcher überzeugt." — Anna Weiß, Head of Ops, mittelständischer 3PL.

**Don't:** include attribution inside the blockquote, use ALL CAPS, exceed
two lines on desktop.

---

## timeline.html — Phased rollout

**Use when:** a 3–6 phase journey with quarterly or named beats. Each
phase = one date label + one short title + one sentence.

**Edit:** the `<h3>`, `.deck`, then for each `.item`: `.q` (date label),
`.t` (phase title, 1–3 words), `.d` (one sentence). Mark the final phase
as `.item.last` so its tick and date pick up the accent.

**Geometry:** if you have 3 or 5 items instead of 4, change `grid-template-columns`
to `repeat(3,1fr)` or `repeat(5,1fr)`.

**Tiny example — DE:** Q2 2025 Prototyp → Q3 Pilot → Q4 Rollout A → Q1 2026
Regelbetrieb. Each phase one Lora sentence; "Regelbetrieb" gets the accent.

**Don't:** mix months and quarters, exceed six phases, add icons.

---

## Workflow for an automated agent

1. Read the article draft. Identify whether it has a **comparison**, a
   **trend**, a **headline number**, a **quote**, or a **rollout**.
2. Pick the matching snippet from `visuals/`. If multiple fit, prefer
   `stat` > `pullquote` > `bar` > `line` > `timeline` (simpler reads better).
3. Open the file, copy its full contents (including `<style>`).
4. Paste into a WordPress Custom HTML block at the right spot in the article.
5. Edit the marked spots in place. Both `[data-de]` and `[data-en]` spans
   must be updated; never delete one language.
6. Verify the figure renders at 760px-ish column width before publishing.

## File layout

```
editorial-visuals/
├── SKILL.md              ← this file
├── README.md             ← human-facing tour
└── visuals/
    ├── bar.html
    ├── line.html
    ├── stat.html
    ├── pullquote.html
    └── timeline.html
```

Snippets are independent — copying one does not require the others.
