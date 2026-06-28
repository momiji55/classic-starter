<?php
/**
 * Comments template
 *
 * @package classic-starter
 */

// パスワード保護中の投稿ではコメントを表示しない
if (post_password_required()) {
  return;
}
?>

<section id="comments" class="comments">
  <?php if (have_comments()) : ?>
    <h2 class="comments-title">
      <?php
      $comments_number = get_comments_number();
      if ($comments_number === '1') {
        echo esc_html__('1 Comment', 'textdomain');
      } else {
        printf(
          /* translators: %s: コメント件数 */
          esc_html__('%s Comments', 'textdomain'),
          esc_html(number_format_i18n($comments_number))
        );
      }
      ?>
    </h2>

    <ol class="comments-list">
      <?php
      wp_list_comments(array(
        'style'      => 'ol',
        'short_ping' => true,
        'avatar_size' => 48,
      ));
      ?>
    </ol>

    <?php
    // ページ分割されたコメントのナビゲーション
    the_comments_navigation(array(
      'prev_text' => esc_html__('&larr; Older Comments', 'textdomain'),
      'next_text' => esc_html__('Newer Comments &rarr;', 'textdomain'),
    ));
    ?>

    <?php if (! comments_open()) : ?>
      <p class="comments-closed"><?php echo esc_html__('コメントは受け付けていません。', 'textdomain'); ?></p>
    <?php endif; ?>
  <?php endif; ?>

  <?php
  comment_form(array(
    'class_form'   => 'comment-form',
    'title_reply'  => esc_html__('Leave a Comment', 'textdomain'),
  ));
  ?>
</section>
