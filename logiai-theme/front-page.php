<?php
/**
 * Front Page Template
 * Homepage layout: hero, story grid, featured double, most popular
 *
 * @package LogiAI
 */

get_header();

// Query: latest posts
$args_latest = array(
	'posts_per_page' => 10,
	'post_status'    => 'publish',
	'ignore_sticky_posts' => false,
);
$latest = new WP_Query( $args_latest );
$posts  = $latest->posts;

// Slot posts into positions
$hero_post     = ! empty( $posts[0] ) ? $posts[0] : null;
$grid_posts    = array_slice( $posts, 1, 3 );
$featured_post = ! empty( $posts[4] ) ? $posts[4] : null;
$sidebar_posts = array_slice( $posts, 5, 3 );

// Most popular (by comment count as proxy; swap for views plugin if available)
$args_popular = array(
	'posts_per_page' => 5,
	'orderby'        => 'comment_count',
	'order'          => 'DESC',
	'post_status'    => 'publish',
);
$popular = new WP_Query( $args_popular );
?>

<!-- ============================================================
     HERO
============================================================ -->
<?php if ( $hero_post ) :
	$hero_id    = $hero_post->ID;
	$hero_cats  = get_the_category( $hero_id );
	$hero_cat   = ! empty( $hero_cats ) ? $hero_cats[0] : null;
?>
<section class="hero">
  <div class="container">

    <div class="hero__text">
      <?php if ( $hero_cat ) : ?>
        <div class="hero__eyebrow">
          <a href="<?php echo esc_url( get_category_link( $hero_cat->term_id ) ); ?>" class="eyebrow eyebrow--orange">
            <?php echo esc_html( $hero_cat->name ); ?>
          </a>
        </div>
      <?php endif; ?>

      <h1 class="hero__headline">
        <a href="<?php echo esc_url( get_permalink( $hero_id ) ); ?>">
          <?php echo esc_html( get_the_title( $hero_id ) ); ?>
        </a>
      </h1>

      <p class="hero__deck">
        <?php echo wp_kses_post( get_the_excerpt( $hero_id ) ); ?>
      </p>

      <p class="hero__meta">
        <?php echo esc_html( get_the_author_meta( 'display_name', get_post_field( 'post_author', $hero_id ) ) ); ?>
        &nbsp;&bull;&nbsp;
        <?php echo logiai_relative_time( $hero_id ); ?>
        &nbsp;&bull;&nbsp;
        <?php echo logiai_reading_time( $hero_id ); ?> <?php esc_html_e( 'min read', 'logiai' ); ?>
      </p>
    </div>

    <div class="hero__image">
      <?php if ( has_post_thumbnail( $hero_id ) ) : ?>
        <?php echo get_the_post_thumbnail( $hero_id, 'logiai-hero', array( 'alt' => esc_attr( get_the_title( $hero_id ) ) ) ); ?>
      <?php else : ?>
        <div class="hero__image-inner hero-placeholder">
          <svg viewBox="0 0 620 480" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect width="620" height="480" fill="#0a1628"/>
            <g stroke="#1e3a5f" stroke-width="0.5" opacity="0.6">
              <line x1="0" y1="80" x2="620" y2="80"/><line x1="0" y1="160" x2="620" y2="160"/>
              <line x1="0" y1="240" x2="620" y2="240"/><line x1="0" y1="320" x2="620" y2="320"/>
              <line x1="0" y1="400" x2="620" y2="400"/>
              <line x1="80" y1="0" x2="80" y2="480"/><line x1="160" y1="0" x2="160" y2="480"/>
              <line x1="240" y1="0" x2="240" y2="480"/><line x1="320" y1="0" x2="320" y2="480"/>
              <line x1="400" y1="0" x2="400" y2="480"/><line x1="500" y1="0" x2="500" y2="480"/>
            </g>
            <g stroke="#057dbc" stroke-width="1.5" opacity="0.8" fill="none">
              <polyline points="60,380 160,300 280,240 400,160 540,100"/>
            </g>
            <g fill="#057dbc">
              <circle cx="60" cy="380" r="5"/><circle cx="160" cy="300" r="4"/>
              <circle cx="280" cy="240" r="6"/><circle cx="400" cy="160" r="4"/><circle cx="540" cy="100" r="5"/>
            </g>
            <text x="30" y="460" font-family="monospace" font-size="11" fill="rgba(255,255,255,0.3)" letter-spacing="1.5">GLOBAL FREIGHT NETWORK / AI ROUTING</text>
          </svg>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- ============================================================
     RIBBON: LATEST
============================================================ -->
<div class="ribbon">
  <div class="container">
    <span class="ribbon__label"><?php esc_html_e( 'Latest Stories', 'logiai' ); ?></span>
    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="ribbon__link">
      <?php esc_html_e( 'View All', 'logiai' ); ?> &rarr;
    </a>
  </div>
</div>

<!-- ============================================================
     STORY GRID — 3 columns
============================================================ -->
<?php if ( ! empty( $grid_posts ) ) : ?>
<section class="story-grid">
  <div class="container">
    <?php foreach ( $grid_posts as $post ) :
      setup_postdata( $post );
      $cats    = get_the_category( $post->ID );
      $cat     = ! empty( $cats ) ? $cats[0] : null;
    ?>
    <article class="story-tile" <?php post_class( 'story-tile', $post->ID ); ?>>

      <div class="story-tile__image">
        <?php if ( has_post_thumbnail( $post->ID ) ) : ?>
          <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
            <?php echo get_the_post_thumbnail( $post->ID, 'logiai-grid', array( 'alt' => esc_attr( get_the_title( $post->ID ) ) ) ); ?>
          </a>
        <?php else : ?>
          <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="img-placeholder" style="display:flex;align-items:center;justify-content:center;background:#1a2a1a;aspect-ratio:4/3;">
            <span class="eyebrow" style="color:rgba(255,255,255,0.4);">LogiAI</span>
          </a>
        <?php endif; ?>
      </div>

      <?php if ( $cat ) : ?>
        <div class="story-tile__eyebrow">
          <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="eyebrow eyebrow--blue">
            <?php echo esc_html( $cat->name ); ?>
          </a>
        </div>
      <?php endif; ?>

      <h2 class="story-tile__headline">
        <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
          <?php echo esc_html( get_the_title( $post->ID ) ); ?>
        </a>
      </h2>

      <p class="story-tile__deck">
        <?php echo wp_kses_post( get_the_excerpt( $post->ID ) ); ?>
      </p>

      <p class="story-tile__meta">
        <?php echo logiai_relative_time( $post->ID ); ?>
      </p>

    </article>
    <?php endforeach; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

<!-- ============================================================
     RIBBON: ANALYSIS
============================================================ -->
<div class="ribbon">
  <div class="container">
    <span class="ribbon__label"><?php esc_html_e( 'In-Depth Analysis', 'logiai' ); ?></span>
    <a href="<?php echo esc_url( home_url( '/category/analysis/' ) ); ?>" class="ribbon__link">
      <?php esc_html_e( 'All Analysis', 'logiai' ); ?> &rarr;
    </a>
  </div>
</div>

<!-- ============================================================
     FEATURED DOUBLE
============================================================ -->
<?php if ( $featured_post ) :
  $fp_id   = $featured_post->ID;
  $fp_cats = get_the_category( $fp_id );
  $fp_cat  = ! empty( $fp_cats ) ? $fp_cats[0] : null;
?>
<section class="featured-double">
  <div class="container">

    <div class="featured-double__main">
      <div class="fd-image">
        <?php if ( has_post_thumbnail( $fp_id ) ) : ?>
          <a href="<?php echo esc_url( get_permalink( $fp_id ) ); ?>">
            <?php echo get_the_post_thumbnail( $fp_id, 'logiai-featured', array( 'alt' => esc_attr( get_the_title( $fp_id ) ) ) ); ?>
          </a>
        <?php else : ?>
          <div style="background:#111; width:100%; aspect-ratio:16/9; display:flex; align-items:center; justify-content:center;">
            <span class="eyebrow" style="color:rgba(255,255,255,0.3);">LogiAI</span>
          </div>
        <?php endif; ?>
      </div>

      <?php if ( $fp_cat ) : ?>
        <div class="eyebrow" style="margin-bottom:12px;">
          <a href="<?php echo esc_url( get_category_link( $fp_cat->term_id ) ); ?>" class="eyebrow">
            <?php echo esc_html( $fp_cat->name ); ?>
          </a>
        </div>
      <?php endif; ?>

      <h2 class="fd-headline">
        <a href="<?php echo esc_url( get_permalink( $fp_id ) ); ?>">
          <?php echo esc_html( get_the_title( $fp_id ) ); ?>
        </a>
      </h2>

      <p class="fd-deck">
        <?php echo wp_kses_post( get_the_excerpt( $fp_id ) ); ?>
      </p>

      <p class="story-tile__meta">
        <?php echo esc_html( get_the_author_meta( 'display_name', get_post_field( 'post_author', $fp_id ) ) ); ?>
        &nbsp;&bull;&nbsp;
        <?php echo logiai_relative_time( $fp_id ); ?>
        &nbsp;&bull;&nbsp;
        <?php echo logiai_reading_time( $fp_id ); ?> <?php esc_html_e( 'min read', 'logiai' ); ?>
      </p>
    </div>

    <div class="featured-double__sidebar">
      <?php foreach ( $sidebar_posts as $post ) :
        setup_postdata( $post );
        $s_cats = get_the_category( $post->ID );
        $s_cat  = ! empty( $s_cats ) ? $s_cats[0] : null;
      ?>
      <article class="sidebar-story">
        <?php if ( $s_cat ) : ?>
          <div class="sidebar-story__eyebrow">
            <a href="<?php echo esc_url( get_category_link( $s_cat->term_id ) ); ?>" class="eyebrow eyebrow--blue">
              <?php echo esc_html( $s_cat->name ); ?>
            </a>
          </div>
        <?php endif; ?>

        <h3 class="sidebar-story__headline">
          <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
            <?php echo esc_html( get_the_title( $post->ID ) ); ?>
          </a>
        </h3>

        <p class="sidebar-story__meta">
          <?php echo logiai_relative_time( $post->ID ); ?>
        </p>
      </article>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- ============================================================
     RIBBON: MOST READ
============================================================ -->
<div class="ribbon">
  <div class="container">
    <span class="ribbon__label"><?php esc_html_e( 'Most Read', 'logiai' ); ?></span>
  </div>
</div>

<!-- ============================================================
     MOST POPULAR
============================================================ -->
<?php if ( $popular->have_posts() ) : ?>
<section class="most-popular">
  <div class="container">
    <?php $count = 1; while ( $popular->have_posts() ) : $popular->the_post(); ?>
    <div class="popular-item">
      <span class="popular-item__num"><?php echo str_pad( $count, 2, '0', STR_PAD_LEFT ); ?></span>
      <div>
        <p class="popular-item__headline">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </p>
        <?php
          $p_cats = get_the_category();
          if ( ! empty( $p_cats ) ) :
        ?>
          <p class="popular-item__meta"><?php echo esc_html( $p_cats[0]->name ); ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php $count++; endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
