<?php
/**
 * Page template（固定ページ）
 *
 * @package classic-starter
 */

get_header();
?>

<div class="inner">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('page-entry'); ?>>
        <header class="page-entry-header">
          <h1 class="page-entry-title"><?php echo esc_html(get_the_title()); ?></h1>
          <?php if (has_post_thumbnail()) : ?>
            <figure class="page-entry-thumbnail">
              <?php the_post_thumbnail('large'); ?>
            </figure>
          <?php endif; ?>
        </header>

        <div class="page-entry-content">
          <?php the_content(); ?>

          <?php
          wp_link_pages(
            array(
              'before' => '<nav class="page-links" aria-label="ページ分割ナビゲーション">' . '<span class="page-links-title">Pages:</span>',
              'after'  => '</nav>',
            )
          );
          ?>
        </div>
      </article>

      <?php
      // コメントを使う場合のみ（不要なら丸ごと削除OK）
      if (comments_open() || get_comments_number()) :
        comments_template();
      endif;
      ?>
    <?php endwhile; ?>
  <?php endif; ?>
</div>

<?php
get_footer();
