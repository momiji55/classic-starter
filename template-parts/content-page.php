<?php
/**
 * 固定ページの本文とコメント（page.php と page-templates/no-sidebar.php のループから読み込む）
 *
 * @package classic-starter
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('page-entry'); ?>>
  <header class="page-entry-header">
    <h1 class="page-entry-title"><?php the_title(); ?></h1>
    <?php if (has_post_thumbnail()) : ?>
      <figure class="page-entry-thumbnail">
        <?php the_post_thumbnail('large'); ?>
      </figure>
    <?php endif; ?>
  </header>

  <div class="page-entry-content entry-content">
    <?php the_content(); ?>

    <?php
    wp_link_pages(
      array(
        'before' => '<nav class="page-links" aria-label="' . esc_attr__('Page navigation', 'classic-starter') . '">'
          . '<span class="page-links-title">' . esc_html__('Pages:', 'classic-starter') . '</span>',
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
