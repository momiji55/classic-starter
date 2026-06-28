<?php
/**
 * Header template
 *
 * @package classic-starter
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text skip-link" href="#main"><?php echo esc_html__('Skip to content', 'classic-starter'); ?></a>

<?php
// サイト説明はヘッダーの上の帯に出す。説明が空の時は、背景を含めて帯ごと出さない
$site_description = get_bloginfo('description');
?>
<?php if ($site_description) : ?>
  <div class="header-top">
    <p class="inner header-top-text"><?php echo esc_html($site_description); ?></p>
  </div>
<?php endif; ?>

<header class="header">
  <div class="inner header-inner">
    <div class="header-branding">
      <?php // トップページでは「表示設定」がどちらの時もサイト名を h1 にする ?>
      <?php if (is_front_page()) : ?>
        <h1 class="header-ttl"><?php classic_starter_branding(); ?></h1>
      <?php else : ?>
        <p class="header-ttl"><?php classic_starter_branding(); ?></p>
      <?php endif; ?>
    </div>

    <?php // hidden と aria-expanded は header.js が付け外しする（JS 無効時はパネルがそのまま並ぶ） ?>
    <div class="header-actions">
      <button class="header-action header-search-toggle" type="button" aria-controls="header-search" data-header-panel="search" hidden>
        <svg class="header-action-icon" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="1.6"></circle>
          <line x1="16" y1="16" x2="20.5" y2="20.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"></line>
        </svg>
        <span class="header-action-label"><?php echo esc_html__('Search', 'classic-starter'); ?></span>
      </button>

      <?php if (has_nav_menu('primary')) : ?>
        <button class="header-action header-nav-toggle" type="button" aria-controls="primary-menu" data-header-panel="nav" hidden>
          <span class="header-action-icon" aria-hidden="true">
            <span class="header-nav-toggle-icon"></span>
          </span>
          <span class="header-action-label"><?php echo esc_html__('Menu', 'classic-starter'); ?></span>
        </button>
      <?php endif; ?>
    </div>

    <?php // ボタンの後ろに置き、SP でメニューを開いた後の Tab が開閉ボタンからメニューの項目へ進むようにする（md 以上は CSS で2行目に置く） ?>
    <?php if (has_nav_menu('primary')) : ?>
      <nav class="header-nav" aria-label="<?php echo esc_attr__('Primary Menu', 'classic-starter'); ?>">
        <?php
        wp_nav_menu(
          array(
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'menu_class'     => 'header-nav-list',
            'container'      => false,
            'depth'          => 2,
          )
        );
        ?>
      </nav>
    <?php endif; ?>
  </div>
</header>

<?php // 検索のモーダル。ヘッダーごと暗幕で覆うため、ヘッダーの外に置く（JS 無効時はヘッダーの下にフォームが並ぶ） ?>
<div class="header-search" id="header-search">
  <div class="header-search-inner">
    <?php get_search_form(); ?>

    <?php // 閉じるボタン。フォームのすぐ上の右端に文字付きで置く。モーダルとして開く時（JS が動く時）だけ header.js が表示する ?>
    <button class="header-search-close" type="button" hidden>
      <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
        <line x1="3" y1="3" x2="13" y2="13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"></line>
        <line x1="13" y1="3" x2="3" y2="13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"></line>
      </svg>
      <?php echo esc_html__('Close', 'classic-starter'); ?>
    </button>
  </div>
</div>

<?php // パンくずリスト（トップページでは出さない）。本文へのスキップリンクで飛ばせるよう、<main> の手前に置く ?>
<?php classic_starter_breadcrumb(); ?>

<main class="main" id="main">
