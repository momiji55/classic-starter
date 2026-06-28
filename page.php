<?php
/**
 * Page template（固定ページ）
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

  <?php get_sidebar(); ?>
</div>

<?php
get_footer();
