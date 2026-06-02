#!/usr/bin/env python3
"""
brief-leak-guard.py — LogiAI pre-publish guard.

Rejects any draft/content that still contains internal editorial-brief
metadata (the brief header that belongs in the planning doc, never in the
published body). Triggered by post 493 (Toyota) where the full brief block
leaked into the live article on 2026-05-29.

Usage:
    python3 brief-leak-guard.py path/to/file.md      # scan a file
    cat content.html | python3 brief-leak-guard.py   # scan stdin
    python3 brief-leak-guard.py --post 493           # scan a live WP post (reads creds from Keychain)

Exit codes:
    0  CLEAN  — no brief markers found, safe to publish
    1  REJECT — brief markers found, do NOT publish

Wire this in before any WP REST publish in the editorial pipeline.
"""

import sys
import re
import subprocess
import json

# High-confidence markers. Each is text that belongs ONLY in an internal
# brief and must never appear in published article body. Matched against the
# tag-stripped plain text, case-insensitive.
MARKERS = [
    r"stilmix-?zuordnung",
    r"target word count",
    r"featured image\s*:",
    r"image brief",
    r"analyse-?hausstil",
    r"erkl[aä]r-?modus",
    r"haltungs-?modus",
    r"schluss-?verdikt",
    r"setup-?punchline",
    r"stratechery\s*/\s*setup",
    # "Pillar:" + a pillar code (P1..P9) is the brief's first line, not body text
    r"\bpillar\s*:?.{0,40}\bp[1-9]\b",
    # "Internal links:" followed by a "Post <number>" reference = brief routing
    r"internal links\s*:.{0,60}\bpost\s*\d+",
]

COMPILED = [re.compile(p, re.IGNORECASE | re.DOTALL) for p in MARKERS]


def strip_tags(text: str) -> str:
    # Drop HTML tags and comments so <strong>Pillar:</strong> still matches "Pillar:"
    text = re.sub(r"<!--.*?-->", " ", text, flags=re.DOTALL)
    text = re.sub(r"<[^>]+>", " ", text)
    return text


def scan(content: str):
    plain = strip_tags(content)
    hits = []
    for pat in COMPILED:
        m = pat.search(plain)
        if m:
            snippet = re.sub(r"\s+", " ", m.group(0)).strip()[:80]
            hits.append((pat.pattern, snippet))
    return hits


def fetch_live_post(post_id: str) -> str:
    pw = subprocess.run(
        ["security", "find-internet-password", "-s", "logiai.blog", "-w"],
        capture_output=True, text=True,
    ).stdout.strip()
    if not pw:
        sys.exit("Could not read WP app password from Keychain (logiai.blog).")
    url = f"https://logiai.blog/wp-json/wp/v2/posts/{post_id}?context=edit&_fields=content.raw"
    out = subprocess.run(
        ["curl", "-s", "-A", "curl/8.4.0", "-u", f"pietvanbloom:{pw}", url],
        capture_output=True, text=True,
    ).stdout
    return json.loads(out)["content"]["raw"]


def main():
    args = sys.argv[1:]
    label = "stdin"
    if args and args[0] == "--post":
        content = fetch_live_post(args[1])
        label = f"WP post {args[1]}"
    elif args and not args[0].startswith("-"):
        with open(args[0], encoding="utf-8") as f:
            content = f.read()
        label = args[0]
    else:
        content = sys.stdin.read()

    hits = scan(content)
    if hits:
        print(f"REJECT — brief-header metadata found in {label}:")
        for pattern, snippet in hits:
            print(f"  - /{pattern}/  ->  \"{snippet}\"")
        print("\nRemove the brief block before publishing. Do NOT push to WordPress.")
        sys.exit(1)

    print(f"CLEAN — no brief-header metadata in {label}. Safe to publish.")
    sys.exit(0)


if __name__ == "__main__":
    main()
