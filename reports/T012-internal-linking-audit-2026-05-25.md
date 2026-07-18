# T012 Internal Linking Audit — 2026-05-25

**Method:** Pulled all 30 live posts via WP REST API, counted unique outbound `<a href="https://logiai.blog/...">` links per post (excluding self-links).

## Headline Numbers

- **30 live posts audited**
- **20 posts (67%) have ZERO internal outlinks** — primarily the March-April 2026 cohort
- **Median outlinks per post: 0**
- **Top performer:** Post 401 (AI in Logistics 2026 Complete Guide) with 23 outlinks — works as it should as a hub
- **Recent baseline:** Posts published since 2026-05-11 average 2-4 outlinks — drafting + publishing skills are now enforcing internal-link insertion correctly

## Zero-Outlink Backlog (Priority Targets)

These 20 posts should each receive 2-3 contextual internal links. Sorted by traffic potential proxy (newer/higher-pillar-fit first):

| Priority | Post ID | Slug | Suggested Links Out |
|----------|---------|------|---------------------|
| 1 | 475 | aurora-mclane-driverless-launch-texas | 338 (Kodiak geography), 47 (FedEx Network 2.0), 451 (pilot-to-production) |
| 2 | 156 | eu-ai-act-logistics-compliance | 464 (Aug 2026 deadline playbook), 451 (production AI exposure) |
| 3 | 184 | ai-service-failure-summary-logistics-quick-tip | 451 (production AI), 110 (freight invoice audit) |
| 4 | 183 | amazon-delivery-robot-physical-ai-last-mile | 338 (Kodiak), 37 (Uber/Rivian), 21 (warehouse robotics RaaS) |
| 5 | 173 | bcg-ai-logistics-providers-scaled-report-2026 | 155 (BCG 13% survey), 451 (pilot-to-production), 47 (FedEx Network 2.0) |
| 6 | 155 | bcg-ai-logistics-returns-survey | 173 (follow-up BCG), 451 (production deployment) |
| 7 | 179 | carrier-performance-summary-qbr-ai-prompt | 110 (freight invoice quick tip), 50 (supplier risk prompt), 184 (service failure) |
| 8 | 172 | gpt-5-4-logistics-automation | 451 (pilot-to-production), 139 (MCP), 460 (ChatGPT vs Claude vs Gemini) |
| 9 | 141 | ai-logistics-news-march-2026 | 127 (LogiMAT), 156 (EU AI Act), 155 (BCG survey) |
| 10 | 127 | logimat-2026-ai-integration-logistics | 21 (RaaS), 25 (multi-agent warehouse), 155 (BCG) |
| 11 | 110 | audit-freight-invoices-ai-quick-tip | 179 (QBR carrier prompt), 50 (supplier risk), 184 (service failure) |
| 12 | 77 | logistics-ai-agents-platforms-2026 | 451 (pilot-to-production), 6 (agentic AI), 184 (service failure prompt) |
| 13 | 50 | analyze-supplier-risks-ai-prompt | 110 (invoice audit), 179 (QBR), 184 (service failure) |
| 14 | 47 | fedex-network-2-0-transformation-2026 | 10 (FedEx AI workforce), 475 (Aurora pilot), 338 (Kodiak) |
| 15 | 68 | crypto-payments-ai-agents-logistics | 139 (MCP), 6 (agentic AI), 77 (logistics AI agents) |
| 16 | 37 | uber-rivian-robotaxi-partnership-logistics | 338 (Kodiak), 475 (Aurora McLane), 183 (Amazon robot) |
| 17 | 6 | agentic-ai-logistics-operations | 451 (pilot-to-production), 77 (agent platforms), 139 (MCP) |
| 18 | 10 | fedex-ai-workforce-strategy-2028 | 47 (FedEx Network 2.0), 451 (production AI), 173 (BCG providers) |
| 19 | 21 | raas-robotics-as-a-service-warehouse-automation | 25 (multi-agent warehouse), 486 once live (GXO/KION) |
| 20 | 25 | multi-agent-ai-warehouse-operations | 21 (RaaS), 6 (agentic AI), 486 once live (GXO/KION) |

## Recommended Execution

**Path A (faster, REST):** Patch each post via `PATCH /wp-json/wp/v2/posts/{id}` with body containing the original HTML plus inserted `<a>` tags. Requires the WP App Password from Keychain. ~20 PATCH calls, low risk because content additions are non-destructive. Time estimate: 30 min.

**Path B (manual, safer):** Open each post in WP Admin block editor, insert links via Yoast internal-linking suggestions, save. Operator does it in batches. Time: 2-3 hours total, but no script risk.

**Hard stop:** Either path requires operator approval per Phase 1 autonomy rules (T015-style hard-stop for any write to main).

## Phase 5 Question for Operator

"Approve T012 internal-linking backfill for the 20 zero-outlink posts via REST PATCH? Or prefer manual via WP Admin?"

