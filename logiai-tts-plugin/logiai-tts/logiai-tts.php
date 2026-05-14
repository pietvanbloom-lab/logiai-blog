<?php
/**
 * Plugin Name: LogiAI Text-to-Speech
 * Plugin URI: https://logiai.blog
 * Description: Adds an ElevenLabs-powered TTS play button to blog articles with automatic DE/EN language detection. Uses a secure Cloudflare Worker proxy — no API keys exposed in the browser.
 * Version: 3.1.0
 * Author: LogiAI
 * License: GPL v2 or later
 * Text Domain: logiai-tts
 */

if (!defined('ABSPATH')) {
    exit;
}

class LogiAI_TTS {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_filter('the_content', [$this, 'inject_player']);
    }

    // ── Admin Settings ─────────────────────────────────────────────

    public function add_settings_page() {
        add_options_page(
            'LogiAI TTS Settings',
            'LogiAI TTS',
            'manage_options',
            'logiai-tts',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('logiai_tts_settings', 'logiai_tts_proxy_url', [
            'sanitize_callback' => 'esc_url_raw'
        ]);
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>LogiAI Text-to-Speech Settings</h1>
            <p class="description" style="margin-bottom:16px">v3.0 — Secure proxy architecture. Audio is generated via a Cloudflare Worker that holds the ElevenLabs API key. No secrets are exposed to visitors.</p>
            <form method="post" action="options.php">
                <?php settings_fields('logiai_tts_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">TTS Proxy URL</th>
                        <td>
                            <input type="url" name="logiai_tts_proxy_url"
                                   value="<?php echo esc_attr(get_option('logiai_tts_proxy_url')); ?>"
                                   class="regular-text"
                                   placeholder="https://logiai-tts-proxy.YOUR-SUBDOMAIN.workers.dev" />
                            <p class="description">The URL of your Cloudflare Worker TTS proxy. Your ElevenLabs API key stays securely on the Worker — never in the browser.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    // ── Frontend Assets ────────────────────────────────────────────

    public function enqueue_assets() {
        if (!is_single()) return;

        $proxy_url = get_option('logiai_tts_proxy_url');
        if (empty($proxy_url)) return;

        wp_enqueue_style(
            'logiai-tts',
            plugin_dir_url(__FILE__) . 'css/logiai-tts.css',
            [],
            '3.1.0'
        );

        wp_enqueue_script(
            'logiai-tts',
            plugin_dir_url(__FILE__) . 'js/logiai-tts.js',
            [],
            '3.1.0',
            true
        );

        // Only the proxy URL goes to the frontend — no API key!
        wp_localize_script('logiai-tts', 'logiaiTTS', [
            'proxyUrl'  => $proxy_url,
            'workerUrl' => $proxy_url,
        ]);
    }

    // ── Inject Player into Article Content ─────────────────────────

    public function inject_player($content) {
        if (!is_single() || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        if (empty(get_option('logiai_tts_proxy_url'))) {
            return $content;
        }

        $player_html = '
        <div id="logiai-tts-player" class="logiai-tts-player">
            <button id="logiai-tts-btn" class="logiai-tts-btn" aria-label="Listen to this article">
                <svg class="logiai-tts-icon logiai-tts-icon--play" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 5.14v14.12a1 1 0 001.5.86l11-7.06a1 1 0 000-1.72l-11-7.06A1 1 0 008 5.14z" fill="currentColor"/>
                </svg>
                <svg class="logiai-tts-icon logiai-tts-icon--pause" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:none">
                    <rect x="6" y="4" width="4" height="16" rx="1" fill="currentColor"/>
                    <rect x="14" y="4" width="4" height="16" rx="1" fill="currentColor"/>
                </svg>
                <svg class="logiai-tts-icon logiai-tts-icon--loading" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:none">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-dasharray="31.4 31.4" stroke-linecap="round"/>
                </svg>
                <span class="logiai-tts-label">Listen to this article</span>
            </button>
            <div class="logiai-tts-controls" style="display:none">
                <div class="logiai-tts-progress-wrap">
                    <div class="logiai-tts-progress-bar">
                        <div class="logiai-tts-progress-fill"></div>
                    </div>
                    <span class="logiai-tts-time">0:00</span>
                </div>
                <button class="logiai-tts-speed" aria-label="Playback speed">1&times;</button>
            </div>
        </div>';

        return $player_html . $content;
    }
}

// Initialize
LogiAI_TTS::get_instance();
