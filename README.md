# LogiAI Blog

Repository für [logiai.blog](https://logiai.blog) — Blog über KI in der Logistik.

Seit Juli 2026 ist dieses Repo die **Single Source of Truth für den gesamten
LogiAI-Produktionsstack**: Site-Code, Editorial-Pipeline-Skills, Konfiguration,
Memory und Publish-Guards laufen von hier (Betriebsmodell wie beim Business
Intelligence Hub). Details: `MIGRATION.md`.

## Struktur

### Produktionsstack (läuft aus dem Repo)
- `.editorial-engine/portal.yaml` — Portal-Konfiguration (Themen, Quellen, Scoring, Quality Gate, Publishing-Adapter, Approval)
- `skills/` — die LogiAI-Pipeline-Skills (Strategy, Monitoring, Scoring, Verification, Drafting, Editorial Visuals, Quality Gate, Publishing, Compliance, Performance, Daily-Orchestrator)
- `memory/logiai_published.md` — Publish-Historie (interne Links, Dubletten-Check)
- `tracker/` — LogiAI Action Tracker
- `runs/` — Run-Logs und Cap-History jedes Produktionslaufs
- `workspace/` — Arbeitsdateien pro Lauf (Kandidaten, Drafts, QC-Reports)
- `tools/` + `brief-leak-guard.py` — Pre-Publish-Guards
- `CONTENT-STYLEGUIDE.md` — Master-Styleguide (Hard Rules, Ton, Formate)

### Site-Code
- `logiai-theme/` — WordPress-Theme (synchron mit live)
- `logiai-tts-v4.0/` — TTS-Plugin v4.0 (pre-generated audio); `logiai-tts-plugin/` v3.1, `logiai-tts-proxy/` Cloudflare-Worker-Proxy
- `editorial-visuals/` — Bibliothek der 16 Editorial-Visual-Snippets
- `DESIGN.md` — Design-System (Apple-inspired); `index.html`, `logiai-mockup.html` — Mockups

## Betrieb
- Täglicher Produktionslauf: Cloud-Routine 07:00 CET (siehe `MIGRATION.md`, Schritt 2)
- Hard Rules: kein Auto-Publish ohne Operator-Approval, keine Em-Dashes im Content, Guards müssen Exit 0 liefern

## Stack
- WordPress auf logiai.blog (Hosting + CMS)
- Pipeline: Claude (Cloud-Routine / Cowork) + dieses Repo
- Design-Referenz: `DESIGN.md`
