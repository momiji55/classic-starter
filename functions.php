<?php
/**
 * Theme functions
 *
 * @package classic-starter
 */

 // テーマの初期設定（タイトルタグやHTML5対応など）を有効化する
add_action('after_setup_theme', function () {
  // <title> を WordPress に任せる（header.php に <title> を書かない構成）
  add_theme_support('title-tag');

  // 検索フォームなどをHTML5マークアップで出力
  add_theme_support('html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
    'style',
    'script',
  ));

  // 投稿・固定ページのアイキャッチを使う場合は有効化
  add_theme_support('post-thumbnails');
});

add_filter('style_loader_tag', function ($html) {
	// linkタグの自己終了だけを変換（中身があるstyleタグは除外）
	return preg_replace('/(<link\b[^>]*?)\s*\/>\s*/', '$1>', $html);
});

add_action('wp_enqueue_scripts', function () {
  $theme = wp_get_theme();
  $version = $theme->get('Version');

  // テーマの style.css
  wp_enqueue_style(
    'classic-starter-style',
    get_stylesheet_uri(),
    array(),
    $version
  );

  // 共通CSS
  wp_enqueue_style(
    'classic-starter-common',
    get_template_directory_uri() . '/assets/css/common.css',
    array('classic-starter-style'),
    $version
  );

  // 404ページ専用CSS
  if (is_404()) {
    wp_enqueue_style(
      'classic-starter-404',
      get_template_directory_uri() . '/assets/css/404.css',
      array('classic-starter-common'),
      $version
    );
  }

  // 投稿詳細ページ専用CSS
  if (is_single()) {
    wp_enqueue_style(
      'classic-starter-single',
      get_template_directory_uri() . '/assets/css/single.css',
      array('classic-starter-common'),
      $version
    );
  }

  // 固定ページ専用CSS
  if (is_page()) {
    wp_enqueue_style(
      'classic-starter-page',
      get_template_directory_uri() . '/assets/css/page.css',
      array('classic-starter-common'),
      $version
    );
  }

  // コメント用CSS（投稿・固定ページでコメントが表示される場合のみ）
  if (is_singular() && (comments_open() || get_comments_number())) {
    wp_enqueue_style(
      'classic-starter-comments',
      get_template_directory_uri() . '/assets/css/comments.css',
      array('classic-starter-common'),
      $version
    );
  }
});
