<?php
/**
 * Plugin Name: LogiAI Text-to-Speech
 * Plugin URI: https://logiai.blog
 * Description: Adds an ElevenLabs-powered TTS play button to blog articles. Audio is pre-generated once per article and stored in the Media Library. Uses a secure Cloudflare Worker proxy.
 * Version: 4.0.0
 * Author: LogiAI
 * License: GPL v2 or later
 * Text Domain: logiai-tts
 */

if (!defined('ABSPATH')) {
    exit;
}

class LogiAI_TTS {

    private static $instance = null;
    const META_KEY = '_logiai_tts_audio_url';
    const META_KEY_ID = '_logiai_tts_audio_id';

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Admin
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('add_meta_boxes', [$this, 'add_tts_meta_box']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        // REST API for saving audio
        add_action('rest_api_init', [$this, 'register_rest_routes']);

        // Frontend
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
            <p class="description" style="margin-bottom:16px">v4.0 — Pre-generated audio. Audio is created once per article via a Cloudflare Worker and stored in the Media Library. No API calls on every visitor.</p>
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
                            <p class="description">The URL of your Cloudflare Worker TTS proxy.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    // ── Meta Box in Post Editor ────────────────────────────────────

    public function add_tts_meta_box() {
        add_meta_box(
            'logiai-tts-generate',
            'Text-to-Speech Audio',
            [$this, 'render_meta_box'],
            'post',
            'side',
            'default'
        );
    }

    public function render_meta_box($post) {
        $audio_url = get_post_meta($post->ID, self::META_KEY, true);
        $proxy_url = get_option('logiai_tts_proxy_url');
        ?>
        <div id="logiai-tts-admin-box">
            <?php if (empty($proxy_url)) : ?>
                <p style="color:#d63638">Configure the TTS Proxy URL in <a href="<?php echo admin_url('options-general.php?page=logiai-tts'); ?>">Settings &rarr; LogiAI TTS</a> first.</p>
            <?php else : ?>
                <?php if ($audio_url) : ?>
                    <p style="color:#00a32a; font-weight:600">&#10003; Audio generated</p>
                    <audio controls preload="metadata" style="width:100%; margin-bottom:8px">
                        <source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
                    </audio>
                <?php else : ?>
                    <p style="color:#996800">No audio yet for this article.</p>
                <?php endif; ?>

                <div id="logiai-tts-progress" style="display:none; margin-bottom:8px">
                    <div style="background:#ddd; border-radius:3px; height:6px; overflow:hidden">
                        <div id="logiai-tts-progress-bar" style="background:#1a56db; height:100%; width:0; transition:width 0.3s"></div>
                    </div>
                    <small id="logiai-tts-progress-text" style="color:#666">Preparing...</small>
                </div>

                <button type="button" id="logiai-tts-generate-btn" class="button button-primary" style="width:100%">
                    <?php echo $audio_url ? 'Regenerate Audio' : 'Generate Audio'; ?>
                </button>

                <?php if ($audio_url) : ?>
                    <button type="button" id="logiai-tts-delete-btn" class="button" style="width:100%; margin-top:4px; color:#d63638">
                        Remove Audio
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    // ── Admin Assets ───────────────────────────────────────────────

    public function enqueue_admin_assets($hook) {
        if (!in_array($hook, ['post.php', 'post-new.php'])) return;

        $proxy_url = get_option('logiai_tts_proxy_url');
        if (empty($proxy_url)) return;

        wp_enqueue_script(
            'logiai-tts-admin',
            plugin_dir_url(__FILE__) . 'js/logiai-tts-admin.js',
            ['wp-api-fetch'],
            '4.0.0',
            true
        );

        global $post;
        wp_localize_script('logiai-tts-admin', 'logiaiTTSAdmin', [
            'proxyUrl'  => $proxy_url,
            'postId'    => $post ? $post->ID : 0,
            'restUrl'   => rest_url('logiai-tts/v1/'),
            'nonce'     => wp_create_nonce('wp_rest'),
            'audioUrl'  => $post ? get_post_meta($post->ID, self::META_KEY, true) : '',
        ]);
    }

    // ── REST API ───────────────────────────────────────────────────

    public function register_rest_routes() {
        register_rest_route('logiai-tts/v1', '/save-audio', [
            'methods'  => 'POST',
            'callback' => [$this, 'rest_save_audio'],
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ]);

        register_rest_route('logiai-tts/v1', '/delete-audio', [
            'methods'  => 'POST',
            'callback' => [$this, 'rest_delete_audio'],
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ]);
    }

    public function rest_save_audio($request) {
        $post_id = intval($request->get_param('post_id'));
        if (!$post_id || !get_post($post_id)) {
            return new WP_Error('invalid_post', 'Invalid post ID', ['status' => 400]);
        }

        $files = $request->get_file_params();
        if (empty($files['audio'])) {
            return new WP_Error('no_audio', 'No audio file provided', ['status' => 400]);
        }

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $file = $files['audio'];
        $upload = wp_handle_upload($file, ['test_form' => false]);

        if (isset($upload['error'])) {
            return new WP_Error('upload_failed', $upload['error'], ['status' => 500]);
        }

        $post_title = get_the_title($post_id);
        $attachment = [
            'post_mime_type' => 'audio/mpeg',
            'post_title'     => 'TTS: ' . $post_title,
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];

        $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

        if (is_wp_error($attach_id)) {
            return new WP_Error('attach_failed', 'Failed to create attachment', ['status' => 500]);
        }

        $old_id = get_post_meta($post_id, self::META_KEY_ID, true);
        if ($old_id) {
            wp_delete_attachment($old_id, true);
        }

        update_post_meta($post_id, self::META_KEY, $upload['url']);
        update_post_meta($post_id, self::META_KEY_ID, $attach_id);

        return [
            'success'  => true,
            'audioUrl' => $upload['url'],
            'attachId' => $attach_id
        ];
    }

    public function rest_delete_audio($request) {
        $post_id = intval($request->get_param('post_id'));
        if (!$post_id) {
            return new WP_Error('invalid_post', 'Invalid post ID', ['status' => 400]);
        }

        $attach_id = get_post_meta($post_id, self::META_KEY_ID, true);
        if ($attach_id) {
            wp_delete_attachment($attach_id, true);
        }

        delete_post_meta($post_id, self::META_KEY);
        delete_post_meta($post_id, self::META_KEY_ID);

        return ['success' => true];
    }

    // ── Frontend Assets ────────────────────────────────────────────

    public function enqueue_assets() {
        if (!is_single()) return;

        global $post;
        $audio_url = get_post_meta($post->ID, self::META_KEY, true);
        $proxy_url = get_option('logiai_tts_proxy_url');

        if (empty($audio_url) && empty($proxy_url)) return;

        wp_enqueue_style(
            'logiai-tts',
            plugin_dir_url(__FILE__) . 'css/logiai-tts.css',
            [],
            '4.0.0'
        );

        wp_enqueue_script(
            'logiai-tts',
            plugin_dir_url(__FILE__) . 'js/logiai-tts.js',
            [],
            '4.0.0',
            true
        );

        wp_localize_script('logiai-tts', 'logiaiTTS', [
            'audioUrl'  => $audio_url ?: '',
            'proxyUrl'  => $proxy_url ?: '',
        ]);
    }

    // ── Inject Player into Article Content ─────────────────────────

    public function inject_player($content) {
        if (!is_single() || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        global $post;
        $audio_url = get_post_meta($post->ID, self::META_KEY, true);
        $proxy_url = get_option('logiai_tts_proxy_url');

        if (empty($audio_url) && empty($proxy_url)) {
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
