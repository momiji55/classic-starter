<?php
/**
 * Main template file
 *
 * @package classic-starter
 */

get_header();
?>

<div class="inner layout">
  <div class="layout-main">
    <?php if (is_search()) : ?>
      <?php // 検索結果は 0 件の時も見出し・件数・検索フォームを出し、検索し直せるようにする ?>
      <header class="archive-header">
        <?php // 見出しは小さな大文字のラベルと大きな検索語を縦に積み、件数は検索語の横に添える ?>
        <div class="archive-heading">
          <h1 class="archive-title">
            <span class="archive-label"><?php echo esc_html__('Search Results', 'classic-starter'); ?></span>
            <?php // 空で検索した時は空の括弧を出さない ?>
            <?php if (get_search_query() !== '') : ?>
              <span class="archive-name">
                <?php
                printf(
                  /* translators: %s: search query. Quotation marks around the search query. */
                  esc_html_x('&ldquo;%s&rdquo;', 'search query', 'classic-starter'),
                  esc_html(get_search_query())
                );
                ?>
              </span>
            <?php endif; ?>
          </h1>
          <p class="archive-count">
            <?php
            $found_posts = (int) $wp_query->found_posts;
            printf(
              /* translators: %s: number of search results */
              esc_html(_n('%s result', '%s results', $found_posts, 'classic-starter')),
              esc_html(number_format_i18n($found_posts))
            );
            ?>
          </p>
        </div>
        <div class="archive-search">
          <?php get_search_form(); ?>
        </div>
      </header>
    <?php elseif (is_archive()) : ?>
      <header class="archive-header">
        <?php // 検索結果と同じく、アーカイブの種類のラベルと大きな名前を縦に積む（名前の前置きは functions.php で外している）。
        // 記事が0件のカテゴリー・タグでも、どのアーカイブか分かるよう出す ?>
        <div class="archive-heading">
          <h1 class="archive-title">
            <span class="archive-label"><?php echo esc_html(classic_starter_archive_label()); ?></span>
            <span class="archive-name"><?php echo wp_kses_post(get_the_archive_title()); ?></span>
          </h1>
        </div>
        <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
      </header>
    <?php elseif (is_home() && !is_front_page()) : ?>
      <?php // 固定ページをトップにした時の投稿ページ。サイト名が h1 ではないので、アーカイブと同じ形で投稿ページのタイトルを h1 にする ?>
      <header class="archive-header">
        <div class="archive-heading">
          <h1 class="archive-title">
            <span class="archive-label"><?php echo esc_html__('Blog', 'classic-starter'); ?></span>
            <span class="archive-name"><?php echo wp_kses_post(get_the_title((int) get_option('page_for_posts'))); ?></span>
          </h1>
        </div>
      </header>
    <?php endif; ?>

    <?php if (have_posts()) : ?>

      <div class="posts">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/post-item'); ?>
        <?php endwhile; ?>
      </div>

      <?php classic_starter_posts_pagination(); ?>

    <?php elseif (is_search()) : ?>
      <?php // 見出しは上の検索結果の h1 があるので h2 にする ?>
      <section class="no-posts">
        <h2 class="no-posts-title"><?php echo esc_html__('No results found', 'classic-starter'); ?></h2>
        <p class="no-posts-text"><?php echo esc_html__('Try searching again with different or fewer keywords.', 'classic-starter'); ?></p>
      </section>

    <?php elseif (is_archive() || (is_home() && !is_front_page())) : ?>
      <?php // 見出しは上のアーカイブ・投稿ページの h1 があるので h2 にする ?>
      <section class="no-posts">
        <h2 class="no-posts-title"><?php echo esc_html__('No posts found', 'classic-starter'); ?></h2>
        <p class="no-posts-text"><?php echo esc_html__('There are no posts yet, or no posts match your criteria.', 'classic-starter'); ?></p>
      </section>

    <?php else : ?>
      <section class="no-posts">
        <h1 class="no-posts-title"><?php echo esc_html__('No posts found', 'classic-starter'); ?></h1>
        <p class="no-posts-text"><?php echo esc_html__('There are no posts yet, or no posts match your criteria.', 'classic-starter'); ?></p>
      </section>
    <?php endif; ?>
  </div>

  <?php get_sidebar(); ?>
</div>

<?php
get_footer();
