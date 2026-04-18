<?php
/**
 * Index — fallback template
 * Used for blog index when no front-page.php is applicable.
 *
 * @package LogiAI
 */

get_header();
?>

<div class="ribbon">
  <div class="container">
    <span class="ribbon__label"><?php esc_html_e( 'All Stories', 'logiai' ); ?></span>
  </div>
</div>

<?php if ( have_posts() ) : ?>
<section class="archive-grid">
  <div class="container">
    <?php while ( have_posts() ) : the_post();
      $cats = get_the_category();
      $cat  = ! empty( $cats ) ? $cats[0] : null;
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-tile' ); ?>>

      <div class="archive-tile__image">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'logiai-grid', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
          </a>
        <?php else : ?>
          <a href="<?php the_permalink(); ?>" class="img-placeholder img-placeholder--dark">
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
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>

      <p class="story-tile__deck"><?php the_excerpt(); ?></p>

      <p class="story-tile__meta">
        <?php echo logiai_relative_time(); ?>
        &nbsp;&bull;&nbsp;
        <?php echo logiai_reading_time(); ?> <?php esc_html_e( 'min', 'logiai' ); ?>
      </p>

    </article>
    <?php endwhile; ?>
  </div>
</section>

<div class="archive-pagination container">
  <?php the_posts_pagination( array(
    'prev_text' => '&larr; ' . esc_html__( 'Older', 'logiai' ),
    'next_text' => esc_html__( 'Newer', 'logiai' ) . ' &rarr;',
    'mid_size'  => 2,
  ) ); ?>
</div>

<?php else : ?>
<div class="container" style="padding: 80px 24px; text-align: center;">
  <p class="eyebrow"><?php esc_html_e( 'No stories found.', 'logiai' ); ?></p>
</div>
<?php endif; ?>

<?php get_footer(); ?>
