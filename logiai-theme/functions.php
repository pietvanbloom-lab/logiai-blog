<?php
/**
 * LogiAI Theme Functions
 *
 * @package LogiAI
 */

if ( ! defined( 'LOGIAI_VERSION' ) ) {
	define( 'LOGIAI_VERSION', '1.0.0' );
}

// ============================================================
// SETUP
// ============================================================
function logiai_setup() {
	load_theme_textdomain( 'logiai', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );

	// Custom image sizes
	add_image_size( 'logiai-hero',     1240, 560, true );
	add_image_size( 'logiai-featured', 740,  420, true );
	add_image_size( 'logiai-grid',     400,  300, true );
	add_image_size( 'logiai-thumb',    120,  120, true );

	// Navigation menus
	register_nav_menus( array(
		'utility'  => esc_html__( 'Utility Bar', 'logiai' ),
		'primary'  => esc_html__( 'Primary Navigation', 'logiai' ),
		'footer'   => esc_html__( 'Footer Links', 'logiai' ),
	) );
}
add_action( 'after_setup_theme', 'logiai_setup' );

// ============================================================
// CONTENT WIDTH
// ============================================================
function logiai_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'logiai_content_width', 1240 );
}
add_action( 'after_setup_theme', 'logiai_content_width', 0 );

// ============================================================
// ENQUEUE STYLES & SCRIPTS
// ============================================================
function logiai_scripts() {
	// Google Fonts
	wp_enqueue_style(
		'logiai-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lora:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;600;700&family=Source+Code+Pro:wght@400;700&display=swap',
		array(),
		null
	);

	// Main stylesheet
	wp_enqueue_style(
		'logiai-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'logiai-fonts' ),
		LOGIAI_VERSION
	);

	// WordPress theme style (empty — all styles in main.css)
	wp_enqueue_style( 'logiai-style', get_stylesheet_uri(), array( 'logiai-main' ), LOGIAI_VERSION );

	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'logiai_scripts' );

// ============================================================
// WIDGETS
// ============================================================
function logiai_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'logiai' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'logiai' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title eyebrow">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Col 1', 'logiai' ),
		'id'            => 'footer-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="footer__col-label">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'logiai_widgets_init' );

// ============================================================
// TEMPLATE HELPERS
// ============================================================

/**
 * Returns reading-time estimate for a post.
 */
function logiai_reading_time( $post_id = null ) {
	$content   = get_post_field( 'post_content', $post_id );
	$words     = str_word_count( strip_tags( $content ) );
	$minutes   = (int) ceil( $words / 200 );
	return $minutes < 1 ? 1 : $minutes;
}

/**
 * Outputs the mono-style eyebrow (category).
 */
function logiai_eyebrow( $post_id = null ) {
	$categories = get_the_category( $post_id );
	if ( empty( $categories ) ) {
		return;
	}
	$cat  = $categories[0];
	$name = esc_html( $cat->name );
	$url  = esc_url( get_category_link( $cat->term_id ) );
	echo '<div class="story-tile__eyebrow"><a href="' . $url . '" class="eyebrow">' . $name . '</a></div>';
}

/**
 * Outputs "X Days Ago / X Hours Ago" style timestamp.
 */
function logiai_relative_time( $post_id = null ) {
	$date  = get_the_date( 'U', $post_id );
	$now   = current_time( 'timestamp' );
	$diff  = $now - $date;

	if ( $diff < 3600 ) {
		$label = sprintf( esc_html__( '%d Minutes Ago', 'logiai' ), round( $diff / 60 ) );
	} elseif ( $diff < 86400 ) {
		$label = sprintf( esc_html__( '%d Hours Ago', 'logiai' ), round( $diff / 3600 ) );
	} elseif ( $diff < 604800 ) {
		$label = sprintf( esc_html__( '%d Days Ago', 'logiai' ), round( $diff / 86400 ) );
	} else {
		$label = get_the_date( 'F j, Y', $post_id );
	}
	return esc_html( $label );
}

// ============================================================
// EXCERPT
// ============================================================
function logiai_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'logiai_excerpt_length' );

function logiai_excerpt_more( $more ) {
	return '';
}
add_filter( 'excerpt_more', 'logiai_excerpt_more' );

// ============================================================
// BODY CLASSES
// ============================================================
function logiai_body_classes( $classes ) {
	if ( is_singular() && ! is_home() ) {
		$classes[] = 'logiai-single';
	}
	return $classes;
}
add_filter( 'body_class', 'logiai_body_classes' );

// ============================================================
// YOAST SEO — move to head
// ============================================================
function logiai_move_yoast_to_top() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		remove_action( 'wpseo_head', 'wpseo_og_output', 30 );
	}
}
