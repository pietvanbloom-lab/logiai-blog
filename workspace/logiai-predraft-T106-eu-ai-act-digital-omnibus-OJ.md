# PRE-DRAFT (T106, parked; fires when the Digital Omnibus hits the Official Journal)

Status: PRE-DRAFT, placeholder-gated. Everything is verified as of 2026-07-23 EXCEPT the four bracketed values, which only exist once the OJ publishes. On trigger day: fill the four placeholders, re-verify against the live EUR-Lex OJ page, run QC, publish.
Pillar: P6 Rules, Risk & Strategy (EU AI Act). Format: explainer + stance. Language: EN. Slug: eu-ai-act-digital-omnibus-official-journal-logistics.
Placeholders to fill on OJ day:
  [OJ-DATE]        = date the act appears in the Official Journal
  [OJ-REF]         = OJ L-series reference (e.g. "OJ L, 2026/xxxx")
  [IN-FORCE-DATE]  = OJ-DATE + 3 days
  [MONTHS-REMAINING] = months from IN-FORCE-DATE to 2 Dec 2027 (Annex III deferral)
Verified timeline (multi-source: Consilium, Freshfields, Gibson Dunn, White & Case, DLA Piper): Parliament adopted 16 Jun 2026; Council final green light 29 Jun 2026; final act signed 8 Jul 2026; awaiting OJ publication; enters into force 3 days after OJ.
Dedup note: EU AI Act pillar is saturated (posts 592/597 shipped the HRAI-guidelines angle). THIS piece must lead on the OJ-publication event + the deferral math, not re-explain the Act. Confirm no same-week EU AI Act post before publishing.

===================== ARTICLE BODY (clean; fill 4 placeholders on OJ day) =====================

# The EU Just Bought Its High-Risk AI Rules Two More Years

*The Digital Omnibus was published in the Official Journal on [OJ-DATE] and enters into force on [IN-FORCE-DATE]. For logistics, the deadline everyone circled, 2 August 2026, is now gone. What replaces it matters less than most vendors will tell you.*

For a year, every AI governance slide aimed at logistics carried the same date: 2 August 2026, when the high-risk obligations of the EU AI Act were meant to bite. That date is now dead. With the Digital Omnibus in the Official Journal as of [OJ-DATE] ([OJ-REF]), the stand-alone high-risk rules move to 2 December 2027, and the obligations tied to AI embedded in regulated products move to 2 August 2028.

The obvious read is relief. The useful read is that the deferral changes far less for core logistics AI than the calendar drama suggests, and the parts it does change are the parts nobody was rushing to comply with anyway.

## What actually moved

The Digital Omnibus is a simplification package, not a repeal. Three changes matter here.

- **The high-risk clock resets.** Annex III stand-alone high-risk systems now face 2 December 2027, roughly [MONTHS-REMAINING] months out. Annex I product-embedded high-risk systems get until 2 August 2028.
- **A new prohibition arrives immediately.** The Omnibus adds an Article 5 ban on AI-generated non-consensual intimate imagery. It is not a logistics concern, but it is the one part that does not wait.
- **The AI Office gets more central power**, and several reporting and registration duties are streamlined. The direction is consolidation and less paperwork, not lighter principles.

## Why most logistics AI was never the target

Here is the part the compliance-anxiety industry glosses over. The vast majority of AI in logistics is not high-risk under the Act. Route optimization, ETA prediction, demand forecasting, warehouse computer vision, freight-rate models, and TMS copilots are not listed in Annex III. They never triggered the 2 August 2026 deadline, and they do not trigger the 2 December 2027 one either.

The genuine touchpoints are narrow and specific: AI acting as a safety component of machinery or vehicles, which reaches automated warehouse equipment and autonomous trucks through Annex I and product law; and AI used to manage or evaluate warehouse and driver workforces, which can fall under the employment category of Annex III. If your AI decides who gets shifts, or how a robot arm behaves next to a person, the deadline is your problem. If it predicts a delay, it is not.

## What the deferral is really for

Two more years is not a gift to logistics operators. It is the Commission conceding that the standards, the notified bodies, and the guidance needed to make high-risk compliance real were not going to exist by August 2026. The deferral buys time to build the machinery, not to ignore it.

For the handful of logistics systems that are genuinely in scope, the smart move is to treat 2 December 2027 as a real deadline that happens to be further away, not as a reprieve. Data governance, logging, human oversight, and technical documentation take longer to retrofit than to design in, and the vendors who will win the 2027 tenders are the ones building it now.

## What to do with the extra runway

For most teams, the practical action is to stop paying for AI Act panic that does not apply to you. Map your AI systems against Annex I and Annex III once, honestly. Anything that is not on those lists is out of high-risk scope, full stop, and the new dates are irrelevant to it.

For the systems that are in scope, use the [MONTHS-REMAINING] months deliberately. Ask your safety-component and workforce-AI vendors for their conformity roadmap and the date they will be assessment-ready. If they cannot name one, the extra time is the only thing standing between you and a scramble in late 2027.

The deadline moved. The work did not. It just got a more honest calendar.

Sources: Official Journal [OJ-REF] (verify on trigger day), Council of the EU press release 29 June 2026, and law-firm analyses (Freshfields, Gibson Dunn, White & Case, DLA Piper).

===================== END ARTICLE BODY =====================

## PLANNING (not for published body)

Trigger checklist (OJ day):
1. Re-verify the OJ publication on EUR-Lex (needs a channel that reaches eur-lex.europa.eu; from cloud use WebSearch + a browser-class fetch, or the operator machine). Capture OJ-DATE and OJ-REF (L-number).
2. Compute IN-FORCE-DATE = OJ-DATE + 3 days; MONTHS-REMAINING = IN-FORCE-DATE to 2026-12-02 -> 2027-12-02 (Annex III).
3. Fill the four placeholders. Re-scan body for em-dashes (0) and any accidental brief-header leak.
4. QC (quality-gate), compliance check, dedup vs EU AI Act posts (592/597) and no same-week AI Act post.
5. Publish via WordPress.com MCP (posts.create -> review -> publish), category "Rules, Risk & Strategy" (788290360) or "Policy & Regulation" (7742354); tags EU AI Act (761967805), Annex III (788290444), AI Governance (788290426), High-Risk AI (788290425), Regulation (5566), Compliance (85113). Featured image: reuse 05_compliance media if present, else placeholder + swap.

Headline variants (fill placeholders as needed):
- V1 (recommended): The EU Just Bought Its High-Risk AI Rules Two More Years
- V2: For Logistics AI, the 2 August Deadline Is Dead. Most of It Never Applied.
- V3: The Digital Omnibus Is Law. Here Is What Actually Changed for Logistics.

SEO:
- Focus keyphrase: EU AI Act logistics high-risk deferral
- SEO title: EU AI Act Digital Omnibus: What the Deferral Means for Logistics
- Meta (fill): The Digital Omnibus deferred EU AI Act high-risk rules to Dec 2027. Why most logistics AI was never in scope, and which systems still are.
