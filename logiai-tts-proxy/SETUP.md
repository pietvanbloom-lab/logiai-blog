# LogiAI TTS Proxy — Setup Guide

## Prerequisites
- Cloudflare account (free tier is fine)
- Node.js installed
- Your ElevenLabs API key

## Step 1: Install Wrangler CLI
```bash
npm install -g wrangler
wrangler login
```

## Step 2: Create KV Namespace for Rate Limiting
```bash
cd logiai-tts-proxy
wrangler kv namespace create TTS_RATE_LIMIT
```
Copy the output ID and replace `REPLACE_WITH_ACTUAL_KV_ID` in `wrangler.toml`.

## Step 3: Set Your ElevenLabs API Key as Secret
```bash
wrangler secret put ELEVENLABS_API_KEY
# Paste your key when prompted — it's stored encrypted, never in code
```

## Step 4: Deploy
```bash
wrangler deploy
```
Note the URL it prints (e.g., `https://logiai-tts-proxy.YOUR-SUB.workers.dev`).

## Step 5: Configure WordPress Plugin
1. Upload `logiai-tts.zip` via WordPress Admin → Plugins → Add New → Upload
2. Activate the plugin
3. Go to Settings → LogiAI TTS
4. Paste the Worker URL from Step 4
5. Save

## Optional: Custom Voice Config
Edit `wrangler.toml` to change voices or model, then `wrangler deploy` again:
```toml
[vars]
VOICE_EN = "onwK4e9ZLuTAKqWW03F9"  # Daniel
VOICE_DE = "pqHfZKP75CvOlQylNhV4"  # Bill
TTS_MODEL = "eleven_flash_v2_5"      # Fastest
```

## Rate Limiting
Default: 10 requests per IP per hour. Change `RATE_LIMIT` in `worker.js`.

## Costs
- Cloudflare Workers Free Tier: 100,000 requests/day
- ElevenLabs: depends on your plan (key stays on the Worker, never exposed)
