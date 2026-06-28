<?php
/**
 * 404 template
 *
 * @package classic-starter
 */

get_header();
?>

<div class="inner">
  <section class="error-404 not-found">
    <header class="error-404-header">
      <?php // 数字を大きく見せ、下に小さな大文字のラベルを置く（見出しは1つのまま、読み上げでは続けて読まれる） ?>
      <h1 class="error-404-title">
        <span class="error-404-code">404</span>
        <span class="error-404-label"><?php echo esc_html__('Page Not Found', 'classic-starter'); ?></span>
      </h1>
      <p class="error-404-text"><?php echo esc_html__('The page you are looking for could not be found. The URL may have changed, or the page may have been deleted.', 'classic-starter'); ?></p>
    </header>
    <div class="error-404-search">
      <?php get_search_form(); ?>
    </div>
    <a class="button" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Back to Home', 'classic-starter'); ?></a>
  </section>
</div>

<?php
get_footer();
