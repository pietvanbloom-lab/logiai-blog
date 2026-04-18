<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ============================================================
     UTILITY BAR
============================================================ -->
<div class="util-bar">
  <div class="container">

    <?php if ( has_nav_menu( 'utility' ) ) : ?>
      <?php wp_nav_menu( array(
        'theme_location' => 'utility',
        'container'      => false,
        'menu_class'     => 'util-bar__links',
        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'depth'          => 1,
      ) ); ?>
    <?php else : ?>
      <ul class="util-bar__links">
        <li><a href="<?php echo esc_url( home_url( '/category/supply-chain/' ) ); ?>">Supply Chain</a></li>
        <li><a href="<?php echo esc_url( home_url( '/category/automation/' ) ); ?>">Automation</a></li>
        <li><a href="<?php echo esc_url( home_url( '/category/last-mile/' ) ); ?>">Last Mile</a></li>
        <li><a href="<?php echo esc_url( home_url( '/category/freight-ai/' ) ); ?>">Freight AI</a></li>
        <li><a href="<?php echo esc_url( home_url( '/category/research/' ) ); ?>">Research</a></li>
      </ul>
    <?php endif; ?>

    <a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>" class="util-bar__cta">
      <?php esc_html_e( 'Subscribe', 'logiai' ); ?>
    </a>

  </div>
</div>

<!-- ============================================================
     MAIN NAV
============================================================ -->
<nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'logiai' ); ?>">
  <div class="container">

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav__logo" rel="home">
      LOGI<span>AI</span>
    </a>

    <?php if ( has_nav_menu( 'primary' ) ) : ?>
      <?php wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'nav__links',
        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'depth'          => 1,
      ) ); ?>
    <?php else : ?>
      <ul class="nav__links">
        <li><a href="<?php echo esc_url( home_url( '/category/trends/' ) ); ?>">Trends</a></li>
        <li><a href="<?php echo esc_url( home_url( '/category/analysis/' ) ); ?>">Analysis</a></li>
        <li><a href="<?php echo esc_url( home_url( '/category/case-studies/' ) ); ?>">Case Studies</a></li>
        <li><a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>">Tools</a></li>
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
      </ul>
    <?php endif; ?>

    <button class="nav__search" aria-label="<?php esc_attr_e( 'Search', 'logiai' ); ?>" onclick="document.getElementById('logiai-search-overlay').classList.toggle('active')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
    </button>

  </div>
</nav>

<!-- Search overlay -->
<div id="logiai-search-overlay" class="search-overlay" role="dialog" aria-label="<?php esc_attr_e( 'Search', 'logiai' ); ?>">
  <div class="search-overlay__inner container">
    <?php get_search_form(); ?>
    <button class="search-overlay__close" onclick="document.getElementById('logiai-search-overlay').classList.remove('active')" aria-label="<?php esc_attr_e( 'Close search', 'logiai' ); ?>">
      &times;
    </button>
  </div>
</div>

<hr class="rule" />
