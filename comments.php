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
      $comments_number = (int) get_comments_number();
      printf(
        /* translators: %s: comment count */
        esc_html(_n('%s Comment', '%s Comments', $comments_number, 'classic-starter')),
        esc_html(number_format_i18n($comments_number))
      );
      ?>
    </h2>

    <ol class="comments-list">
      <?php
      wp_list_comments(array(
        'style'       => 'ol',
        'short_ping'  => true,
        'avatar_size' => 96,
        // 「Read more」と同じ小さなリンク（大文字にしない）にする。ja.po では「返信」に訳す
        'reply_text'  => esc_html__('Reply', 'classic-starter'),
      ));
      ?>
    </ol>

    <?php
    // ページ分割されたコメントのナビゲーション。記事一覧のページ送りと同じ小さなリンク（大文字にしない）にし、
    // 矢印は付けない。ja.po では「古いコメント」「新しいコメント」に訳す
    the_comments_navigation(array(
      'prev_text' => esc_html__('Older Comments', 'classic-starter'),
      'next_text' => esc_html__('Newer Comments', 'classic-starter'),
    ));
    ?>

    <?php if (! comments_open()) : ?>
      <p class="comments-closed"><?php echo esc_html__('Comments are closed.', 'classic-starter'); ?></p>
    <?php endif; ?>
  <?php endif; ?>

  <?php
  comment_form(array(
    'title_reply'        => esc_html__('Leave a Comment', 'classic-starter'),
    // 初期値の h3 では、コメント一覧の見出し（h2）と並ぶ関係にならず、コメントが無い時は階層が飛ぶので h2 にする
    'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
    'title_reply_after'  => '</h2>',
    // 送信ボタンは 404 のボタンと同じ汎用の .button にする
    'class_submit'       => 'submit button',
  ));
  ?>
</section>
