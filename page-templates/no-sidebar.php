<?php
/**
 * Template Name: No Sidebar
 *
 * 固定ページのテンプレート（サイドバーなし）。お問い合わせ・LP など、サイドバーを出さずに見せたいページ用。
 * 本文は読みやすい行幅のまま、幅広・全幅のブロックで広げる（サイドバーが無いので全幅は画面の端まで広がる）
 *
 * @package classic-starter
 */

get_header();
?>

<div class="inner layout">
  <div class="layout-main">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('template-parts/content-page'); ?>
    <?php endwhile; ?>
  </div>
</div>

<?php
get_footer();
