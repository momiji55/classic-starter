<?php
/**
 * 記事一覧の1件分（index.php と front-page.php のループから読み込む）
 *
 * @package classic-starter
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
  <?php // タイトルと同じリンクが続くので、読み上げ・Tab 移動の対象から外す ?>
  <a class="post-item-thumbnail" href="<?php echo esc_url(get_permalink()); ?>" tabindex="-1" aria-hidden="true">
    <?php if (has_post_thumbnail()) : ?>
      <?php // sm 未満は全幅、md 未満は画面幅の 3 分の 1 程度で出すので、表示幅に合った画像を選ばせる ?>
      <?php the_post_thumbnail('medium', array('sizes' => '(max-width: 479px) 100vw, (max-width: 767px) 33vw, 300px')); ?>
    <?php else : ?>
      <?php // アイキャッチが無くても同じ大きさの枠を出し、文字の左端をそろえる ?>
      <span class="post-item-thumbnail-placeholder"></span>
    <?php endif; ?>
  </a>

  <div class="post-item-body">
    <header class="post-item-header">
      <?php // 固定ページ（検索結果に出る）は日付に意味が無く、カテゴリーも無いので、行ごと出さない ?>
      <?php if (get_post_type() !== 'page') : ?>
        <div class="post-item-meta">
          <?php if (is_sticky() && is_home() && !is_paged()) : ?>
            <span class="post-item-sticky-label"><?php echo esc_html__('Featured', 'classic-starter'); ?></span>
          <?php endif; ?>
          <time class="post-item-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date(classic_starter_date_format())); ?></time>
          <?php
          $categories = get_the_category_list(' ');
          if ($categories) :
          ?>
            <span class="post-item-categories"><?php echo wp_kses_post($categories); ?></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <h2 class="post-item-title">
        <a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
      </h2>
    </header>

    <div class="post-item-content">
      <div class="post-item-excerpt">
        <?php the_excerpt(); ?>
      </div>

      <p class="post-item-more">
        <?php // 同じ文字のリンクが並ぶので、読み上げではどの記事か分かるようタイトルを添える。空白で下線が伸びないよう、a の中は改行しない ?>
        <a class="post-item-more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html__('Read more', 'classic-starter'); ?><span class="screen-reader-text"><?php echo esc_html(get_the_title()); ?></span></a>
      </p>
    </div>
  </div>
</article>
