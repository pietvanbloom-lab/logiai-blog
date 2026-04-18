<?php
/**
 * Static Page Template
 *
 * @package LogiAI
 */

get_header();

while ( have_posts() ) : the_post(); ?>

<div class="ribbon">
  <div class="container">
    <span class="ribbon__label"><?php the_title(); ?></span>
  </div>
</div>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
  <div class="article-body">
    <div class="container">
      <div class="article-body__content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>
