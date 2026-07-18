---
name: logiai-production-stack
description: End-to-end production stack for logiai.blog. Orchestrates Action Tracker triage, full editorial pipeline (Monitoring, Scoring, Verification, Drafting, QC, Compliance), Performance Review, and Memory hygiene. Hard-stops before every publish for operator approval. Use when the user says "LogiAI production run", "run LogiAI stack", "process LogiAI backlog", "LogiAI daily routine", "LogiAI weekly run", or when scheduled to run automatically.
trigger: on-demand + scheduled (daily 07:00 CET via scheduled-tasks)
---

# LogiAI Production Stack

End-to-end orchestrator for logiai.blog. Runs Action Tracker, editorial pipeline, and memory hygiene in one coherent workflow. **Never auto-publishes.**

## Inputs

| Input | Path / Source | Purpose |
|-------|---------------|---------|
| **Runtime Config** | `~/Documents/Claude/Scheduled/logiai-production-stack/config.yml` | **Required.** Model assignments per phase, hard caps, drift guard, loop detection. Read FIRST. |
| Action Tracker | `/Users/maxxposs/Documents/Logistics LAB/LogiAI Action Tracker.html` | Open tasks, parsed from `EMBEDDED_TASKS` array |
| Content Register | `memory/logiai_published.md` | Duplicate-check, content history |
| LogiAI Project Memory | `memory/logiai_project.md` | Editorial strategy context |
| Cowork Sessions | `mcp__session_info__list_sessions` (last 30) | Detect new in-flight work |
| WordPress REST API | `https://logiai.blog/wp-json/wp/v2/` | Live post sync, publishing target |

## Runtime Config (config.yml)

Every run starts by reading `config.yml`. It controls:

- **Model assignment** per phase and sub-task (Haiku for structured work, Sonnet for reasoning and writing, Opus only for rare escalations).
- **Hard caps** per mode: total USD, total tool calls, total wall-clock, plus per-phase and per-draft sub-caps. Whichever cap triggers first stops the current phase.
- **Goal-drift guard**: before each phase the skill writes a 1-2 sentence self-check comparing the phase goal vs the current action. Mismatch stops the run.
- **Loop detection**: if the same tool with substantially the same arguments fires more than 4 times in a phase, abort the phase.
- **Telemetry**: every run appends actuals to `runs/cap-history.csv` for later cap tuning.

Operator can edit `config.yml` between runs without touching this SKILL.md.

## Modes

Skill checks `$ARGUMENTS` for mode flag. Default mode is `daily`.

- `daily` (default): Phases 0, 1, 2, 3, 4, 5, 7. Skip performance review.
- `weekly`: All phases 0 to 8. Run Sundays 09:00 CET.
- `tracker-only`: Phases 0, 1, 7. Quick action sweep.
- `content-only`: Phases 0, 2, 3, 4, 5, 7. Skip tracker triage.

## Phase 0: Pre-flight (always)

1. **Read `config.yml`** first. Parse model assignments, caps for current mode, drift/loop settings.
2. Initialize run state: counters at 0 (tool_calls, wall_seconds, estimated_usd), per-phase and per-draft trackers ready.
3. **Read queue folder** `~/Documents/Claude/Scheduled/logiai-production-stack/queue/*.md`. If files exist that match today's date or are date-less, treat them as operator pre-approved work packages for this run. Each file describes a one-shot instruction set, scope of autonomy, and post-execution cleanup (usually "delete this queue file once executed"). Honor scope strictly: pre-approval applies only to the work package described in the file, never extended to other actions.
4. Read `MEMORY.md` index, `memory/logiai_published.md`, `memory/logiai_project.md`.
5. Read Action Tracker HTML, parse `EMBEDDED_TASKS` array (regex on the `const EMBEDDED_TASKS = [` block until matching `];`).
6. Filter to `status: "Open"`, sort by priority (High, Medium, Low), then by `date_added` ascending.
7. Check current WordPress REST API access: `curl -s https://logiai.blog/wp-json/wp/v2/posts?per_page=1`. If 401, stop and escalate (T021 unresolved).
8. Log run start to `~/Documents/Claude/Scheduled/logiai-production-stack/runs/YYYY-MM-DD_HHMM.log` with config snapshot.
9. **Write Phase-0 drift-guard line** to the log: goal, current action, aligned=yes/no.

## Phase 1: Action Tracker Sweep (modes: daily, weekly, tracker-only)

For each Open task, sorted by priority:

1. **Classify task type** from `type` field: Bug, SEO, Tech, Content, Design.
2. **Pick handler**:
   - `SEO` → use `searchfit-seo` plugin skills: `seo-audit` for audits, `on-page-seo` for single-page fixes, `internal-linking` for cross-link work, `schema-markup` for structured data, `keyword-clustering` for keyword work, `content-brief` for new content briefs, `technical-seo` for site-speed/crawl issues, `broken-links` for 404 sweeps, `ai-visibility` for LLM-citation work.
   - `Bug` → diagnose via `engineering:debug`, propose fix, **hard stop if change is irreversible** (DB migration, theme overwrite, etc.).
   - `Tech` → execute via Mac Terminal MCP, GitHub MCP, or workspace bash. **Hard stop before git push, before file deletion, before any auth credential change.**
   - `Content` → defer to Phase 3 (Drafting). Skip in Phase 1.
   - `Design` → propose CSS/HTML change as diff, **hard stop before applying** to main.css.
3. **Autonomy rules**:
   - Low-risk auto-execute: read-only audits, draft generation, local file writes in Logistics LAB, memory updates.
   - Hard-stop required: WordPress publish, git push to main, DNS/Cloudflare changes, deletion of any file, any credential write, any task status flip to "Done" without operator confirmation.
4. **Update tracker**: when a task is successfully completed or its scope changes, propose an Edit to the HTML file (status flip, description append). Operator approves before the Edit is applied.
5. **Time budget**: max 30 minutes total in Phase 1, prioritize by HIGH > MEDIUM. Carry over unfinished tasks to next run.

### SEO Plugin Mapping (Action Tracker `type: SEO`)

| Tracker Task Pattern | searchfit-seo Skill | Notes |
|---------------------|---------------------|-------|
| "Submit sitemap" / "GSC indexing" | `technical-seo` | Run audit, propose next steps |
| "Meta description missing" | `on-page-seo` | Per-post fix, requires WP REST credential |
| "Internal linking" / "cross-link" | `internal-linking` | Map site, propose link plan |
| "Keyword research" / "topic gap" | `keyword-clustering` | Output: cluster JSON for Phase 2 |
| "Schema markup" / "JSON-LD" | `schema-markup` | Generate ready-to-paste schema |
| "Competitor analysis" | `competitor-analyzer` agent | Already customized for FreightWaves, Supply Chain Dive, McKinsey Logistics |
| "Broken links" / "404" | `broken-links` | Crawl logiai.blog, report |
| "AI visibility" / "LLM citations" | `ai-visibility` | Track ChatGPT / Claude / Perplexity mentions |

## Phase 2: Editorial Pipeline, Upstream (modes: daily, weekly, content-only)

Run logiai sub-skills in sequence, output flows into Phase 3.

1. **`logiai-strategy`** (weekly mode only, else skip): refresh active theme focus, keyword weights, escalation criteria.
2. **`logiai-monitoring`**: scan Tier 1 + Tier 2 sources, apply direct-impact filter. Output: research objects.
3. **`logiai-scoring`**: score every candidate against 5-criteria model, assign priority class.
4. **`logiai-verification`**: check source robustness, factual accuracy, date currency. Assign Confidence Score, only `Verified` or `Partial` proceed.

**Duplicate-check (CRITICAL):** before passing any story to Phase 3, run `memory/logiai_published.md` check:

- Compare topic-tag overlap and title similarity against published list
- If overlap >= 3 tags AND same pillar → flag as duplicate-risk, surface to operator
- If exact title match → block, escalate

Output of Phase 2: shortlist of 1 to 5 verified, non-duplicate story candidates ready for drafting.

## Phase 3: Drafting (modes: daily, weekly, content-only)

For each candidate from Phase 2:

1. Call `logiai-drafting` skill with the verified story object.
2. Apply LogiAI tone, no em-dashes (per global CLAUDE.md rule).
3. **Editorial visuals (REQUIRED):** Call `logiai-editorial-visuals` skill. Select 1-2 visuals per article based on story type:
   - Breaking news with a key stat → `stat.html` callout
   - Trend data over time → `line.html`
   - Before/after comparison → `bar.html`
   - Multi-step compliance or workflow → `timeline.html` or `flow.html`
   - Vendor/platform comparison → `cmp.html`
   - System architecture (agents, TMS, WMS) → `arch.html`
   - Geographic distribution (routes, hubs) → `map.html`
   - Direct quote worth amplifying → `pullquote.html`
   - Market position quadrant → `matrix.html`
   Reference library: `/Users/maxxposs/Documents/Logistics LAB/editorial-visuals/visuals/`
   Also check: `/Users/maxxposs/Documents/Projects/Logistics LAB/Branding/LogiAI Blog Design Elements/visuals/`
   Apply Apple-inspired design rules from `DESIGN.md` (black/gray palette, SF Pro-equivalent fonts, #0071e3 accent).
4. **Featured image brief (REQUIRED):** Include two image brief options (Option A editorial photographic, Option B diagrammatic) in the draft package. Reference style from DESIGN.md.
5. Save draft to `/Users/maxxposs/Documents/Logistics LAB/logiai-draft-YYYY-MM-DD-{slug}.md`.

## Phase 4: QC + Compliance (modes: daily, weekly, content-only)

For each draft from Phase 3:

1. **`logiai-quality-gate`**: run 18-point QC checklist, calculate Quality Score.
2. **`logiai-compliance`**: EU AI Act, GDPR, copyright, taxonomy check, audit trail entry.
3. Route based on outcome:
   - **Auto-approve route** (QC >= 8/10 AND compliance pass): mark draft `READY-FOR-PUBLISH`, proceed to Phase 5.
   - **Soft escalation** (QC 6 to 7 OR minor compliance flag): write report `drafts/YYYY-MM-DD-{slug}-issues.md`, surface to operator.
   - **Hard escalation** (QC < 6 OR major compliance flag): block, do not publish-prepare, surface with red flag.

## Phase 5: Approval Gate (HARD STOP, all modes)

Before any publish:

1. **Operator present (interactive session): render the Approval Board widget.** Use the visualize/show_widget tool with the template at `~/Documents/Claude/Scheduled/logiai-production-stack/approval-board-template.html`. Populate the four piles with current items:
   - Pile 1 "Ready for publish": one card per `READY-FOR-PUBLISH` or soft-escalated draft (id letter, title, QC score, pillar, word count, suggested slot, amber badge for time-sensitive items). Publish / Skip / Defer segmented buttons per card, recommended option marked.
   - Pile 2 "Open decisions": non-draft decisions (dedup conflicts, drift sync, slotting) with custom option buttons.
   - Pile 3 "Backup candidates": checkboxes, checked = draft next run.
   - Pile 4 "Operator follow-ups": checkboxes (tracker items needing operator, Yoast, images), checked = handled or schedule it.
   The board's Submit button sends one structured message: `Approval decisions YYYY-MM-DD: Drafts: A=publish (slot ...); ... Decision D: ... Draft as backups next run: ... Follow-ups handled or to schedule: ...`. Parse it and execute within approved scope. "Select all recommended" preselects every recommended option.
2. **Fallback** (widget tooling unavailable): `AskUserQuestion` batch per `memory/logiai_decision_queue_format.md` (max 4 questions, Recommended-first options).
3. **Operator absent (scheduled run):** async mail-label flow per `config.yml > approval_email` (stage 1 selection mail, label scan, 24h defaults). Unchanged.
4. **No publish or status flip proceeds without explicit operator answer.**

## Phase 6: Publishing (only if approved in Phase 5)

**Pre-flight (run once, before any publish):**
`python3 "/Users/maxxposs/Documents/Logistics LAB/fal_generate.py" check --wp`

- **WP FAIL** (HTTP 401): hard-stop the entire publish. POST nothing. Surface to operator (app password / T021).
- **fal FAIL** (HTTP 401): WP is fine, so publishing still proceeds. Skip custom image generation, use the library placeholder from the mapping below, and flag "custom image pending fal-key renewal" in the run log and to the operator. Never block a publish on a dead fal key.

For each draft operator approved:

1. Call `logiai-publishing` skill to format for WordPress (HTML blocks, internal links, Yoast meta).
1a. **Inline every editorial visual (REQUIRED, kses-safe).** Editorial-visual snippets carry a `<style>` block with `.lv-*` class selectors. WordPress can ship that as unstyled plain text when the `<style>` is dropped (root cause of the post-600 drift, 2026-06-29). Before building the post body, convert each visual to inline-styled, English-only HTML:
   `python3 "/Users/maxxposs/Documents/Logistics LAB/editorial-visual-inline.py" <snippet.html> --lang en -o <inlined.html>`
   Use the inlined output in the post content. Never POST a `<style>`-block visual via REST.
1b. **Visual-style guard (HARD RULE, run before every WP POST/PATCH that contains a visual).** Mirror brief-leak-guard:
   `python3 "/Users/maxxposs/Documents/Logistics LAB/visual-style-guard.py" --file <content.html>` on the outgoing body, and `--post <ID>` after publish to verify live. Exit 0 = CLEAN (publish allowed). Exit 1 = REJECT (a `lv-*` figure lacks inline styles or a `<style>` block is present): block the publish, hard escalation.
2. **Select or generate featured image (REQUIRED before publish):**
   - Check `logiai-images/` folder against story pillar using this mapping:
     | WP Media ID | File | Use for |
     |-------------|------|---------|
     | 402 | `01_hero.png` | General AI/logistics overview, P7 AI Vendor Eval |
     | 457 | `02_warehouse.png` | P2 Warehouse Automation, robotics |
     | 453 | `03_control_room.png` | P5 Agentic AI, AI Agents, operations |
     | 458 | `04_autonomous_truck.png` | P1 Autonomous Trucking |
     | 454 | `05_compliance.png` | P6 EU AI Act, compliance, regulation |
   - If no pre-made image fits the story angle, generate one with the fal tool (preferred over the fal MCP for unattended runs):
     `python3 "/Users/maxxposs/Documents/Logistics LAB/fal_generate.py" publish-image --prompt "<Image Brief Option A>" --filename <slug>.jpg --alt "<alt text>" --title "<title>" --attach-post <ID> --style cinematic`
     It generates (fal-ai/nano-banana-2), downloads, uploads to `wp/v2/media`, and attaches as `featured_media` in one call, printing the media id. Apply DESIGN.md style in the prompt: clean, professional, minimal, no text overlays.
   - If the Phase 6 pre-flight reported fal FAIL, skip generation and use the placeholder media id from the mapping; record "image: placeholder, swap pending" in the run log.
   - fal key lives in Keychain (`security add-generic-password -a fal -s fal-api-key -w "KEY_ID:SECRET" -U`), falling back to `~/Documents/.fal_key`. The `check`/`publish-image` commands read it automatically.
3. POST article to WordPress REST API: `/wp-json/wp/v2/posts` with `status: publish` (or `future` for scheduled), `featured_media: <media_id>`, categories, tags, and Yoast meta fields.
4. Capture returned `id`, `link`, `date` from API response.
5. Verify post is live: `GET {link}` returns 200 and contains article title.
6. Record media ID used in the run log for future deduplication.

## Phase 7: Memory Update (always)

1. For each published article in Phase 6: append row to `memory/logiai_published.md` table.
2. For each completed tracker task: update Action Tracker HTML (status flip via Edit).
3. Update `MEMORY.md` index if new memory files were created.
4. Write run summary log entry to `runs/YYYY-MM-DD_HHMM.log`.

## Phase 8: Performance Review (mode: weekly only)

1. Call `logiai-performance` skill.
2. Compare last week's KPIs (publishing cadence, quality scores, indexing progress, GSC clicks if accessible) against targets.
3. Generate learning signals: which Pillars are over/underperforming, which sources to weight up/down, which content patterns work.
4. Append signals to `memory/logiai_project.md` under "Weekly Signals" section.
5. Output: weekly KPI report saved to `/Users/maxxposs/Documents/Logistics LAB/reports/YYYY-WW-kpi-report.md`.

## Output Format

End every run with a structured summary in chat:

```
## LogiAI Production Stack Run — YYYY-MM-DD HH:MM (mode: X)

### Phase 1: Action Tracker
- Processed: N tasks (X High, Y Medium, Z Low)
- Auto-completed: list of task IDs
- Awaiting approval: list of task IDs with reason
- Carried over: list of task IDs

### Phase 2-4: Editorial Pipeline
- Stories scanned: N
- Verified candidates: N
- Drafts generated: N (paths)
- QC results: X auto-approve, Y soft-escalate, Z hard-escalate

### Phase 5: Approval Gate
- Operator decisions: see question response

### Phase 6: Published (if approved)
- N articles, list with URLs

### Phase 7: Memory
- logiai_published.md updated: N new rows
- Action Tracker updated: N status flips

### Phase 8: Performance (weekly only)
- KPI report path
- Top learning signals
```

## Constraints

- **NEVER auto-publish.** Hard-stop before any `wp/v2/posts` POST.
- **NEVER push to git main** without operator approval.
- **NEVER delete files** without explicit operator confirmation.
- **NEVER flip tracker task to Done** without operator approval (the user controls Done status).
- **NO em-dashes** in any generated content (logiai.blog, drafts, reports, memory). Use commas, colons, semicolons, or hyphens with spaces.
- **Editorial visuals must be inline-styled before any WP publish.** Run `editorial-visual-inline.py` on every snippet and `visual-style-guard.py` on the outgoing body. Never POST a visual that relies on a `<style>` block. A `lv-*` figure with no inline style is a hard publish-blocker.
- **Time-box** each phase. If Phase 1 exceeds 30 min, defer remaining tasks. If Phase 3 produces more than 3 drafts, queue extras for next run.
- **Audit trail.** Every state change (memory update, file edit, API call) gets a one-line entry in the run log.

## Failure Modes and Recovery

| Failure | Detection | Recovery |
|---------|-----------|----------|
| WP REST API 401 | Phase 0 check | Stop pipeline, alert operator (T021 must be resolved first) |
| Source RSS feed dead | Phase 2 (monitoring) | Skip source, flag in run log, continue |
| Action Tracker HTML malformed | Phase 0 parse | Skip Phase 1, run Phases 2-7 only, alert |
| Skill not found | Any phase | Log missing skill, skip that step, surface to operator |
| Duplicate content detected | Phase 2 dedup | Block draft, surface candidate to operator with similarity report |
| QC hard-fail | Phase 4 | Block draft, save issue report, do not surface as `READY-FOR-PUBLISH` |
| Network timeout | Any external call | Retry once with 5s backoff, then skip and log |

## Cron Setup

Scheduled-tasks entry:

- Name: `logiai-production-stack-daily`
- Cron: `0 7 * * *` (07:00 CET daily)
- Mode: `daily`
- Trigger: read this SKILL.md and execute

Weekly variant:

- Name: `logiai-production-stack-weekly`
- Cron: `0 9 * * 0` (Sundays 09:00 CET)
- Mode: `weekly`
