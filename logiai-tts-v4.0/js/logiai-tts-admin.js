(function () {
  'use strict';

  var CHUNK_SIZE = 2500;

  // ── Language detection ─────────────────────────────────────────
  function detectLanguage(text) {
    var dePatterns = /\b(und|der|die|das|ist|für|mit|von|auf|ein|eine|sich|auch|nicht|werden|wurde|bei|nach|über|oder|wie|dem|den|hat|kann|sind|wird|als|aus|noch|mehr|aber|dann|wenn|nur|haben|seine|dieser|diese|dieses|können|müssen|sollen|zwischen|durch|bereits|jedoch|dabei|sowie|künstliche|intelligenz|lieferkette|logistik)\b/gi;
    var matches = text.match(dePatterns) || [];
    var ratio = matches.length / (text.split(/\s+/).length || 1);
    return ratio > 0.08 ? 'de' : 'en';
  }

  // ── Extract clean text from post content ───────────────────────
  function getPostText() {
    var content = '';

    // Gutenberg: get content from the editor store
    if (window.wp && wp.data && wp.data.select('core/editor')) {
      var post = wp.data.select('core/editor').getCurrentPost();
      if (post && post.content) {
        content = post.content;
      }
    }

    // Classic editor fallback
    if (!content) {
      var editor = document.getElementById('content');
      if (editor) content = editor.value;
    }

    // TinyMCE fallback
    if (!content && window.tinyMCE) {
      var mce = tinyMCE.get('content');
      if (mce) content = mce.getContent();
    }

    if (!content) return '';

    // Strip HTML tags and clean up
    var div = document.createElement('div');
    div.innerHTML = content;

    // Remove elements we don't want read
    div.querySelectorAll(
      'script, style, nav, .logiai-tts-player, .sharedaddy, .jp-relatedposts, ' +
      '.wp-block-image figcaption, pre, code, .wp-caption-text'
    ).forEach(function (el) { el.remove(); });

    return div.textContent.replace(/\s+/g, ' ').trim();
  }

  // ── Split text into chunks at sentence boundaries ──────────────
  function chunkText(text, maxLen) {
    if (text.length <= maxLen) return [text];

    var chunks = [];
    var remaining = text;

    while (remaining.length > 0) {
      if (remaining.length <= maxLen) {
        chunks.push(remaining);
        break;
      }

      var slice = remaining.substring(0, maxLen);
      var breakAt = -1;
      var candidates = [
        slice.lastIndexOf('. '),
        slice.lastIndexOf('! '),
        slice.lastIndexOf('? ')
      ];
      for (var i = 0; i < candidates.length; i++) {
        if (candidates[i] > breakAt) breakAt = candidates[i];
      }

      if (breakAt < maxLen * 0.3) {
        breakAt = slice.lastIndexOf(' ');
      }

      if (breakAt <= 0) {
        breakAt = maxLen;
      } else {
        breakAt += 2;
      }

      chunks.push(remaining.substring(0, breakAt).trim());
      remaining = remaining.substring(breakAt).trim();
    }

    return chunks;
  }

  // ── Fetch a single TTS chunk ───────────────────────────────────
  async function fetchChunk(url, text, lang) {
    var response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ text: text, lang: lang })
    });

    if (!response.ok) {
      var errText = await response.text();
      throw new Error('TTS proxy error ' + response.status + ': ' + errText);
    }

    return await response.blob();
  }

  // ── Upload MP3 blob to WordPress ───────────────────────────────
  async function uploadAudio(blob, postId) {
    var formData = new FormData();
    formData.append('audio', blob, 'tts-' + postId + '.mp3');
    formData.append('post_id', postId);

    var response = await fetch(logiaiTTSAdmin.restUrl + 'save-audio', {
      method: 'POST',
      headers: {
        'X-WP-Nonce': logiaiTTSAdmin.nonce
      },
      body: formData
    });

    if (!response.ok) {
      var errText = await response.text();
      throw new Error('Upload failed: ' + errText);
    }

    return await response.json();
  }

  // ── Delete audio ───────────────────────────────────────────────
  async function deleteAudio(postId) {
    var response = await fetch(logiaiTTSAdmin.restUrl + 'delete-audio', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': logiaiTTSAdmin.nonce
      },
      body: JSON.stringify({ post_id: postId })
    });

    if (!response.ok) {
      throw new Error('Delete failed');
    }

    return await response.json();
  }

  // ── Main ───────────────────────────────────────────────────────
  function init() {
    var generateBtn = document.getElementById('logiai-tts-generate-btn');
    var deleteBtn = document.getElementById('logiai-tts-delete-btn');
    var progressWrap = document.getElementById('logiai-tts-progress');
    var progressBar = document.getElementById('logiai-tts-progress-bar');
    var progressText = document.getElementById('logiai-tts-progress-text');

    if (!generateBtn) return;

    generateBtn.addEventListener('click', async function () {
      var text = getPostText();
      if (!text || text.length < 50) {
        alert('Article text is too short or could not be found. Save the post first.');
        return;
      }

      var lang = detectLanguage(text);
      var chunks = chunkText(text, CHUNK_SIZE);

      generateBtn.disabled = true;
      generateBtn.textContent = 'Generating...';
      if (progressWrap) progressWrap.style.display = '';

      try {
        var blobs = [];
        for (var i = 0; i < chunks.length; i++) {
          if (progressText) {
            progressText.textContent = 'Generating chunk ' + (i + 1) + ' of ' + chunks.length + '...';
          }
          if (progressBar) {
            progressBar.style.width = ((i + 1) / (chunks.length + 1) * 100) + '%';
          }
          blobs.push(await fetchChunk(logiaiTTSAdmin.proxyUrl, chunks[i], lang));
        }

        if (progressText) progressText.textContent = 'Merging audio...';
        var merged = blobs.length === 1 ? blobs[0] : new Blob(blobs, { type: 'audio/mpeg' });

        if (progressText) progressText.textContent = 'Uploading to Media Library...';
        if (progressBar) progressBar.style.width = '90%';

        var result = await uploadAudio(merged, logiaiTTSAdmin.postId);

        if (progressBar) progressBar.style.width = '100%';
        if (progressText) progressText.textContent = 'Done!';

        generateBtn.textContent = 'Regenerate Audio';
        generateBtn.disabled = false;

        setTimeout(function () {
          location.reload();
        }, 1000);

      } catch (err) {
        console.error('LogiAI TTS Admin Error:', err);
        generateBtn.disabled = false;
        generateBtn.textContent = 'Error — Try Again';
        if (progressText) progressText.textContent = 'Error: ' + err.message;
        if (progressBar) progressBar.style.width = '0';

        setTimeout(function () {
          generateBtn.textContent = logiaiTTSAdmin.audioUrl ? 'Regenerate Audio' : 'Generate Audio';
          if (progressWrap) progressWrap.style.display = 'none';
        }, 5000);
      }
    });

    if (deleteBtn) {
      deleteBtn.addEventListener('click', async function () {
        if (!confirm('Remove the audio file for this article?')) return;

        deleteBtn.disabled = true;
        deleteBtn.textContent = 'Removing...';

        try {
          await deleteAudio(logiaiTTSAdmin.postId);
          location.reload();
        } catch (err) {
          console.error('Delete error:', err);
          deleteBtn.disabled = false;
          deleteBtn.textContent = 'Error — Try Again';
        }
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
