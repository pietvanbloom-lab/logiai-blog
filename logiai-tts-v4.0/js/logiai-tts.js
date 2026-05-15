(function () {
  'use strict';

  var SPEEDS = [1, 1.25, 1.5, 1.75, 2];

  // ── Format seconds → m:ss ──────────────────────────────────────
  function formatTime(s) {
    var m = Math.floor(s / 60);
    var sec = Math.floor(s % 60);
    return m + ':' + (sec < 10 ? '0' : '') + sec;
  }

  // ── Main controller ────────────────────────────────────────────
  function init() {
    var player    = document.getElementById('logiai-tts-player');
    var btn       = document.getElementById('logiai-tts-btn');
    var controls  = document.querySelector('.logiai-tts-controls');
    var iconPlay  = btn ? btn.querySelector('.logiai-tts-icon--play') : null;
    var iconPause = btn ? btn.querySelector('.logiai-tts-icon--pause') : null;
    var iconLoad  = btn ? btn.querySelector('.logiai-tts-icon--loading') : null;
    var label     = btn ? btn.querySelector('.logiai-tts-label') : null;
    var fill      = document.querySelector('.logiai-tts-progress-fill');
    var timeEl    = document.querySelector('.logiai-tts-time');
    var speedBtn  = document.querySelector('.logiai-tts-speed');

    // No player element or no button — bail
    if (!btn || !player) return;

    // v4.0: If no pre-generated audio URL exists, hide the player entirely
    var audioUrl = (typeof logiaiTTS !== 'undefined') ? logiaiTTS.audioUrl : '';
    if (!audioUrl) {
      player.style.display = 'none';
      return;
    }

    var audio = null;
    var speedIndex = 0;

    function showIcon(name) {
      if (iconPlay)  iconPlay.style.display  = name === 'play'  ? '' : 'none';
      if (iconPause) iconPause.style.display = name === 'pause' ? '' : 'none';
      if (iconLoad)  iconLoad.style.display  = name === 'loading' ? '' : 'none';
    }

    // Speed button
    if (speedBtn) {
      speedBtn.addEventListener('click', function () {
        speedIndex = (speedIndex + 1) % SPEEDS.length;
        var speed = SPEEDS[speedIndex];
        speedBtn.textContent = speed + '×';
        if (audio) audio.playbackRate = speed;
      });
    }

    // Progress bar click-to-seek
    var progressBar = document.querySelector('.logiai-tts-progress-bar');
    if (progressBar) {
      progressBar.addEventListener('click', function (e) {
        if (!audio || !audio.duration) return;
        var rect = this.getBoundingClientRect();
        var pct = (e.clientX - rect.left) / rect.width;
        audio.currentTime = pct * audio.duration;
      });
    }

    // Main play/pause button
    btn.addEventListener('click', function () {
      // Toggle play/pause if audio is already loaded
      if (audio) {
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

      // First click: create Audio element and start playback
      showIcon('loading');
      label.textContent = 'Loading…';

      audio = new Audio(audioUrl);
      audio.playbackRate = SPEEDS[speedIndex];

      audio.addEventListener('loadedmetadata', function () {
        if (timeEl) timeEl.textContent = '0:00 / ' + formatTime(audio.duration);
      });

      audio.addEventListener('timeupdate', function () {
        if (!audio.duration) return;
        var pct = (audio.currentTime / audio.duration) * 100;
        if (fill) fill.style.width = pct + '%';
        if (timeEl) timeEl.textContent = formatTime(audio.currentTime) + ' / ' + formatTime(audio.duration);
      });

      audio.addEventListener('ended', function () {
        showIcon('play');
        label.textContent = 'Play again';
        if (fill) fill.style.width = '100%';
      });

      audio.addEventListener('canplay', function () {
        showIcon('pause');
        label.textContent = 'Pause';
        if (controls) controls.style.display = '';
      }, { once: true });

      audio.addEventListener('error', function () {
        showIcon('play');
        label.textContent = 'Error – try again';
        audio = null;
        setTimeout(function () {
          label.textContent = 'Listen to this article';
        }, 4000);
      });

      audio.play().catch(function (err) {
        console.error('LogiAI TTS playback error:', err);
        showIcon('play');
        label.textContent = 'Error – try again';
        audio = null;
      });
    });
  }

  // Wait for DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
