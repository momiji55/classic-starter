<?php
/**
 * Footer template
 *
 * @package classic-starter
 */
?>
</main>

<footer class="footer">
  <div class="inner">
    <?php if (has_nav_menu('footer')) : ?>
      <nav class="footer-nav" aria-label="<?php echo esc_attr__('Footer Menu', 'classic-starter'); ?>">
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'footer',
            'menu_class'     => 'footer-nav-list',
            'container'      => false,
            'depth'          => 1,
          )
        );
        ?>
      </nav>
    <?php endif; ?>

    <div class="footer-bottom">
      <?php // プライバシーポリシーのページが設定・公開されている時だけ出力される ?>
      <?php the_privacy_policy_link('<p class="footer-privacy">', '</p>'); ?>
      <p class="footer-copyright">&copy; <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?></p>
    </div>
  </div>

  <?php // ページの先頭へ戻るボタン。<body id="top"> へ移動し、キーボードの Tab の起点も先頭に戻す（JS 無効時も動く）。
  // フッターの中に置き、パネルを開いた時に header.js がフッターごと inert にする対象に含める ?>
  <a class="page-top" href="#top">
    <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
      <polyline points="3,10.5 8,5.5 13,10.5" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></polyline>
    </svg>
    <span class="screen-reader-text"><?php echo esc_html__('Back to top', 'classic-starter'); ?></span>
  </a>
</footer>

<?php wp_footer(); ?>
</body>
</html>
