/**
 * LogiAI TTS Proxy — Cloudflare Worker
 *
 * Hält den ElevenLabs API Key sicher serverseitig.
 * Akzeptiert nur Requests von logiai.blog.
 * Rate-Limiting: max 10 Requests pro IP pro Stunde.
 */

const ALLOWED_ORIGINS = [
  'https://logiai.blog',
  'https://www.logiai.blog'
];

// Rate limit: max requests per IP per hour
const RATE_LIMIT = 10;
const RATE_WINDOW = 3600; // seconds

export default {
  async fetch(request, env) {
    // ── CORS preflight ──────────────────────────────────
    if (request.method === 'OPTIONS') {
      return handleCors(request, new Response(null, { status: 204 }));
    }

    // ── Only POST allowed ───────────────────────────────
    if (request.method !== 'POST') {
      return handleCors(request, new Response('Method not allowed', { status: 405 }));
    }

    // ── Origin check ────────────────────────────────────
    const origin = request.headers.get('Origin') || '';
    if (!ALLOWED_ORIGINS.includes(origin)) {
      return new Response('Forbidden', { status: 403 });
    }

    // ── Rate limiting (using Cloudflare KV) ─────────────
    const clientIP = request.headers.get('CF-Connecting-IP') || 'unknown';
    const rateKey = `rate:${clientIP}`;

    if (env.TTS_RATE_LIMIT) {
      const current = await env.TTS_RATE_LIMIT.get(rateKey);
      const count = current ? parseInt(current, 10) : 0;

      if (count >= RATE_LIMIT) {
        return handleCors(request, new Response(
          JSON.stringify({ error: 'Rate limit exceeded. Try again later.' }),
          { status: 429, headers: { 'Content-Type': 'application/json' } }
        ));
      }

      await env.TTS_RATE_LIMIT.put(rateKey, String(count + 1), {
        expirationTtl: RATE_WINDOW
      });
    }

    // ── Parse request body ──────────────────────────────
    let body;
    try {
      body = await request.json();
    } catch (e) {
      return handleCors(request, new Response('Invalid JSON', { status: 400 }));
    }

    const { text, lang } = body;

    if (!text || typeof text !== 'string' || text.length < 10) {
      return handleCors(request, new Response('Text too short', { status: 400 }));
    }

    if (text.length > 15000) {
      return handleCors(request, new Response('Text too long (max 15000 chars)', { status: 400 }));
    }

    // ── Select voice based on language ──────────────────
    const voiceId = lang === 'de'
      ? (env.VOICE_DE || 'pqHfZKP75CvOlQylNhV4')   // Bill (German)
      : (env.VOICE_EN || 'onwK4e9ZLuTAKqWW03F9');   // Daniel (English)

    const model = env.TTS_MODEL || 'eleven_flash_v2_5';

    // ── Call ElevenLabs ─────────────────────────────────
    try {
      const elevenResponse = await fetch(
        `https://api.elevenlabs.io/v1/text-to-speech/${voiceId}/stream`,
        {
          method: 'POST',
          headers: {
            'xi-api-key': env.ELEVENLABS_API_KEY,
            'Content-Type': 'application/json',
            'Accept': 'audio/mpeg'
          },
          body: JSON.stringify({
            text: text,
            model_id: model,
            voice_settings: {
              stability: 0.5,
              similarity_boost: 0.75,
              style: 0.0,
              use_speaker_boost: true
            }
          })
        }
      );

      if (!elevenResponse.ok) {
        const errText = await elevenResponse.text();
        console.error('ElevenLabs error:', elevenResponse.status, errText);
        return handleCors(request, new Response(
          JSON.stringify({
            error: 'TTS generation failed',
            upstream_status: elevenResponse.status,
            upstream_error: errText
          }),
          { status: 502, headers: { 'Content-Type': 'application/json' } }
        ));
      }

      // Stream the audio back to the client
      const response = new Response(elevenResponse.body, {
        status: 200,
        headers: {
          'Content-Type': 'audio/mpeg',
          'Cache-Control': 'public, max-age=86400' // cache 24h
        }
      });

      return handleCors(request, response);

    } catch (err) {
      console.error('Proxy error:', err);
      return handleCors(request, new Response(
        JSON.stringify({ error: 'Internal proxy error' }),
        { status: 500, headers: { 'Content-Type': 'application/json' } }
      ));
    }
  }
};

function handleCors(request, response) {
  const origin = request.headers.get('Origin') || '';
  const headers = new Headers(response.headers);

  if (ALLOWED_ORIGINS.includes(origin)) {
    headers.set('Access-Control-Allow-Origin', origin);
  }
  headers.set('Access-Control-Allow-Methods', 'POST, OPTIONS');
  headers.set('Access-Control-Allow-Headers', 'Content-Type');
  headers.set('Access-Control-Max-Age', '86400');

  return new Response(response.body, {
    status: response.status,
    headers
  });
}
