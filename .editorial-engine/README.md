# .editorial-engine

Portal configuration that lets the editorial-engine / LogiAI skills run this
blog's production stack directly from the repo.

- `portal.yaml` — the single source of truth for themes, sources, scoring,
  quality gate, publishing adapter (wordpress-rest), approval routing, and
  repo-relative state paths.

Runtime caps and model assignments live with the orchestrator at
`skills/logiai-production-stack/config.yml`.
