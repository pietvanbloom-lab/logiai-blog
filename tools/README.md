# Publish guard tools

Pre-publish guard scripts referenced by the publishing and drafting skills.

| Script | Status | Purpose |
|---|---|---|
| `../brief-leak-guard.py` | in repo (root) | Rejects brief-header metadata in a post body before publish (post 493 incident) |
| `editorial-visual-inline.py` | **IMPORT-ME** | Inlines the `<style>` block of an editorial-visual snippet into per-element styles so WordPress cannot drop it (post 600 incident) |
| `visual-style-guard.py` | **IMPORT-ME** | Rejects any `lv-*` figure without inline styles or with a leftover `<style>` block before publish |

The two IMPORT-ME scripts live on the operator's Mac at
`/Users/maxxposs/Documents/Logistics LAB/`. Copy them here (MIGRATION.md,
step 1). Until they are present, any publish involving an editorial visual is
a hard escalation — do not publish visuals without these guards.
