# LogiAI Editorial Visuals

Self-contained HTML snippets for LogiAI articles. Paste into a WordPress
**Custom HTML block**. No JavaScript, no build step.

Five visuals in this first set:

- `bar.html` — KPI comparison (grouped bar)
- `line.html` — Trend over time
- `stat.html` — One big number
- `pullquote.html` — Direct quotation
- `timeline.html` — Phased rollout

## Quick start

1. Open `visuals/<id>.html`.
2. Copy the whole file contents.
3. In WordPress: **Add Block → Custom HTML**, paste, **Preview**.
4. Edit text and numbers in place. Save.

## Bilingual

Each snippet contains both German and English copy. The active language
is chosen by the `lang` attribute on `<html>`:

```html
<html lang="de">   <!-- DE only -->
<html lang="en">   <!-- EN only -->
```

WordPress sets this from the site/post language. Polylang and WPML both
work out of the box. **Always update both languages** when editing — never
delete one.

## What you can change, what you can't

✅ **Edit**: titles, decks, captions, axis labels, source lines, numbers,
the `<span class="num">` index, the bar/line geometry to fit your data.

❌ **Don't**: change the `viewBox`, rename `.lv-*` classes, swap fonts, add
emoji, add a third data series, change the accent color (`#057dbc`) without
talking to design.

## Design discipline

These snippets enforce the LogiAI editorial system:

- Playfair Display for titles and big numbers
- Lora for body and italic decks
- Inter for UI-ish names
- Source Code Pro for kickers, axes, ALL CAPS metadata
- One accent (`#057dbc`), four grays, ink black, paper white
- 2px black border top/bottom — newsstand frame
- No animation, no hover states, no decorative SVG

If a story needs something the system doesn't cover, ask design for a new
snippet rather than bending an existing one.

## For agents

See `SKILL.md` for the decision tree, per-visual edit rules, and a tiny
example for each snippet.

## Roadmap

Held back for v2 — pending a design pass:
`arch`, `flow`, `sankey`, `matrix`, `scatter`, `map`, `cmp`, `annotated`.

## Source

Snippets are pre-rendered from the React design system at
[the Editorial Visuals canvas](../). The canvas is the source of truth for
geometry; this repo is the production-ready paste-able output.
