---
name: logiai-publishing
description: >-
  Format approved LogiAI content for channel-specific publication and manage
  dispatch logistics. Use when the user says "publish post", "prepare newsletter
  dispatch", "finalize issue", "publish to WordPress", "schedule newsletter",
  "LogiAI publish", "push to blog", or "assemble newsletter". Handles WordPress
  blog formatting, internal linking, metadata, and newsletter issue assembly
  with pre-publish and pre-send checklists.
metadata:
  author: LogiAI
  version: '1.0'
---

# LogiAI Publishing — Agent 9: Channel Publication & Dispatch

## When to Use This Skill

Use this skill when:

- An auto-approved draft (or Operator-approved escalated draft) is ready to publish
- The weekly newsletter needs to be assembled from the week's approved content
- A blog post needs to be formatted and pushed to WordPress
- A breaking story needs immediate publication

This is a **Core** skill — required for Phase 1 minimum viable operations.

## Styleguide Compliance (final sweep)

**Master file:** `<repo>/CONTENT-STYLEGUIDE.md`

Publishing is the last line of defense. Before any push to WordPress or newsletter send, run the styleguide sweep on the approved package. If anything reverts a Hard Rule (e.g. WYSIWYG editor auto-replaces `--` with `—`), block the publish and return the draft.

### Pre-Publish Hard-Rule Sweep

1. **Em-dash scan:** Strg+F nach `—` über Title, Lead, Body, Meta-Description, Alt-Texts, Image-Captions. Treffer = Publish blockieren.
2. **En-dash scan:** `–` nur in Zahlenbereichen (`2024–2026`). Sonst durch Bindestrich ersetzen.
3. **Emoji scan:** Im Artikel-Body und in Meta-Description = entfernen. Erlaubt nur in Social-Teaser-Text und Newsletter-Eyebrow.
4. **Floskel scan:** Body + Meta-Description gegen die Floskel-Liste prüfen (Master-File Abschnitt 0 + 6). Treffer = zurück an drafting.
5. **Brief-Header-Leak scan:** Pflicht-Check vor jedem WordPress-Publish: `python3 "<repo>/brief-leak-guard.py" --post N` (bei Post-ID) oder mit Datei-Pfad für Drafts. Exit 0 = CLEAN (Publish erlaubt). Exit 1 = REJECT (Brief-Marker gefunden, Publish blockieren, hard escalation). Ursprung: Post 493 Toyota, 2026-05-29.
6. **Editorial-Visual inlining (Pflicht):** Editorial-Visual-Snippets stylen ueber einen `<style>`-Block mit `.lv-*` Klassen. WordPress kann den `<style>`-Block droppen, dann rendert das Visual als nackter Text (Ursache der Post-600-Drift, 2026-06-29). Vor dem Einbau jedes Visual inlinen: `python3 "<repo>/tools/editorial-visual-inline.py" <snippet.html> --lang en -o <inlined.html>`. Nur die inlinete Version in den Post-Body. Niemals ein `<style>`-Block-Visual per REST posten.
7. **Visual-Style-Guard scan:** Pflicht-Check vor jedem WordPress-Publish mit Visual: `python3 "<repo>/tools/visual-style-guard.py" --file <content.html>` (Body) bzw. `--post N` (Verifikation live). Exit 0 = CLEAN. Exit 1 = REJECT (ein `lv-*` Figure ohne Inline-Style oder ein `<style>`-Block vorhanden, Publish blockieren, hard escalation). Ursprung: Post 600 Amazon Proteus, 2026-06-29.

### SEO-Metadata-Regeln (styleguide-konform)

- **SEO-Title:** max 60 Zeichen, enthält Eigennamen oder zentralen Begriff. Nicht identisch mit H1.
- **Meta-Description:** max 155 Zeichen, eigenständig formuliert, KEINE Wiederholung des Lead-Absatzes. Eine These plus ein Faktum.
- **Slug:** Eigennamen-zentriert (`/project44-lunapath-visibility-execution/`), nicht generisch (`/ki-revolutioniert-die-logistik/`).
- **Alt-Texte:** beschreibend, keine Keyword-Stuffing-Listen. "Kodiak-Truck auf I-70 bei Tageslicht", nicht "AI truck autonomous logistics future".
- **Tags:** 3-5, mit Eigennamen wo möglich. Keine Mega-Tags wie "KI" oder "Logistik" allein.

### WordPress-Formatting-Regeln

- **H2s erzählen die Story.** Wer nur H1 + alle H2s liest, kennt die These. Keine generischen H2s wie "Hintergrund", "Was bedeutet das?", "Fazit".
- **Lead-Absatz kursiv,** max 2 Sätze, mit Anlass + These.
- **Bold + Italic sparsam.** Maximal eine fett markierte Stelle pro Absatz. Bold-Leads nur in Erklär-Modus-Listen ("Erstens", "Mitnehmen").
- **Listen** nur wenn enumerativ, max 5 Bullets, jedes Bullet ein vollständiger Satz.
- **Inline-Links** auf Originalquellen (nicht Aggregatoren). Keine "klicken Sie hier"-Anchor-Texts.

### Newsletter-Eyebrow / Social-Teaser

Hier sind Emojis und LinkedIn-naher Ton erlaubt, aber: keine em-dashes, keine generischen KI-Floskeln, keine doppelte Verneinung als Stilmittel.

## Instructions

### Step 1 — Load Approved Content

Read from workspace:
- Auto-approved drafts: `logiai-draft-*.md` files where the corresponding QC report shows "Auto-Approve"
- Operator-approved drafts: drafts where the Operator has given explicit approval after escalation
- QC reports: `logiai-qc-*.md` for reference

**Never process a draft that has not been approved.** Check the QC report approval route before proceeding.

### Step 2 — Channel A: LogiAI Blog (WordPress)

For every approved blog post:

#### 2a. Format Adaptation

1. Convert the draft to WordPress-compatible format:
   - H2 for main subheads, H3 for sub-sections
   - Short paragraphs (max 3–4 sentences each)
   - Bold key terms on first mention
   - Bullet lists for 3+ related items

2. Apply SEO metadata from the draft package:
   - SEO Title as the WordPress title
   - Meta Description in the Yoast/Jetpack SEO field
   - Slug from the metadata block
   - Primary Category from the LogiAI category system
   - Tags as WordPress tags

   **Yoast SEO meta (reliable method, important).** Yoast SEO title and meta description do NOT persist when sent as `meta._yoast_wpseo_*` through the WordPress REST `/posts` endpoint on this site (the request returns 200 but the values are silently dropped, because the meta is not REST-registered). Do not rely on REST for Yoast, and do not fight the fragile Yoast "Search appearance" modal. Instead drive the block editor's Yoast data store from the Claude in Chrome JS console on the post edit screen (`/wp-admin/post.php?post=ID&action=edit`, wait for the editor to load):

   ```js
   var d = wp.data.dispatch('yoast-seo/editor');
   d.updateData({ title: 'Custom SEO Title %%sep%% %%sitename%%', description: 'Meta description, aim for ~150-155 chars, no em-dash' });
   d.setFocusKeyword('primary keyphrase');
   wp.data.dispatch('core/editor').savePost();
   ```

   Notes: the title field accepts the `%%sep%%` and `%%sitename%%` replacement variables (renders " - LogiAI"); `savePost()` preserves the post status and scheduled date; never touch the slug. Verify persistence with an authenticated GET of `yoast_head_json` (check the `title` and `description` fields). Proven 2026-06-01 on posts 566 and 567.

3. Internal linking:
   - Search for 2–3 relevant previously published LogiAI posts
   - Add natural inline links where they add context
   - Link format: anchor text referencing the topic, not "click here"

4. Featured image:
   - Use the image brief from the draft package
   - Generate or select the featured image based on the style guide:
     - REALISTIC PHOTOGRAPHIC for operational/strategy/company topics
     - FUTURISTIC ILLUSTRATION for tools/prompts/emerging tech
     - HYBRID for Visibility & Customer Experience
   - Format: 16:9, optimized for web

#### 2b. Pre-Publish Checklist (Blog)

Before publishing, verify all items:

| # | Check | Status |
|---|---|---|
| 1 | Title matches approved headline variant | [ ] |
| 2 | SEO metadata populated (title, description, slug) | [ ] |
| 3 | Category assigned correctly | [ ] |
| 4 | Tags applied (3–5) | [ ] |
| 5 | Featured image uploaded and set | [ ] |
| 6 | Internal links added (2–3 minimum) | [ ] |
| 7 | All source URLs functional | [ ] |
| 8 | Mobile preview checked — readable on small screen | [ ] |
| 9 | Author profile set correctly | [ ] |
| 10 | Publication date/time correct (per schedule) | [ ] |

All 10 items must pass. Any failure: fix before publishing.

#### 2c. Publish and Log

- Publish the post to WordPress (or save as draft if the Operator prefers manual publish trigger)
- Record publication metadata:
  - Post ID, URL, publish timestamp
  - Category, tags, word count
  - Verification status and QC score from the pipeline
  - Image style used

### Step 3 — Channel B: LogiAI Weekly Newsletter

For the weekly newsletter (assembled from the week's approved content):

#### 3a. Issue Assembly

Assemble the newsletter in this order:

1. **Subject Line** — Max 50 characters, states the lead story's core insight
2. **Lead Story** (150–250 words) — The week's highest-scored story in newsletter lead format
3. **Supporting Items** (2–4 items, 50–100 words each) — Next-best stories in supporting item format
4. **Spotlight** (40–70 words) — Tool/startup/emerging item if available
5. **Closing** (2–3 sentences) — Forward look or call-to-action (visit blog, reply with feedback)
6. **Footer** — Unsubscribe link, LogiAI branding, issue number, publication date

#### 3b. Pre-Send Checklist (Newsletter)

| # | Check | Status |
|---|---|---|
| 1 | Subject line max 50 characters | [ ] |
| 2 | Mobile preview renders correctly | [ ] |
| 3 | Unsubscribe link present and functional | [ ] |
| 4 | Brand formatting consistent (colors, fonts, logo) | [ ] |
| 5 | Issue number incremented from last issue | [ ] |
| 6 | All story links point to live blog posts | [ ] |
| 7 | No broken images | [ ] |
| 8 | Lead story matches the week's top-scored candidate | [ ] |
| 9 | Scheduled send time: Sunday 09:00 CET | [ ] |
| 10 | Test email sent and reviewed | [ ] |

All 10 items must pass. Any failure: fix before scheduling.

#### 3c. Schedule and Log

- Schedule newsletter for Sunday 09:00 CET via Mailchimp (or the configured email platform)
- Record issue metadata:
  - Issue number, subject line, send time
  - Story count (lead + supporting + spotlight)
  - Subscriber count at send time
  - All story IDs included

### Step 4 — Create Publication Log Entry

For every publication (blog post or newsletter), create an immutable log entry:

```
=== LOGIAI PUBLICATION LOG ===
Type: [Blog Post / Newsletter Issue]
Published: [YYYY-MM-DD HH:MM CET]
Channel: [WordPress / Mailchimp]

CONTENT:
  Story ID(s): [List]
  Headline(s): [List]
  Format(s): [Blog Post / Newsletter Lead / Supporting / Spotlight]
  Word Count: [Total]

PIPELINE TRAIL:
  Monitoring: [Date of scan that sourced this story]
  Scoring: [Final Score / Priority Class]
  Verification: [Status / Confidence Score]
  QC: [Quality Score / Approval Route]
  Approval: [Auto / Operator — name if escalated]

PUBLISH DETAILS:
  Post URL: [If blog]
  Issue Number: [If newsletter]
  Subscriber Count: [If newsletter]
  Featured Image: [Style used]
  Categories: [Applied]
  Tags: [Applied]

CORRECTIONS: [None / Description of any post-publish edits]
```

### Step 5 — Save and Archive

- Save the publication log to workspace as `logiai-published-[YYYY-MM-DD]-[type].md`
- Update the Google Sheets content tracker if connected
- The publication log feeds into **logiAI-performance** for KPI measurement

## Corrections Protocol

If a published story requires correction after publication:

1. Make the correction in the CMS
2. Add a visible correction note at the top of the article: "Correction [date]: [what was changed and why]"
3. Update the publication log entry with the correction details
4. **No silent edits** — every change must be logged and visible to readers
5. If the correction is material (changes the core message), notify newsletter subscribers in the next issue

## Critical Rules

- **Never publish unapproved content** — always verify the QC report approval route first
- **No silent edits** — every post-publication change is logged
- **Issue number must increment** — never reuse or skip issue numbers
- **Sunday 09:00 CET is the default newsletter send time** — only the Operator can change it
- **Blog is the primary channel** — the newsletter repackages blog content, not the other way around

## Notes

- The publishing skill is the final step in the automated pipeline
- WordPress integration uses the WordPress REST API or connected WordPress tools
- For Mailchimp integration, use the connected Mailchimp tools or prepare content for manual upload
- The blog-first model means every newsletter story should link back to a published blog post
- Publication logs accumulate as the audit trail backbone for performance analysis and compliance review
