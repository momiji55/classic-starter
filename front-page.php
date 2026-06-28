<?php
/**
 * Front page template（トップページ）
 *
 * 「設定 → 表示設定」のホームページの表示がどちらの時もトップページに使われる。
 * ページの本文（固定ページをトップにした時だけ）→ 最新の記事 →
 * ウィジェットエリア「Front page bottom」（お問い合わせへの誘導を置く想定）の順に並べる。
 * サイドバーはほかのページと同じく、ウィジェットがある時だけ本文の右に出す。
 * メインビジュアルは案件ごとに作るので、テーマには持たない。
 *
 * @package classic-starter
 */

get_header();

// 固定ページをトップにしているか（「最新の投稿」の時は、メインのループが記事の一覧になる）
$is_static_front = is_page();
?>

<div class="inner layout">
  <div class="layout-main">
    <?php if ($is_static_front) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php // 固定ページの本文。トップページの見出しはヘッダーのサイト名（h1）が担うので、ページのタイトルは出さない。
        // 本文が空の時は、下の余白だけが残らないよう枠ごと出さない ?>
        <?php if (trim(get_the_content()) !== '') : ?>
          <div class="front-content entry-content">
            <?php the_content(); ?>
          </div>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php endif; ?>

    <?php
    // 最新の記事。固定ページをトップにした時は「表示設定」の件数だけ取得し、投稿ページへのリンクを添える
    $latest_posts = $is_static_front
      ? new WP_Query(array(
        'posts_per_page'      => (int) get_option('posts_per_page'),
        'ignore_sticky_posts' => true,
        // ページ送りを出さないので、全体の件数は数えない
        'no_found_rows'       => true,
      ))
      : $GLOBALS['wp_query'];
    ?>
    <?php // 固定ページをトップにした時は、記事が無ければ節ごと出さない。
    // 「最新の投稿」をトップにした時は記事の一覧がページの本文なので、記事が無い時も見出しと案内文を出す ?>
    <?php if ($latest_posts->have_posts() || !$is_static_front) : ?>
      <section class="front-section front-posts">
        <?php // 見出しは検索結果・アーカイブと同じく、小さな大文字のラベルと大きな名前を縦に積む（common.css の .archive-*） ?>
        <h2 class="archive-title front-section-title">
          <span class="archive-label"><?php echo esc_html__('Blog', 'classic-starter'); ?></span>
          <span class="archive-name"><?php echo esc_html__('Latest Posts', 'classic-starter'); ?></span>
        </h2>

        <?php if ($latest_posts->have_posts()) : ?>
          <div class="posts">
            <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
              <?php get_template_part('template-parts/post-item'); ?>
            <?php endwhile; ?>
          </div>

          <?php if ($is_static_front) : ?>
            <?php
            wp_reset_postdata();
            $page_for_posts = (int) get_option('page_for_posts');
            ?>
            <?php if ($page_for_posts) : ?>
              <p class="front-posts-more">
                <a class="post-item-more-link" href="<?php echo esc_url(get_permalink($page_for_posts)); ?>"><?php echo esc_html__('View all posts', 'classic-starter'); ?></a>
              </p>
            <?php endif; ?>
          <?php else : ?>
            <?php classic_starter_posts_pagination(); ?>
          <?php endif; ?>
        <?php else : ?>
          <?php // 見出しは上の「Latest Posts」（h2）の下なので h3 にする ?>
          <div class="no-posts">
            <h3 class="no-posts-title"><?php echo esc_html__('No posts found', 'classic-starter'); ?></h3>
            <p class="no-posts-text"><?php echo esc_html__('There are no posts yet, or no posts match your criteria.', 'classic-starter'); ?></p>
          </div>
        <?php endif; ?>
      </section>
    <?php endif; ?>

    <?php if (is_active_sidebar('front-page')) : ?>
      <div class="front-section front-widgets">
        <?php dynamic_sidebar('front-page'); ?>
      </div>
    <?php endif; ?>
  </div>

  <?php get_sidebar(); ?>
</div>

<?php
get_footer();
