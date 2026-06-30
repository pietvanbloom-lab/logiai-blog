<!-- ============================================================
     NEWSLETTER BAND
============================================================ -->
<section class="newsletter">
  <div class="container">
    <div class="newsletter__label"><?php esc_html_e( 'Newsletter', 'logiai' ); ?></div>
    <h3 class="newsletter__headline">
      <?php esc_html_e( 'AI + Logistics intelligence, every Tuesday morning.', 'logiai' ); ?>
    </h3>
    <form class="newsletter__form" action="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>" method="post">
      <input
        class="newsletter__input"
        type="email"
        name="email"
        placeholder="<?php esc_attr_e( 'your@email.com', 'logiai' ); ?>"
        required
        aria-label="<?php esc_attr_e( 'Email address', 'logiai' ); ?>"
      />
      <button class="newsletter__btn" type="submit">
        <?php esc_html_e( 'Subscribe', 'logiai' ); ?>
      </button>
      <?php wp_nonce_field( 'logiai_newsletter', 'logiai_nonce' ); ?>
    </form>
  </div>
</section>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="footer" role="contentinfo">
  <div class="container">

    <div class="footer__top">
      <div class="footer__col">
        <div class="footer__col-label"><?php esc_html_e( 'Topics', 'logiai' ); ?></div>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/category/supply-chain/' ) ); ?>"><?php esc_html_e( 'Supply Chain AI', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/automation/' ) ); ?>"><?php esc_html_e( 'Warehouse Robotics', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/last-mile/' ) ); ?>"><?php esc_html_e( 'Last Mile Delivery', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/freight-ai/' ) ); ?>"><?php esc_html_e( 'Freight Tech', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/sustainability/' ) ); ?>"><?php esc_html_e( 'Sustainability', 'logiai' ); ?></a></li>
        </ul>
      </div>
      <div class="footer__col">
        <div class="footer__col-label"><?php esc_html_e( 'Analysis', 'logiai' ); ?></div>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/category/research/' ) ); ?>"><?php esc_html_e( 'Research Reports', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/case-studies/' ) ); ?>"><?php esc_html_e( 'Case Studies', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>"><?php esc_html_e( 'Tool Benchmarks', 'logiai' ); ?></a></li>
        </ul>
      </div>
      <div class="footer__col">
        <div class="footer__col-label"><?php esc_html_e( 'About', 'logiai' ); ?></div>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'LogiAI Mission', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/editorial-standards/' ) ); ?>"><?php esc_html_e( 'Editorial Standards', 'logiai' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'logiai' ); ?></a></li>
        </ul>
      </div>
      <div class="footer__col">
        <div class="footer__col-label"><?php esc_html_e( 'Follow', 'logiai' ); ?></div>
        <ul>
          <li><a href="https://www.linkedin.com/company/logiai" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
          <li><a href="https://x.com/logiai_blog" target="_blank" rel="noopener noreferrer">Twitter / X</a></li>
          <?php
          $rss_url = get_bloginfo( 'rss2_url' );
          ?>
          <li><a href="<?php echo esc_url( $rss_url ); ?>">RSS Feed</a></li>
        </ul>
      </div>
    </div>

    <div class="footer__bottom">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo">LOGI<span>AI</span></a>
      <div class="footer__legal">
        &copy; <?php echo date( 'Y' ); ?> LogiAI Blog &nbsp;&bull;&nbsp;
        <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'logiai' ); ?></a>
        &nbsp;&bull;&nbsp;
        <a href="<?php echo esc_url( home_url( '/imprint/' ) ); ?>"><?php esc_html_e( 'Imprint', 'logiai' ); ?></a>
      </div>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
