<?php
/**
 * 404 Template
 *
 * @package LogiAI
 */

get_header();
?>

<div class="ribbon">
  <div class="container">
    <span class="ribbon__label">404</span>
  </div>
</div>

<div class="container" style="padding: 80px 24px; text-align: center;">
  <p class="eyebrow" style="margin-bottom: 16px; color: var(--accent-alt);">Page Not Found</p>
  <h1 style="font-family: var(--f-display); font-size: 48px; font-weight: 400; line-height: 1.1; margin-bottom: 24px;">
    This story has moved or doesn't exist.
  </h1>
  <p style="font-family: var(--f-body); font-size: 18px; color: var(--caption-gray); margin-bottom: 36px;">
    Try browsing by category or use search to find what you're looking for.
  </p>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">
    Back to LogiAI
  </a>
</div>

<?php get_footer(); ?>
