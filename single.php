<?php
/**
 * Single post template
 *
 * @package classic-starter
 */

get_header();
?>

<div class="inner layout">
  <div class="layout-main">
    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('post-entry'); ?>>
        <header class="post-entry-header">
          <?php // 日付とカテゴリーは一覧（index.php）と同じ並び・見た目にする ?>
          <div class="post-entry-meta">
            <time class="post-entry-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date(classic_starter_date_format())); ?></time>
            <?php
            $categories = get_the_category_list(' ');
            if ($categories) :
            ?>
              <span class="post-entry-categories"><?php echo wp_kses_post($categories); ?></span>
            <?php endif; ?>
          </div>
          <h1 class="post-entry-title"><?php the_title(); ?></h1>
          <?php if (has_post_thumbnail()) : ?>
            <figure class="post-entry-thumbnail">
              <?php the_post_thumbnail('large'); ?>
            </figure>
          <?php endif; ?>
        </header>

        <div class="post-entry-content entry-content">
          <?php the_content(); ?>

          <?php
          wp_link_pages(array(
            'before' => '<nav class="page-links" aria-label="' . esc_attr__('Page navigation', 'classic-starter') . '">'
              . '<span class="page-links-title">' . esc_html__('Pages:', 'classic-starter') . '</span>',
            'after'  => '</nav>',
          ));
          ?>
        </div>

        <?php
        // カテゴリーは記事の頭に出しているので、記事の終わりはタグだけにする（タグが無ければ線ごと出さない）
        $tags = get_the_tag_list('', ' ');
        if ($tags) :
        ?>
          <footer class="post-entry-footer">
            <p class="post-entry-tags">
              <span class="post-entry-tax-label"><?php echo esc_html__('Tags:', 'classic-starter'); ?></span>
              <?php echo wp_kses_post($tags); ?>
            </p>
          </footer>
        <?php endif; ?>

        <?php // 前後の記事。小さな大文字のラベルの下に記事のタイトルを出す ?>
        <nav class="post-entry-nav" aria-label="<?php echo esc_attr__('Post navigation', 'classic-starter'); ?>">
          <div class="post-entry-nav-prev">
            <?php previous_post_link('%link', '<span class="post-entry-nav-label">' . esc_html__('Prev', 'classic-starter') . '</span><span class="post-entry-nav-title">%title</span>'); ?>
          </div>
          <div class="post-entry-nav-next">
            <?php next_post_link('%link', '<span class="post-entry-nav-label">' . esc_html__('Next', 'classic-starter') . '</span><span class="post-entry-nav-title">%title</span>'); ?>
          </div>
        </nav>
      </article>

      <?php
      // コメントを使う場合のみ（不要なら丸ごと削除OK）
      if (comments_open() || get_comments_number()) :
        comments_template();
      endif;
      ?>
    <?php endwhile; ?>
  </div>

  <?php get_sidebar(); ?>
</div>

<?php
get_footer();
