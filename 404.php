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
      <h1 class="error-404-title">404 Not Found</h1>
      <p class="error-404-lead">お探しのページは見つかりませんでした。</p>
    </header>
    <div class="error-404-body">
      <p class="error-404-text">URLが変更されたか、ページが削除された可能性があります。</p>
      <div class="error-404-search">
        <?php get_search_form(); ?>
      </div>
      <div class="error-404-actions">
        <a class="button error-404-home-link" href="<?php echo esc_url(home_url('/')); ?>">TOPページへ</a>
      </div>
    </div>
  </section>
</div>

<?php
get_footer();
