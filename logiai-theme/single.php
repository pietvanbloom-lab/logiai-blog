<?php
/**
 * Single Post Template
 *
 * @package LogiAI
 */

get_header();

while ( have_posts() ) : the_post();
	$cats       = get_the_category();
	$cat        = ! empty( $cats ) ? $cats[0] : null;
	$author_id  = get_the_author_meta( 'ID' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>

  <!-- ============================================================
       ARTICLE HEADER
  ============================================================ -->
  <header class="article-header">
    <div class="container article-header__inner">

      <?php if ( $cat ) : ?>
        <div class="article-header__eyebrow">
          <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="eyebrow eyebrow--blue">
            <?php echo esc_html( $cat->name ); ?>
          </a>
        </div>
      <?php endif; ?>

      <h1 class="article-header__headline"><?php the_title(); ?></h1>

      <?php if ( has_excerpt() ) : ?>
        <p class="article-header__deck"><?php the_excerpt(); ?></p>
      <?php endif; ?>

      <div class="article-header__meta">
        <span class="article-header__author">
          <?php echo esc_html( get_the_author() ); ?>
        </span>
        <span class="article-header__sep">&bull;</span>
        <span class="article-header__date">
          <?php echo logiai_relative_time(); ?>
        </span>
        <span class="article-header__sep">&bull;</span>
        <span class="article-header__read">
          <?php echo logiai_reading_time(); ?> <?php esc_html_e( 'min read', 'logiai' ); ?>
        </span>
      </div>

    </div>
  </header>

  <?php if ( has_post_thumbnail() ) : ?>
  <div class="article-hero-image">
    <div class="container">
      <?php the_post_thumbnail( 'logiai-featured', array( 'class' => 'article-hero-image__img', 'alt' => esc_attr( get_the_title() ) ) ); ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ============================================================
       ARTICLE BODY
  ============================================================ -->
  <div class="article-body">
    <div class="container">
      <div class="article-body__content">
        <?php the_content(); ?>
        <?php
          wp_link_pages( array(
            'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'logiai' ),
            'after'       => '</div>',
            'link_before' => '<span class="page-link">',
            'link_after'  => '</span>',
          ) );
        ?>
      </div>
    </div>
  </div>

  <!-- ============================================================
       ARTICLE FOOTER: tags, share
  ============================================================ -->
  <footer class="article-footer">
    <div class="container">
      <hr class="rule--hair" style="border-top:1px solid #e2e8f0; margin-bottom:24px;">

      <?php
        $tags = get_the_tags();
        if ( $tags ) :
      ?>
        <div class="article-footer__tags">
          <span class="eyebrow" style="margin-right:12px;">Tags:</span>
          <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="article-tag">
              <?php echo esc_html( $tag->name ); ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </footer>

</article>

<!-- ============================================================
     RELATED POSTS
============================================================ -->
<?php
  $related = new WP_Query( array(
    'category__in'   => wp_get_post_categories( get_the_ID() ),
    'post__not_in'   => array( get_the_ID() ),
    'posts_per_page' => 3,
    'orderby'        => 'rand',
  ) );

  if ( $related->have_posts() ) :
?>
<div class="ribbon">
  <div class="container">
    <span class="ribbon__label"><?php esc_html_e( 'Related Stories', 'logiai' ); ?></span>
  </div>
</div>

<section class="story-grid">
  <div class="container">
    <?php while ( $related->have_posts() ) : $related->the_post();
      $r_cats = get_the_category();
      $r_cat  = ! empty( $r_cats ) ? $r_cats[0] : null;
    ?>
    <article class="story-tile">
      <div class="story-tile__image">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'logiai-grid', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
          </a>
        <?php else : ?>
          <a href="<?php the_permalink(); ?>" class="img-placeholder" style="display:flex;align-items:center;justify-content:center;background:#1a2a1a;aspect-ratio:4/3;">
            <span class="eyebrow" style="color:rgba(255,255,255,0.4);">LogiAI</span>
          </a>
        <?php endif; ?>
      </div>
      <?php if ( $r_cat ) : ?>
        <div class="story-tile__eyebrow">
          <a href="<?php echo esc_url( get_category_link( $r_cat->term_id ) ); ?>" class="eyebrow eyebrow--blue">
            <?php echo esc_html( $r_cat->name ); ?>
          </a>
        </div>
      <?php endif; ?>
      <h2 class="story-tile__headline">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>
      <p class="story-tile__meta"><?php echo logiai_relative_time(); ?></p>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
