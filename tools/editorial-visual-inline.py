#!/usr/bin/env python3
"""
editorial-visual-inline.py  -  LogiAI production stack

Converts a LogiAI editorial-visual snippet (which carries a <style> block plus
class-only selectors) into kses-safe, inline-styled HTML that survives a
WordPress.com REST publish.

WHY THIS EXISTS
---------------
WordPress.com strips <style> elements from post_content on publish (kses
sanitization keeps <figure>/<div>/<table> but drops <style>). The library
snippets in editorial-visuals/visuals/*.html style everything through a <style>
block with .lv-* class selectors, so when posted raw via REST the figure renders
as unstyled stacked text (see post 600, 2026-06-29). Inline style="" attributes
ARE preserved by kses, so we resolve the cascade to inline styles before publish.

WHAT IT DOES
------------
1. Resolves the bilingual snippet to one language (default: en).
   Removes [data-de] elements, unwraps [data-en] wrappers. (LogiAI publishes EN.)
2. Parses the flat <style> ruleset. Ignores @import, @media, :lang(...) and the
   [data-de]/[data-en] display-toggle rules (no longer needed after step 1).
3. Inlines matching declarations onto each element as style="...".
4. Strips the <style> block.
5. Emits the <figure> only, ready to drop into REST post_content.

Pseudo-elements (e.g. td.v.best::before bullet) cannot be inlined and are
dropped; this is cosmetic only.

Dependency-free: standard library only (runs on system python3 / python3.12).

USAGE
  python3 editorial-visual-inline.py INPUT.html [--lang en|de] [-o OUT.html]
  cat snippet.html | python3 editorial-visual-inline.py - > out.html

Exit 0 on success. Exit 2 if the OUTPUT still contains a <style> tag or any
lv-* figure without inline styles (self-check; should never happen).
"""
import sys, re, argparse
from html.parser import HTMLParser

VOID = {"area","base","br","col","embed","hr","img","input","link","meta",
        "param","source","track","wbr"}

# ---------- tiny DOM ----------
class Node:
    def __init__(self, tag=None, attrs=None, parent=None):
        self.tag = tag
        self.attrs = dict(attrs or {})
        self.parent = parent
        self.children = []   # Node or ("text", str)
    def classes(self):
        return (self.attrs.get("class") or "").split()

class DOM(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.root = Node("#root")
        self.stack = [self.root]
    def handle_starttag(self, tag, attrs):
        n = Node(tag, attrs, self.stack[-1])
        self.stack[-1].children.append(n)
        if tag not in VOID:
            self.stack.append(n)
    def handle_startendtag(self, tag, attrs):
        n = Node(tag, attrs, self.stack[-1])
        self.stack[-1].children.append(n)
    def handle_endtag(self, tag):
        for i in range(len(self.stack)-1, 0, -1):
            if self.stack[i].tag == tag:
                del self.stack[i:]
                break
    def handle_data(self, data):
        self.stack[-1].children.append(("text", data))

# ---------- CSS parsing ----------
def parse_css(css):
    """Return list of (selector_str, decls_str) in source order, skipping
    at-rules, :lang and data-toggle display rules."""
    css = re.sub(r"/\*.*?\*/", "", css, flags=re.S)
    rules = []
    # drop @import ...;  (url() and the font query contain inner ';', so match the
    # whole statement up to the terminating ';' after the closing paren/quote)
    css = re.sub(r'@import\s+url\([^)]*\)[^;]*;', "", css, flags=re.I)
    css = re.sub(r'@import\s+"[^"]*"[^;]*;', "", css, flags=re.I)
    # remove @media blocks entirely (need balanced braces)
    css = strip_at_media(css)
    for m in re.finditer(r"([^{}]+)\{([^{}]*)\}", css):
        sel_group = m.group(1).strip()
        decls = m.group(2).strip()
        if not sel_group or not decls:
            continue
        for sel in sel_group.split(","):
            s = sel.strip()
            if not s:
                continue
            if ":lang(" in s or "::" in s:
                continue
            if "[data-de]" in s or "[data-en]" in s:
                continue
            # skip any at-rule remnants or font-query junk (robustness)
            if s.startswith("@") or "&" in s or "=" in s or '"' in s or "(" in s:
                continue
            rules.append((s, decls))
    return rules

def strip_at_media(css):
    out = []
    i = 0
    while i < len(css):
        j = css.find("@media", i)
        if j == -1:
            out.append(css[i:]); break
        out.append(css[i:j])
        # find opening brace then matched closing brace
        k = css.find("{", j)
        depth = 0; m = k
        while m < len(css):
            if css[m] == "{": depth += 1
            elif css[m] == "}":
                depth -= 1
                if depth == 0:
                    break
            m += 1
        i = m + 1
    return "".join(out)

# ---------- selector matching ----------
def parse_simple(tok):
    """'td.v.best' -> ('td', {'v','best'})  ;  '.lv-cmp' -> (None,{'lv-cmp'})"""
    tag = None
    classes = set()
    m = re.match(r"^([a-zA-Z0-9]+)?", tok)
    if m and m.group(1):
        tag = m.group(1)
    for c in re.findall(r"\.([A-Za-z0-9_-]+)", tok):
        classes.add(c)
    return tag, classes

def simple_matches(node, simple):
    tag, classes = simple
    if tag and node.tag != tag:
        return False
    if classes and not classes.issubset(set(node.classes())):
        return False
    return True

def selector_matches(node, selector):
    """Descendant-combinator only (whitespace). Returns True/False."""
    parts = selector.split()
    simples = [parse_simple(p) for p in parts]
    # rightmost must match node
    if not simple_matches(node, simples[-1]):
        return False
    # walk ancestors for the rest, in order (loose descendant match)
    anc = node.parent
    idx = len(simples) - 2
    while idx >= 0 and anc is not None:
        if simple_matches(anc, simples[idx]):
            idx -= 1
        anc = anc.parent
    return idx < 0

def specificity(selector):
    cls = len(re.findall(r"\.[A-Za-z0-9_-]+", selector))
    tags = len(re.findall(r"(?:^|\s)[a-zA-Z]", selector))
    return (cls, tags)

# ---------- language resolution ----------
def resolve_lang(node, lang):
    keep = "data-"+lang
    drop = "data-de" if lang == "en" else "data-en"
    new = []
    for ch in node.children:
        if isinstance(ch, tuple):
            new.append(ch); continue
        if drop in ch.attrs:
            continue  # remove the other-language element entirely
        resolve_lang(ch, lang)
        if keep in ch.attrs and ch.tag == "span":
            # unwrap: replace this span by its children
            new.extend(ch.children)
        else:
            new.append(ch)
    node.children = new

# ---------- inline application ----------
def collect_decls(css):
    decls = {}
    for d in css.split(";"):
        d = d.strip()
        if not d or ":" not in d:
            continue
        k, v = d.split(":", 1)
        decls[k.strip()] = v.strip()
    return decls

def build_var_map(rules):
    """Collect CSS custom properties (--name:value) declared anywhere in the
    ruleset. The lv-* snippets declare them once on the root .lv-* selector."""
    vmap = {}
    for _, css in rules:
        for k, v in collect_decls(css).items():
            if k.startswith("--"):
                vmap[k] = v.strip()
    return vmap

def resolve_vars(value, vmap):
    """Replace var(--name[, fallback]) with its literal value. WordPress kses can
    drop var()/custom properties, so we bake literals (matches proven post-578)."""
    def rep(m):
        inner = m.group(1)
        if "," in inner:
            name, fb = inner.split(",", 1)
            name, fb = name.strip(), fb.strip()
        else:
            name, fb = inner.strip(), ""
        return vmap.get(name, fb)
    prev = None
    while prev != value:
        prev = value
        value = re.sub(r"var\(\s*(--[A-Za-z0-9_-]+\s*(?:,[^()]*)?)\)", rep, value)
    return value

def apply_inline(node, rules, vmap):
    if node.tag and not node.tag.startswith("#"):
        matched = []  # (spec, order, decls)
        for order, (sel, css) in enumerate(rules):
            if selector_matches(node, sel):
                matched.append((specificity(sel), order, css))
        if matched:
            matched.sort(key=lambda x: (x[0], x[1]))
            merged = {}
            for _, _, css in matched:
                merged.update(collect_decls(css))
            # preserve any pre-existing inline style (highest priority)
            if node.attrs.get("style"):
                merged.update(collect_decls(node.attrs["style"]))
            # drop custom-property declarations, resolve var() to literals
            clean = {}
            for k, v in merged.items():
                if k.startswith("--"):
                    continue
                clean[k] = resolve_vars(v, vmap)
            if clean:
                node.attrs["style"] = ";".join(f"{k}:{v}" for k, v in clean.items())
    for ch in node.children:
        if not isinstance(ch, tuple):
            apply_inline(ch, rules, vmap)

# ---------- serialize ----------
def esc_attr(v):
    return v.replace("&","&amp;").replace('"',"&quot;")

def serialize(node):
    if isinstance(node, tuple):
        return node[1]
    if node.tag.startswith("#"):
        return "".join(serialize(c) for c in node.children)
    parts = []
    for k, v in node.attrs.items():
        if v is None:
            parts.append(f" {k}")          # valueless/boolean attribute
        else:
            parts.append(f' {k}="{esc_attr(v)}"')
    attrs = "".join(parts)
    if node.tag in VOID:
        return f"<{node.tag}{attrs}>"
    inner = "".join(serialize(c) for c in node.children)
    return f"<{node.tag}{attrs}>{inner}</{node.tag}>"

def find_figure(node):
    if not isinstance(node, tuple) and node.tag == "figure":
        return node
    if isinstance(node, tuple):
        return None
    for c in node.children:
        r = find_figure(c)
        if r:
            return r
    return None

def inline_snippet(html, lang="en"):
    style = "".join(re.findall(r"<style[^>]*>(.*?)</style>", html, flags=re.S))
    rules = parse_css(style)
    body = re.sub(r"<style[^>]*>.*?</style>", "", html, flags=re.S)
    dom = DOM(); dom.feed(body)
    resolve_lang(dom.root, lang)
    vmap = build_var_map(rules)
    apply_inline(dom.root, rules, vmap)
    fig = find_figure(dom.root)
    out = serialize(fig) if fig else serialize(dom.root)
    out = re.sub(r"[ \t]*\n[ \t]*", "", out)  # collapse whitespace between tags
    return out.strip()

def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("input", help="snippet .html file or - for stdin")
    ap.add_argument("--lang", default="en", choices=["en","de"])
    ap.add_argument("-o","--output", default=None)
    a = ap.parse_args()
    html = sys.stdin.read() if a.input == "-" else open(a.input, encoding="utf-8").read()
    out = inline_snippet(html, a.lang)
    # self-check
    if "<style" in out.lower():
        sys.stderr.write("FAIL: <style> survived inlining\n"); sys.exit(2)
    if "lv-" in out and "style=" not in out:
        sys.stderr.write("FAIL: lv-* figure has no inline styles\n"); sys.exit(2)
    if a.output:
        open(a.output,"w",encoding="utf-8").write(out)
        sys.stderr.write(f"wrote {a.output} ({len(out)} chars)\n")
    else:
        sys.stdout.write(out)

if __name__ == "__main__":
    main()
