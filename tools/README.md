# Publish guard tools

Pre-publish guard scripts referenced by the publishing and drafting skills.

| Script | Status | Purpose |
|---|---|---|
| `../brief-leak-guard.py` | in repo (root) | Rejects brief-header metadata in a post body before publish (post 493 incident) |
| `editorial-visual-inline.py` | in repo | Inlines the `<style>` block of an editorial-visual snippet into per-element styles so WordPress cannot drop it (post 600 incident) |
| `visual-style-guard.py` | in repo | Rejects any `lv-*` figure without inline styles or with a leftover `<style>` block before publish |
| `fal_generate.py` | **IMPORT-ME (optional)** | Featured-image generation and WordPress attach (Phase 6). Lives on the Mac at `/Users/maxxposs/Documents/Logistics LAB/fal_generate.py`; copy it here to enable image generation in cloud runs. Until then, image steps are skipped and flagged in the run summary. |

## Other Mac-local items not yet in the repo (optional imports)

- `memory/logiai_project.md` — editorial strategy context read in Phase 0. Runs proceed without it but with less context.
- `skills/logiai-production-stack/approval-board-template.html` — Approval Board widget template for interactive sessions. Cloud runs use the text/Gmail approval path instead.
