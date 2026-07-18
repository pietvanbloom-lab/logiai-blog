#!/usr/bin/env python3
"""
visual-style-guard.py  -  LogiAI production stack pre-publish guard

Blocks a WordPress publish when an editorial visual would render as unstyled
plain text. Mirrors brief-leak-guard.py: exit 0 = CLEAN (publish allowed),
exit 1 = REJECT (block publish, hard escalation).

ROOT CAUSE THIS GUARDS AGAINST
------------------------------
Post 600 (Amazon Proteus, went live 2026-06-29) shipped its lv-stat and lv-cmp
figures with NO <style> block and NO inline styles, so they rendered as stacked
plain text. The autonomous queue-publish path dropped the styling. Separately,
<style> blocks are fragile on WordPress.com (kses can strip them depending on
path/capability). The robust standard is therefore INLINE styles on every
editorial-visual element (produced by editorial-visual-inline.py).

RULES (on the content destined for REST publish)
1. REJECT if any <figure class="lv-..."> opening tag has no inline style="..."
   attribute. (This is exactly the post-600 failure.)
2. REJECT if a <style> block is present. New content must be inline-only so it
   cannot break if WordPress strips <style>. Run editorial-visual-inline.py
   first.

USAGE
  python3 visual-style-guard.py --post 600
  python3 visual-style-guard.py --file path/to/content.html
  python3 visual-style-guard.py --file -        # stdin

Dependency-free (urllib + stdlib).
"""
import sys, re, argparse, json, urllib.request

UA = "curl/8.4.0"

def fetch_post(pid):
    import time
    u = f"https://logiai.blog/wp-json/wp/v2/posts/{pid}?_fields=content&cb={int(time.time())}"
    r = urllib.request.Request(u, headers={"User-Agent": UA, "Cache-Control": "no-cache"})
    d = json.loads(urllib.request.urlopen(r, timeout=30).read().decode())
    return d["content"]["rendered"]

def check(html):
    issues = []
    # Rule 2: no <style> blocks in publish-ready content
    if re.search(r"<style[\s>]", html, re.I):
        issues.append("Found a <style> block. Inline the visual CSS with "
                      "editorial-visual-inline.py (WordPress can strip <style>).")
    # Rule 1: every lv-* figure must carry an inline style on the opening tag
    for m in re.finditer(r"<figure\b([^>]*?)class=\"(lv-[a-z]+)\"([^>]*)>", html, re.I):
        full = m.group(0)
        cls = m.group(2)
        if "style=" not in full:
            issues.append(f'<figure class="{cls}"> has no inline style= '
                          f"(would render as unstyled plain text).")
    return issues

def main():
    ap = argparse.ArgumentParser()
    g = ap.add_mutually_exclusive_group(required=True)
    g.add_argument("--post", type=int)
    g.add_argument("--file")
    a = ap.parse_args()
    if a.post:
        html = fetch_post(a.post)
        label = f"post {a.post}"
    else:
        html = sys.stdin.read() if a.file == "-" else open(a.file, encoding="utf-8").read()
        label = a.file
    issues = check(html)
    if not issues:
        print(f"CLEAN: {label} - all editorial visuals are inline-styled, no <style> blocks.")
        sys.exit(0)
    print(f"REJECT: {label} - editorial-visual styling problems:")
    for i in issues:
        print("  - " + i)
    sys.exit(1)

if __name__ == "__main__":
    main()
