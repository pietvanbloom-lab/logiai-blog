#!/usr/bin/env python3
"""
fal_generate.py — LogiAI image tool for the production stack.

One job: turn a prompt into a WordPress featured image, unattended, with the
fal.ai key stored in the macOS Keychain (same auth model as WP publishing).

Subcommands
-----------
  check                         Preflight auth. Validates the fal key (zero-cost
                                probe). Add --wp to also validate the WP app
                                password. Exit 0 = all checked creds OK.
  publish-image                 Generate -> download -> upload to WP media ->
                                (optional) attach as a post's featured image.
                                Prints the WP media id + source_url.
  gen                           Generate + download only (no WP). Prints local path.
  wp-upload                     Upload an existing local file to WP media.

Credential sources (first hit wins)
-----------------------------------
  fal key : Keychain generic-password  service "fal-api-key"
            -> ~/Documents/.fal_key  -> env FAL_KEY
  wp pass : Keychain internet-password server "logiai.blog" account "pietvanbloom"
            -> env WP_PW

Rotate the fal key after renewing it at https://fal.ai/dashboard/keys :
  security add-generic-password -a fal -s fal-api-key -w "KEY_ID:SECRET" -U

Examples
--------
  python3 fal_generate.py check --wp
  python3 fal_generate.py publish-image \
      --prompt "Interior of a compact automated micro-fulfilment centre at blue hour ..." \
      --filename veloq-fulfilment-os-2026.jpg \
      --alt "Automated micro-fulfilment centre at blue hour" \
      --title "Veloq fulfilment OS" \
      --attach-post 622 --size landscape_16_9 --style cinematic
"""
import os, sys, json, time, subprocess, argparse, base64, urllib.request, urllib.error

FAL_MODEL_DEFAULT = "fal-ai/nano-banana-2"
WP_BASE = "https://logiai.blog/wp-json/wp/v2"
WP_USER = "pietvanbloom"
UA = "curl/8.4.0"

STYLE_SUFFIX = {
    "product":   "professional product photography, studio lighting, clean background, commercial quality",
    "thumbnail": "bold colors, high contrast, eye-catching, professional",
    "ugc":       "authentic user-generated content style, natural lighting, lifestyle photography",
    "cinematic": "cinematic photography, dramatic lighting, film grain, professional cinematography",
    "none":      "",
}

# ── credentials ──────────────────────────────────────────────────────────────
def _security(args):
    try:
        return subprocess.run(["security", *args], capture_output=True, text=True, timeout=10).stdout.strip()
    except Exception:
        return ""

def load_fal_key():
    k = _security(["find-generic-password", "-s", "fal-api-key", "-w"])
    if k:
        return k.strip()
    p = os.path.expanduser("~/Documents/.fal_key")
    if os.path.exists(p):
        with open(p) as f:
            return f.read().strip()
    return os.environ.get("FAL_KEY", "").strip()

def load_wp_pw():
    k = _security(["find-internet-password", "-s", "logiai.blog", "-a", WP_USER, "-w"])
    if k:
        return k.strip()
    return os.environ.get("WP_PW", "").strip()

def wp_headers(pw, content_type="application/json"):
    auth = "Basic " + base64.b64encode(f"{WP_USER}:{pw}".encode()).decode()
    h = {"Authorization": auth, "User-Agent": UA}
    if content_type:
        h["Content-Type"] = content_type
    return h

# ── http helper ──────────────────────────────────────────────────────────────
def http(method, url, headers=None, data=None, timeout=60):
    req = urllib.request.Request(url, data=data, headers=headers or {}, method=method)
    try:
        with urllib.request.urlopen(req, timeout=timeout) as r:
            return r.status, r.read(), dict(r.headers)
    except urllib.error.HTTPError as e:
        return e.code, e.read(), dict(e.headers or {})
    except Exception as e:
        return 0, str(e).encode(), {}

# ── preflight ────────────────────────────────────────────────────────────────
def check_fal(key):
    """Zero-cost auth probe: POST an invalid body to the queue endpoint.
    401/403 => bad key. 422/400 => key accepted (just bad args). 0 => network."""
    if not key:
        return False, "no fal key found (Keychain fal-api-key / ~/Documents/.fal_key / FAL_KEY)"
    status, body, _ = http("POST", f"https://queue.fal.run/{FAL_MODEL_DEFAULT}",
                           headers={"Authorization": f"Key {key}", "Content-Type": "application/json"},
                           data=b"{}", timeout=25)
    if status in (401, 403):
        return False, f"fal key rejected (HTTP {status}) - renew at fal.ai/dashboard/keys"
    if status in (200, 201, 400, 422):
        return True, f"fal key OK (probe HTTP {status})"
    return False, f"fal probe inconclusive (HTTP {status}): {body[:120].decode('utf-8','replace')}"

def check_wp(pw):
    if not pw:
        return False, "no WP app password (Keychain logiai.blog/pietvanbloom or WP_PW)"
    status, body, _ = http("GET", f"{WP_BASE}/users/me?context=edit&_fields=id,name",
                           headers=wp_headers(pw), timeout=25)
    if status == 200:
        try:
            who = json.loads(body).get("name", "?")
        except Exception:
            who = "?"
        return True, f"WP auth OK (user {who})"
    return False, f"WP auth failed (HTTP {status}) - check app password"

def cmd_check(a):
    fk = load_fal_key()
    ok_fal, msg_fal = check_fal(fk)
    print(f"[fal] {'OK ' if ok_fal else 'FAIL'} - {msg_fal}")
    ok = ok_fal
    if a.wp:
        ok_wp, msg_wp = check_wp(load_wp_pw())
        print(f"[wp ] {'OK ' if ok_wp else 'FAIL'} - {msg_wp}")
        ok = ok and ok_wp
    return 0 if ok else 1

# ── generation ───────────────────────────────────────────────────────────────
def generate_image_url(key, prompt, size, model):
    """Return a generated image URL. Prefer the proven fal_client SDK;
    fall back to the raw queue API. Raises on failure."""
    os.environ["FAL_KEY"] = key
    args = {"prompt": prompt, "image_size": size, "num_images": 1}
    try:
        import fal_client  # proven path (matches fal_content_generator.py)
        result = fal_client.run(model, arguments=args)
        imgs = (result or {}).get("images") or []
        if imgs and imgs[0].get("url"):
            return imgs[0]["url"]
        raise RuntimeError(f"fal_client returned no image: {str(result)[:200]}")
    except ImportError:
        pass  # fall through to raw API
    # Raw queue API fallback
    hdr = {"Authorization": f"Key {key}", "Content-Type": "application/json"}
    status, body, _ = http("POST", f"https://queue.fal.run/{model}", headers=hdr,
                           data=json.dumps(args).encode(), timeout=60)
    if status not in (200, 201):
        raise RuntimeError(f"fal submit failed HTTP {status}: {body[:200].decode('utf-8','replace')}")
    sub = json.loads(body)
    status_url, response_url = sub.get("status_url"), sub.get("response_url")
    for _ in range(60):  # up to ~3 min
        time.sleep(3)
        s, b, _ = http("GET", status_url, headers=hdr, timeout=25)
        st = json.loads(b).get("status") if s == 200 else None
        if st == "COMPLETED":
            break
        if st in ("FAILED", "ERROR"):
            raise RuntimeError(f"fal job failed: {b[:200].decode('utf-8','replace')}")
    s, b, _ = http("GET", response_url, headers=hdr, timeout=40)
    if s != 200:
        raise RuntimeError(f"fal result fetch HTTP {s}")
    imgs = json.loads(b).get("images") or []
    if not imgs:
        raise RuntimeError("fal result had no images")
    return imgs[0]["url"]

def download(url, path):
    status, body, _ = http("GET", url, headers={"User-Agent": UA}, timeout=90)
    if status != 200:
        raise RuntimeError(f"image download HTTP {status}")
    with open(path, "wb") as f:
        f.write(body)
    return path, len(body)

# ── WP media ─────────────────────────────────────────────────────────────────
def wp_upload_media(pw, filepath, filename, alt=None, title=None):
    with open(filepath, "rb") as f:
        data = f.read()
    ctype = "image/png" if filename.lower().endswith(".png") else "image/jpeg"
    h = wp_headers(pw, content_type=ctype)
    h["Content-Disposition"] = f'attachment; filename="{filename}"'
    status, body, _ = http("POST", f"{WP_BASE}/media", headers=h, data=data, timeout=120)
    if status not in (200, 201):
        raise RuntimeError(f"WP media upload HTTP {status}: {body[:200].decode('utf-8','replace')}")
    media = json.loads(body)
    mid = media["id"]
    if alt or title:
        patch = {}
        if alt:   patch["alt_text"] = alt
        if title: patch["title"] = title
        http("POST", f"{WP_BASE}/media/{mid}", headers=wp_headers(pw),
             data=json.dumps(patch).encode(), timeout=40)
    return mid, media.get("source_url", "")

def wp_attach_featured(pw, post_id, media_id):
    status, body, _ = http("POST", f"{WP_BASE}/posts/{post_id}", headers=wp_headers(pw),
                           data=json.dumps({"featured_media": media_id}).encode(), timeout=40)
    return status in (200, 201)

# ── commands ─────────────────────────────────────────────────────────────────
def build_prompt(prompt, style):
    suffix = STYLE_SUFFIX.get(style, "")
    return f"{prompt}. {suffix}".strip() if suffix else prompt

def cmd_gen(a):
    key = load_fal_key()
    ok, msg = check_fal(key)
    if not ok:
        print(f"PREFLIGHT FAIL [fal]: {msg}", file=sys.stderr); return 2
    out_dir = a.out or os.path.expanduser("~/Documents/Logistics LAB/logiai-images")
    os.makedirs(out_dir, exist_ok=True)
    path = os.path.join(out_dir, a.filename)
    url = generate_image_url(key, build_prompt(a.prompt, a.style), a.size, a.model)
    p, n = download(url, path)
    print(json.dumps({"local_path": p, "bytes": n, "fal_url": url}, indent=2))
    return 0

def cmd_wp_upload(a):
    pw = load_wp_pw()
    ok, msg = check_wp(pw)
    if not ok:
        print(f"PREFLIGHT FAIL [wp]: {msg}", file=sys.stderr); return 2
    mid, src = wp_upload_media(pw, a.file, a.filename, a.alt, a.title)
    if a.attach_post:
        wp_attach_featured(pw, a.attach_post, mid)
    print(json.dumps({"media_id": mid, "source_url": src, "attached_post": a.attach_post}, indent=2))
    return 0

def cmd_publish_image(a):
    fk = load_fal_key(); pw = load_wp_pw()
    ok_f, mf = check_fal(fk); ok_w, mw = check_wp(pw)
    if not ok_f:
        print(f"PREFLIGHT FAIL [fal]: {mf}", file=sys.stderr); return 2
    if not ok_w:
        print(f"PREFLIGHT FAIL [wp]: {mw}", file=sys.stderr); return 2
    out_dir = os.path.expanduser("~/Documents/Logistics LAB/logiai-images")
    os.makedirs(out_dir, exist_ok=True)
    local = os.path.join(out_dir, a.filename)
    url = generate_image_url(fk, build_prompt(a.prompt, a.style), a.size, a.model)
    download(url, local)
    mid, src = wp_upload_media(pw, local, a.filename, a.alt, a.title)
    attached = False
    if a.attach_post:
        attached = wp_attach_featured(pw, a.attach_post, mid)
    print(json.dumps({"media_id": mid, "source_url": src, "local_path": local,
                      "attached_post": a.attach_post, "attached": attached}, indent=2))
    return 0

def main():
    ap = argparse.ArgumentParser(description="LogiAI fal.ai image tool")
    sub = ap.add_subparsers(dest="cmd", required=True)

    c = sub.add_parser("check", help="preflight auth check")
    c.add_argument("--wp", action="store_true", help="also check WP app password")
    c.set_defaults(fn=cmd_check)

    g = sub.add_parser("gen", help="generate + download only")
    g.add_argument("--prompt", required=True)
    g.add_argument("--filename", required=True)
    g.add_argument("--size", default="landscape_16_9")
    g.add_argument("--style", default="cinematic", choices=list(STYLE_SUFFIX))
    g.add_argument("--model", default=FAL_MODEL_DEFAULT)
    g.add_argument("--out", default=None)
    g.set_defaults(fn=cmd_gen)

    u = sub.add_parser("wp-upload", help="upload local file to WP media")
    u.add_argument("--file", required=True)
    u.add_argument("--filename", required=True)
    u.add_argument("--alt", default=None)
    u.add_argument("--title", default=None)
    u.add_argument("--attach-post", type=int, default=None)
    u.set_defaults(fn=cmd_wp_upload)

    p = sub.add_parser("publish-image", help="generate -> download -> upload to WP -> attach")
    p.add_argument("--prompt", required=True)
    p.add_argument("--filename", required=True)
    p.add_argument("--alt", default=None)
    p.add_argument("--title", default=None)
    p.add_argument("--attach-post", type=int, default=None)
    p.add_argument("--size", default="landscape_16_9")
    p.add_argument("--style", default="cinematic", choices=list(STYLE_SUFFIX))
    p.add_argument("--model", default=FAL_MODEL_DEFAULT)
    p.set_defaults(fn=cmd_publish_image)

    a = ap.parse_args()
    sys.exit(a.fn(a))

if __name__ == "__main__":
    main()
