(function () {
  'use strict';

  var SPEEDS = [1, 1.25, 1.5, 1.75, 2];

  // Tiny silent WAV (44 bytes header + 1 sample) to unlock audio playback
  var SILENT_WAV = 'data:audio/wav;base64,UklGRiQAAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQAAAAA=';

  // ── Language detection ─────────────────────────────────────────
  function detectLanguage(text) {
    var dePatterns = /\b(und|der|die|das|ist|für|mit|von|auf|ein|eine|sich|auch|nicht|werden|wurde|bei|nach|über|oder|wie|dem|den|hat|kann|sind|wird|als|aus|noch|mehr|aber|dann|wenn|nur|haben|seine|dieser|diese|dieses|können|müssen|sollen|zwischen|durch|bereits|jedoch|dabei|sowie|künstliche|intelligenz|lieferkette|logistik)\b/gi;
    var matches = text.match(dePatterns) || [];
    var ratio = matches.length / (text.split(/\s+/).length || 1);
    return ratio > 0.08 ? 'de' : 'en';
  }

  // ── Extract clean article text ─────────────────────────────────
  function getArticleText() {
    var content = document.querySelector('.article-body, .entry-content, .post-content, article .content, .wp-block-post-content');
    if (!content) return '';

    var clone = content.cloneNode(true);
    clone.querySelectorAll(
      'script, style, nav, .logiai-tts-player, .sharedaddy, .jp-relatedposts, ' +
      '.wp-block-image figcaption, pre, code, .wp-caption-text'
    ).forEach(function (el) { el.remove(); });

    return clone.textContent.replace(/\s+/g, ' ').trim();
  }

  // ── Format seconds → m:ss ──────────────────────────────────────
  function formatTime(s) {
    var m = Math.floor(s / 60);
    var sec = Math.floor(s % 60);
    return m + ':' + (sec < 10 ? '0' : '') + sec;
  }

  // ── Call TTS via secure Cloudflare Worker proxy ────────────────
  async function generateAudio(text, lang) {
    var url = logiaiTTS.proxyUrl || logiaiTTS.workerUrl;
    var response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        text: text,
        lang: lang
      })
    });

    if (!response.ok) {
      var errText = await response.text();
      throw new Error('TTS proxy error ' + response.status + ': ' + errText);
    }

    var blob = await response.blob();
    return URL.createObjectURL(blob);
  }

  // ── Main controller ────────────────────────────────────────────
  function init() {
    var btn       = document.getElementById('logiai-tts-btn');
    var controls  = document.querySelector('.logiai-tts-controls');
    var iconPlay  = btn.querySelector('.logiai-tts-icon--play');
    var iconPause = btn.querySelector('.logiai-tts-icon--pause');
    var iconLoad  = btn.querySelector('.logiai-tts-icon--loading');
    var label     = btn.querySelector('.logiai-tts-label');
    var fill      = document.querySelector('.logiai-tts-progress-fill');
    var timeEl    = document.querySelector('.logiai-tts-time');
    var speedBtn  = document.querySelector('.logiai-tts-speed');

    var proxyUrl = logiaiTTS.proxyUrl || logiaiTTS.workerUrl;
    if (!btn || !proxyUrl) return;

    var audio = null;
    var speedIndex = 0;
    var isLoading = false;

    function showIcon(name) {
      iconPlay.style.display  = name === 'play'    ? '' : 'none';
      iconPause.style.display = name === 'pause'   ? '' : 'none';
      iconLoad.style.display  = name === 'loading' ? '' : 'none';
    }

    // Speed button
    speedBtn.addEventListener('click', function () {
      speedIndex = (speedIndex + 1) % SPEEDS.length;
      var speed = SPEEDS[speedIndex];
      speedBtn.textContent = speed + '×';
      if (audio) audio.playbackRate = speed;
    });

    // Progress bar click-to-seek
    document.querySelector('.logiai-tts-progress-bar').addEventListener('click', function (e) {
      if (!audio || !audio.duration) return;
      var rect = this.getBoundingClientRect();
      var pct = (e.clientX - rect.left) / rect.width;
      audio.currentTime = pct * audio.duration;
    });

    // Main play/pause button
    btn.addEventListener('click', async function () {
      // If audio already loaded, toggle play/pause
      if (audio && audio.src && audio.src !== SILENT_WAV && !isLoading) {
        if (audio.paused) {
          audio.play();
          showIcon('pause');
          label.textContent = 'Pause';
        } else {
          audio.pause();
          showIcon('play');
          label.textContent = 'Resume';
        }
        return;
      }

      if (isLoading) return;

      // First click: immediately create & play silent audio to unlock playback
      // This preserves the user gesture so we can swap src later
      isLoading = true;
      showIcon('loading');
      label.textContent = 'Generating audio…';

      // Create audio element NOW (inside user gesture) and play silent sound
      audio = new Audio(SILENT_WAV);
      audio.playbackRate = SPEEDS[speedIndex];
      try {
        await audio.play();
      } catch (e) {
        // Silent play failed — will handle below
        console.warn('LogiAI TTS: silent unlock failed', e);
      }

      var text = getArticleText();
      if (!text) {
        label.textContent = 'No article text found';
        showIcon('play');
        isLoading = false;
        audio = null;
        return;
      }

      var lang = detectLanguage(text);

      try {
        var audioUrl = await generateAudio(text, lang);

        // Wire up event listeners before setting src
        audio.addEventListener('timeupdate', function () {
          if (!audio.duration) return;
          var pct = (audio.currentTime / audio.duration) * 100;
          fill.style.width = pct + '%';
          timeEl.textContent = formatTime(audio.currentTime) + ' / ' + formatTime(audio.duration);
        });

        audio.addEventListener('ended', function () {
          showIcon('play');
          label.textContent = 'Play again';
          fill.style.width = '100%';
        });

        audio.addEventListener('loadedmetadata', function () {
          timeEl.textContent = '0:00 / ' + formatTime(audio.duration);
        });

        // Swap src to real audio — the Audio element is already "unlocked"
        audio.src = audioUrl;
        audio.playbackRate = SPEEDS[speedIndex];

        // Play the real audio — should work because the element was already playing
        await audio.play();
        isLoading = false;
        showIcon('pause');
        label.textContent = 'Pause';
        controls.style.display = '';
      } catch (err) {
        console.error('LogiAI TTS Error:', err);
        isLoading = false;
        showIcon('play');
        label.textContent = 'Error – try again';
        audio = null;
        setTimeout(function () {
          label.textContent = 'Listen to this article';
        }, 4000);
      }
    });
  }

  // Wait for DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
