<?php
/**
 * Single post template
 *
 * @package classic-starter
 */

get_header();
?>

<div class="inner">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('post-entry'); ?>>
        <header class="post-entry-header">
          <h1 class="post-entry-title"><?php the_title(); ?></h1>
          <?php if (has_post_thumbnail()) : ?>
            <figure class="post-entry-thumbnail">
              <?php the_post_thumbnail('large'); ?>
            </figure>
          <?php endif; ?>
          <p class="post-entry-meta">
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
              <?php echo esc_html(get_the_date()); ?>
            </time>
          </p>
        </header>

        <div class="post-entry-content">
          <?php the_content(); ?>

          <?php
          wp_link_pages(array(
            'before' => '<nav class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'textdomain') . '</span>',
            'after'  => '</nav>',
          ));
          ?>
        </div>

        <footer class="post-entry-footer">
          <?php
          $categories = get_the_category_list(' ');
          if ($categories) :
          ?>
            <p class="post-entry-categories">
              <span class="post-entry-tax-label"><?php echo esc_html__('Categories:', 'textdomain'); ?></span>
              <?php echo wp_kses_post($categories); ?>
            </p>
          <?php endif; ?>

          <?php
          $tags = get_the_tag_list('', ' ');
          if ($tags) :
          ?>
            <p class="post-entry-tags">
              <span class="post-entry-tax-label"><?php echo esc_html__('Tags:', 'textdomain'); ?></span>
              <?php echo wp_kses_post($tags); ?>
            </p>
          <?php endif; ?>
        </footer>

        <nav class="post-entry-nav" aria-label="<?php echo esc_attr__('Post navigation', 'textdomain'); ?>">
          <div class="post-entry-nav-prev">
            <?php previous_post_link('%link', esc_html__('&larr; Prev', 'textdomain')); ?>
          </div>
          <div class="post-entry-nav-next">
            <?php next_post_link('%link', esc_html__('Next &rarr;', 'textdomain')); ?>
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
  <?php else : ?>
    <section class="no-posts">
      <h1 class="no-posts-title"><?php echo esc_html__('Post not found', 'textdomain'); ?></h1>
      <p class="no-posts-text"><?php echo esc_html__('お探しの記事は見つかりませんでした。', 'textdomain'); ?></p>
    </section>
  <?php endif; ?>
</div>

<?php
get_footer();
