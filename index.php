<?php
/**
 * Main template file
 *
 * @package classic-starter
 */

get_header();
?>

<div class="inner">
  <?php if (have_posts()) : ?>

    <?php if (is_archive() || is_search()) : ?>
      <header class="archive-header">
        <?php if (is_search()) : ?>
          <h1 class="archive-title">
            <?php
            // 検索結果ページの見出し
            printf(
              /* translators: %s: 検索キーワード */
              esc_html__('「%s」の検索結果', 'classic-starter'),
              '<span class="archive-search-term">' . esc_html(get_search_query()) . '</span>'
            );
            ?>
          </h1>
        <?php else : ?>
          <?php the_archive_title('<h1 class="archive-title">', '</h1>'); ?>
          <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
        <?php endif; ?>
      </header>
    <?php endif; ?>

    <div class="posts">
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
          <header class="post-item-header">
          <?php if (is_singular()) : ?>
            <h1 class="post-item-title"><?php echo esc_html(get_the_title()); ?></h1>
          <?php else : ?>
            <h2 class="post-item-title">
              <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a>
            </h2>
          <?php endif; ?>
          </header>

          <div class="post-item-content">
            <?php if (is_singular()) : ?>
              <?php the_content(); ?>

              <?php
              wp_link_pages(
                array(
                  'before' => '<nav class="page-links" aria-label="ページ分割ナビゲーション">' . '<span class="page-links-title">Pages:</span>',
                  'after'  => '</nav>',
                )
              );
              ?>
            <?php else : ?>
              <?php the_excerpt(); ?>

              <p class="post-item-more">
                <a class="post-item-more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html__('続きを読む', 'classic-starter'); ?></a>
              </p>
            <?php endif; ?>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <?php the_posts_navigation(); ?>

  <?php else : ?>
    <section class="no-posts">
      <h1 class="no-posts-title">投稿が見つかりませんでした</h1>
      <p class="no-posts-text">まだ投稿がないか、条件に一致する投稿がありません。</p>
    </section>
  <?php endif; ?>
</div>

<?php
get_footer();
