<?php
/**
 * Theme functions
 *
 * @package classic-starter
 */

/* =========================
  テーマの初期設定
========================= */

// 本文の最大幅（埋め込み・本文に挿入する画像の幅の上限）。
// lg 以上でサイドバーがある時の本文の列（--container-max - --gutter * 2 - --sidebar-width - --gap-l）に合わせる。
// ほかのプラグインが参照できるよう、優先度 0 で先に設定する
add_action('after_setup_theme', function () {
  $GLOBALS['content_width'] = 826;
}, 0);

// テーマの初期設定（タイトルタグやHTML5対応など）を有効化する
add_action('after_setup_theme', function () {
  // 翻訳ファイル（languages/*.mo）を読み込む
  load_theme_textdomain('classic-starter', get_template_directory() . '/languages');

  // <title> を WordPress に任せる（header.php に <title> を書かない構成）
  add_theme_support('title-tag');

  // RSS フィードの <link> を <head> に自動出力する
  add_theme_support('automatic-feed-links');

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

  // 投稿・固定ページのアイキャッチを使う
  add_theme_support('post-thumbnails');

  // 埋め込み（YouTube 等）を縦横比を保ったまま画面幅に合わせる（必要な CSS はコアのブロック CSS に含まれる）
  add_theme_support('responsive-embeds');

  // エディターの配置に「幅広」「全幅」を出す（広げ方は common.css の .alignwide / .alignfull）
  add_theme_support('align-wide');

  // エディターの本文をフロントと同じ見た目にする（content.css をフロントと共有する）
  add_theme_support('editor-styles');
  add_editor_style(array(
    'assets/css/tokens.css',
    'assets/css/editor-style.css',
    'assets/css/content.css',
  ));

  // エディターで選べる色を役割のトークンに絞る（HEX は色見本用。実際の色は tokens.css で --color-* に差し替える）
  add_theme_support('editor-color-palette', array(
    array('name' => __('Text', 'classic-starter'), 'slug' => 'text', 'color' => '#222'),
    array('name' => __('Muted', 'classic-starter'), 'slug' => 'text-muted', 'color' => '#707070'),
    array('name' => __('Background', 'classic-starter'), 'slug' => 'bg', 'color' => '#fff'),
    array('name' => __('Subtle', 'classic-starter'), 'slug' => 'bg-subtle', 'color' => '#f9fafb'),
    array('name' => __('Muted background', 'classic-starter'), 'slug' => 'bg-muted', 'color' => '#f3f4f6'),
  ));
  add_theme_support('disable-custom-colors');
  add_theme_support('editor-gradient-presets', array());
  add_theme_support('disable-custom-gradients');

  // エディターで選べる文字サイズを本文向けのタイプスケールに絞る（ラベル用・タイトル用の大きさは入れない）
  add_theme_support('editor-font-sizes', array(
    array('name' => __('Small', 'classic-starter'), 'slug' => 'xs', 'size' => '0.875rem'),
    array('name' => __('Normal', 'classic-starter'), 'slug' => 'base', 'size' => '1rem'),
    array('name' => __('Large', 'classic-starter'), 'slug' => 'lg', 'size' => '1.125rem'),
    array('name' => __('Extra large', 'classic-starter'), 'slug' => 'xl', 'size' => '1.25rem'),
    array('name' => __('Huge', 'classic-starter'), 'slug' => '2-xl', 'size' => '1.5rem'),
  ));
  add_theme_support('disable-custom-font-sizes');

  // ナビゲーションメニューの位置を登録（管理画面の 外観 > メニュー で割り当てる）
  register_nav_menus(array(
    'primary' => esc_html__('Primary Menu', 'classic-starter'),
    'footer'  => esc_html__('Footer Menu', 'classic-starter'),
  ));

  // ロゴ画像（外観 > カスタマイズ > サイト基本情報 で設定する）
  add_theme_support('custom-logo', array(
    'height'      => 60,
    'width'       => 240,
    'flex-height' => true,
    'flex-width'  => true,
  ));
});

// サイドバーのウィジェットエリアを登録する（sidebar.php で出力。ウィジェットが無ければ1カラムのまま）
add_action('widgets_init', function () {
  register_sidebar(array(
    'name'          => __('Sidebar', 'classic-starter'),
    'id'            => 'sidebar-1',
    'description'   => __('Shown to the right of the content on the front page, posts, pages and archives. On small screens it moves below the content.', 'classic-starter'),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ));

  // トップページ（front-page.php）の一番下。お問い合わせへの誘導（パターン「Call to action」）などを置く想定
  register_sidebar(array(
    'name'          => __('Front page bottom', 'classic-starter'),
    'id'            => 'front-page',
    'description'   => __('Shown at the bottom of the front page. Place the "Call to action" pattern here and edit its text and link.', 'classic-starter'),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>',
  ));
});

/* =========================
  テンプレート用の関数（ヘッダー・一覧・パンくずリスト）
========================= */

/**
 * ヘッダーのサイト名部分を出力する。
 *
 * ロゴ画像が設定されていればロゴを、なければサイト名のテキストリンクを出す。
 * 見出し要素（h1 / p）は呼び出し側が用意する。
 */
function classic_starter_branding() {
  if (has_custom_logo()) {
    the_custom_logo();

    return;
  }

  printf(
    '<a class="header-ttl-link" href="%s">%s</a>',
    esc_url(home_url('/')),
    esc_html(get_bloginfo('name'))
  );
}

/**
 * 記事一覧の番号付きのページ送りを出力する（index.php と front-page.php で使う）。
 *
 * 全体のページ数と現在地が分かるよう番号を出し、前後は「Read more」と同じ小さなリンク（大文字にしない）にする。
 * 現在地の前後1ページずつの番号も出す（mid_size 1。スマホでは折り返すことがある）。
 */
function classic_starter_posts_pagination() {
  add_filter('paginate_links_output', 'classic_starter_fill_pagination_gap');

  the_posts_pagination(
    array(
      'mid_size'  => 1,
      // 記事詳細の前後ナビは大文字のラベル（英字のまま）なので、訳を分けられるよう文脈を付ける
      'prev_text' => esc_html_x('Prev', 'pagination', 'classic-starter'),
      'next_text' => esc_html_x('Next', 'pagination', 'classic-starter'),
    )
  );

  remove_filter('paginate_links_output', 'classic_starter_fill_pagination_gap');
}

/**
 * ページ送りの省略（…）が1ページ分だけを隠している時は、省略の代わりにその番号を出す。
 *
 * WordPress は「1 … 3 4 5」のように1ページだけでも省略してしまうため。項目の数は変わらない。
 */
function classic_starter_fill_pagination_gap($output) {
  return preg_replace_callback(
    // 右隣の番号は先読みにして、続く省略の左隣としても使えるようにする
    '#(>(\d+)</(?:a|span)>\s*)<span class="page-numbers dots">[^<]*</span>(?=\s*<(?:a|span)[^>]*>(\d+)</(?:a|span)>)#',
    function ($matches) {
      $page = (int) $matches[2] + 1;

      if ((int) $matches[3] !== $page + 1) {
        return $matches[0];
      }

      return $matches[1] . '<a class="page-numbers" href="' . esc_url(get_pagenum_link($page)) . '">' . esc_html(number_format_i18n($page)) . '</a>';
    },
    $output
  );
}

/**
 * パンくずリストを出力する（header.php でヘッダーの下に出す）。
 *
 * トップページでは出さない。最後の項目（今いるページ）はリンクにせず aria-current を付ける。
 * 構造化データ（BreadcrumbList）は SEO プラグインの役割なので付けない。
 */
function classic_starter_breadcrumb() {
  if (is_front_page() && !is_paged()) {
    return;
  }

  // 項目は array(表示する文字, URL) で持つ。URL が空の項目はリンクにしない
  $items = array(
    array(__('Home', 'classic-starter'), home_url('/')),
  );

  // タームの親を上から順に足す（カテゴリー・階層のあるタクソノミー）
  $add_term_ancestors = function ($term) use (&$items) {
    foreach (array_reverse(get_ancestors($term->term_id, $term->taxonomy, 'taxonomy')) as $ancestor_id) {
      $ancestor = get_term($ancestor_id, $term->taxonomy);

      if ($ancestor && !is_wp_error($ancestor)) {
        $items[] = array($ancestor->name, get_term_link($ancestor));
      }
    }
  };

  // トップページ（「最新の投稿」）の2ページ目以降はどの分岐にも入らず、ホームの後にページ番号だけが付く
  if (is_404()) {
    $items[] = array(_x('Page not found', 'breadcrumb', 'classic-starter'), '');
  } elseif (is_home() && !is_front_page()) {
    $items[] = array(get_the_title((int) get_option('page_for_posts')), get_permalink((int) get_option('page_for_posts')));
  } elseif (is_singular() && get_queried_object() instanceof WP_Post) {
    $post = get_queried_object();

    if (is_singular('post')) {
      $categories = get_the_category($post->ID);

      if ($categories) {
        $add_term_ancestors($categories[0]);
        $items[] = array($categories[0]->name, get_term_link($categories[0]));
      }
    } elseif (is_page() || is_attachment()) {
      foreach (array_reverse(get_post_ancestors($post)) as $ancestor_id) {
        // 非公開・下書きの親はタイトルを漏らさないよう出さない
        if (!is_post_publicly_viewable($ancestor_id)) {
          continue;
        }

        $items[] = array(get_the_title($ancestor_id), get_permalink($ancestor_id));
      }
    } elseif (get_post_type_archive_link($post->post_type)) {
      $items[] = array(get_post_type_object($post->post_type)->labels->name, get_post_type_archive_link($post->post_type));
    }

    $items[] = array(get_the_title($post), '');
  } elseif (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();
    $add_term_ancestors($term);
    $items[] = array($term->name, get_term_link($term));
  } elseif (is_author()) {
    $items[] = array(get_the_author_meta('display_name', (int) get_query_var('author')), get_author_posts_url((int) get_query_var('author')));
  } elseif (is_date()) {
    // 年・月・日を別の項目にする。投稿が無い月でも出せるよう、日付は URL の年・月・日から組み立てる。
    // 書式は訳で言語ごとに変えられるようにする（日本語は「2026年」「9月」「22日」）
    $year      = (int) get_query_var('year');
    $month     = (int) get_query_var('monthnum');
    $day       = (int) get_query_var('day');
    $timestamp = mktime(0, 0, 0, $month ?: 1, $day ?: 1, $year);

    /* translators: Breadcrumb year format. See https://www.php.net/manual/datetime.format.php */
    $items[] = array(date_i18n(_x('Y', 'breadcrumb year format', 'classic-starter'), $timestamp), get_year_link($year));

    if ($month) {
      /* translators: Breadcrumb month format. See https://www.php.net/manual/datetime.format.php */
      $items[] = array(date_i18n(_x('F', 'breadcrumb month format', 'classic-starter'), $timestamp), get_month_link($year, $month));
    }

    if ($day) {
      /* translators: Breadcrumb day format. See https://www.php.net/manual/datetime.format.php */
      $items[] = array(date_i18n(_x('j', 'breadcrumb day format', 'classic-starter'), $timestamp), get_day_link($year, $month, $day));
    }
  } elseif (is_post_type_archive()) {
    $items[] = array(post_type_archive_title('', false), get_post_type_archive_link(get_query_var('post_type')));
  } elseif (is_search()) {
    // 検索語は利用者の入力なので、下の wp_strip_all_tags() でタグとして消えないよう先にエスケープする。
    // 空で検索した時は空の括弧を出さない
    $query   = get_search_query(false);
    $items[] = array(
      $query === ''
        ? _x('Search results', 'breadcrumb', 'classic-starter')
        /* translators: %s: search query */
        : sprintf(__('Search results for &ldquo;%s&rdquo;', 'classic-starter'), esc_html($query)),
      get_search_link(),
    );
  }

  // 2ページ目以降は、一覧をリンクにしてページ番号を足す
  if (is_paged()) {
    $items[] = array(
      /* translators: %s: page number */
      sprintf(__('Page %s', 'classic-starter'), number_format_i18n((int) get_query_var('paged'))),
      '',
    );
  }

  // 最後の項目は今いるページなので、リンクにしない
  $items[count($items) - 1][1] = '';
  ?>
  <nav class="breadcrumb" aria-label="<?php echo esc_attr__('Breadcrumb', 'classic-starter'); ?>">
    <ol class="inner breadcrumb-list">
      <?php foreach ($items as $item) : ?>
        <li class="breadcrumb-item">
          <?php if ($item[1] && !is_wp_error($item[1])) : ?>
            <a class="breadcrumb-link" href="<?php echo esc_url($item[1]); ?>"><?php echo esc_html(wp_strip_all_tags($item[0])); ?></a>
          <?php else : ?>
            <span aria-current="page"><?php echo esc_html(wp_strip_all_tags($item[0])); ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </nav>
  <?php
}

/**
 * アーカイブの見出しの上に出すラベル（アーカイブの種類）を返す。
 *
 * 小さな大文字のラベルとして出すので英語で書き、日本語でも英字のまま訳す。
 * コメントの投稿者バッジの「Author」（日本語に訳す）と訳を分けるため、文脈を付ける。
 */
function classic_starter_archive_label() {
  if (is_category()) {
    return _x('Category', 'archive label', 'classic-starter');
  }

  if (is_tag()) {
    return _x('Tag', 'archive label', 'classic-starter');
  }

  if (is_author()) {
    return _x('Author', 'archive label', 'classic-starter');
  }

  return _x('Archive', 'archive label', 'classic-starter');
}

// アーカイブの見出しから「カテゴリー:」などの前置きを外す（種類は見出しの上のラベルで示す）
add_filter('get_the_archive_title_prefix', '__return_empty_string');

// タイトルを空のまま公開した投稿・固定ページは、一覧やパンくずでリンクの文字が無くならないよう、
// 管理画面と同じく「(no title)」を出す（管理画面と REST API は元のまま）
add_filter('the_title', function ($title, $post_id = 0) {
  if ($title !== '' || is_admin() || (defined('REST_REQUEST') && REST_REQUEST)) {
    return $title;
  }

  if (!in_array(get_post_type($post_id), array('post', 'page'), true)) {
    return $title;
  }

  return __('(no title)', 'classic-starter');
}, 10, 2);

/* =========================
  日付（投稿・「最新の投稿」ブロック・コメントの書式をそろえる）
========================= */

/**
 * 投稿の日付の書式を返す（0埋め・「.」区切り）。
 *
 * 管理画面の日付形式ではなくテーマの書式で一覧・投稿詳細・最新の投稿ブロック・コメントの日付をそろえる。
 * 他の言語では訳で書式を差し替えられるようにする。
 */
function classic_starter_date_format() {
  /* translators: Post date format, 0-padded. See https://www.php.net/manual/datetime.format.php */
  return _x('Y.m.d', 'post date format', 'classic-starter');
}

/**
 * 書式を指定せずに取得した投稿の日付を、テーマの書式に差し替える。
 *
 * コアの「最新の投稿」ブロックは書式を指定せず（管理画面の日付形式で）日付を出すため、
 * そのブロックを描画する間だけ get_the_date に付ける。
 */
function classic_starter_filter_block_date($the_date, $format, $post) {
  if ($format !== '') {
    return $the_date;
  }

  return get_the_date(classic_starter_date_format(), $post);
}

// 「最新の投稿」ブロックの描画の前後で、日付の差し替えを付け外しする（他の日付には影響させない）。
// ほかのプラグインが pre_render_block で描画を差し替えると render_block_core/latest-posts が呼ばれず
// 解除が漏れるため、どのブロックでも先に外してから付け直す
add_filter('pre_render_block', function ($pre_render, $parsed_block) {
  remove_filter('get_the_date', 'classic_starter_filter_block_date', 10);

  if (($parsed_block['blockName'] ?? '') === 'core/latest-posts') {
    add_filter('get_the_date', 'classic_starter_filter_block_date', 10, 3);
  }

  return $pre_render;
}, 10, 2);

add_filter('render_block_core/latest-posts', function ($block_content) {
  remove_filter('get_the_date', 'classic_starter_filter_block_date', 10);

  return $block_content;
});

// コメントの日付も投稿と同じ書式にする（書式を指定せずに取得した時だけ差し替える。時刻はそのまま）
add_filter('get_comment_date', function ($comment_date, $format, $comment) {
  if ($format !== '') {
    return $comment_date;
  }

  return get_comment_date(classic_starter_date_format(), $comment);
}, 10, 3);

/* =========================
  抜粋
========================= */

// 自動抜粋の末尾を「[…]」から「…」にする（続きへは一覧の「Read more」リンクで誘導するため）
add_filter('excerpt_more', function () {
  return '&hellip;';
});

// 一覧の抜粋は CSS で行数を決めて切るので、広い画面でも行を埋められるよう自動抜粋を長めにする。
// 初期値は言語ごとに翻訳されている（日本語は文字数、英語は単語数）ので、それを2倍にする
add_filter('excerpt_length', function ($length) {
  return $length * 2;
});

/* =========================
  検索フォーム
========================= */

// ブロックの「検索」も、テーマの検索フォーム（searchform.php）で出してどこでも同じ見た目にする。
// ボタンの文字・案内文などブロックの設定は使わず、ラベルだけを上に出す
add_filter('render_block_core/search', function ($block_content, $block) {
  $attrs      = $block['attrs'] ?? array();
  $show_label = $attrs['showLabel'] ?? true;
  $label      = $attrs['label'] ?? __('Search', 'classic-starter');
  $output     = '<div class="search-block">';

  if ($show_label && $label !== '') {
    $output .= '<p class="search-block-label">' . wp_kses_post($label) . '</p>';
  }

  return $output . get_search_form(array('echo' => false)) . '</div>';
}, 10, 2);

/* =========================
  パスワード保護
========================= */

// パスワード保護のフォームの送信ボタンを、汎用の .button にする（入力欄の見た目は content.css）
add_filter('the_password_form', function ($output) {
  return str_replace('type="submit"', 'type="submit" class="button"', $output);
});

/* =========================
  コメント
========================= */

// コメントフォームからサイト（URL）欄を削除する（スパム対策・入力の手間削減）
add_filter('comment_form_default_fields', function ($fields) {
  unset($fields['url']);
  return $fields;
});

// コメント投稿者名のリンクを無効化（常に名前のみ表示。既存コメント・ログインユーザー・ピンバックにも適用）
add_filter('get_comment_author_url', '__return_empty_string');

// 記事投稿者のコメントにバッジを付ける
add_filter('get_comment_author_link', function ($link, $author, $comment_id) {
  $comment = get_comment($comment_id);
  $post = $comment ? get_post($comment->comment_post_ID) : null;

  if ($post && $comment->user_id && (int) $comment->user_id === (int) $post->post_author) {
    $link .= ' <span class="comment-author-badge">' . esc_html__('Author', 'classic-starter') . '</span>';
  }

  return $link;
}, 10, 3);

/* =========================
  ナビゲーションメニュー
========================= */

// メニューのリンクにクラスを付ける（詳細度を平坦に保つため、CSS を要素セレクタで書かない）
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
  if (isset($args->theme_location) && $args->theme_location === 'primary') {
    $atts['class'] = 'header-nav-link';
  }

  return $atts;
}, 10, 3);

// メニュー項目（li）にクラスを付ける（区切り線・子メニューの開閉の指定に使う）
add_filter('nav_menu_css_class', function ($classes, $item, $args) {
  if (isset($args->theme_location) && $args->theme_location === 'primary') {
    $classes[] = 'header-nav-item';
  }

  return $classes;
}, 10, 3);

// メニューの文字を span で包む（リンクは行全体に広がるので、下線を文字の幅に合わせるため）
add_filter('nav_menu_item_title', function ($title, $item, $args) {
  if (isset($args->theme_location) && $args->theme_location === 'primary') {
    $title = '<span class="header-nav-text">' . $title . '</span>';
  }

  return $title;
}, 10, 3);

// 子メニュー（ul）にクラスを付ける
add_filter('nav_menu_submenu_css_class', function ($classes, $args) {
  if (isset($args->theme_location) && $args->theme_location === 'primary') {
    $classes[] = 'header-nav-sublist';
  }

  return $classes;
}, 10, 2);

// ページ内のアンカー（#）へのカスタムリンクは、WordPress が URL の # 以降を無視して
// 「現在のページ」と判定してしまうため、現在地のクラスを外す（親に付いた祖先のクラスも外す）
add_filter('wp_nav_menu_objects', function ($items, $args) {
  if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
    return $items;
  }

  $by_id   = array();
  $parents = array();
  $removed = array();

  foreach ($items as $item) {
    $by_id[(int) $item->ID]   = $item;
    $parents[(int) $item->ID] = (int) $item->menu_item_parent;

    if ($item->type === 'custom' && str_contains($item->url, '#') && in_array('current-menu-item', (array) $item->classes, true)) {
      $item->current = false;
      $item->classes = array_values(array_diff($item->classes, array('current-menu-item', 'current_page_item')));
      $removed[]     = (int) $item->ID;
    }
  }

  // 現在地として残った項目の祖先は、祖先のクラスを残す
  $keep = array();

  foreach ($items as $item) {
    if (in_array('current-menu-item', (array) $item->classes, true)) {
      for ($id = $parents[(int) $item->ID]; $id; $id = $parents[$id] ?? 0) {
        $keep[$id] = true;
      }
    }
  }

  foreach ($removed as $removed_id) {
    for ($id = $parents[$removed_id]; $id && isset($by_id[$id]); $id = $parents[$id] ?? 0) {
      if (isset($keep[$id])) {
        continue;
      }

      $by_id[$id]->current_item_ancestor = false;
      $by_id[$id]->current_item_parent   = false;
      $by_id[$id]->classes               = array_values(array_diff(
        $by_id[$id]->classes,
        array('current-menu-ancestor', 'current-menu-parent', 'current_page_ancestor', 'current_page_parent')
      ));
    }
  }

  return $items;
}, 10, 2);

// 子を持つ最上位の項目に、md 未満のアコーディオン用の開閉ボタンを足す。
// hidden で出力して header.js が表示する（JS 無効時は子メニューを開いたまま見せる）
add_filter('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {
  if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
    return $item_output;
  }

  if ($depth !== 0 || !in_array('menu-item-has-children', (array) $item->classes, true)) {
    return $item_output;
  }

  $label = sprintf(
    /* translators: %s: menu item title */
    esc_html__('%s submenu', 'classic-starter'),
    esc_html(wp_strip_all_tags($item->title))
  );

  return $item_output . '<button class="header-nav-sub-toggle" type="button" hidden><span class="screen-reader-text">' . $label . '</span></button>';
}, 10, 4);

/* =========================
  ブロック（スタイル・パターン）
========================= */

// ブロックスタイルを登録する（エディターの「スタイル」パネルに出る）。
// エディターでもフロントと同じ見た目にするため、block-styles.css は tokens.css に依存させる。
// クラシックテーマでは style_handle の CSS が全ページに読み込まれるため、
// style_handle はエディター（管理画面）だけに付け、フロントは wp_enqueue_scripts で条件付きで読み込む
add_action('init', function () {
  $version = wp_get_theme()->get('Version');

  wp_register_style(
    'classic-starter-block-styles',
    get_template_directory_uri() . '/assets/css/block-styles.css',
    array('classic-starter-tokens'),
    $version
  );

  $button_style = array(
    'name'  => 'line',
    'label' => __('Line', 'classic-starter'),
  );

  if (is_admin()) {
    $button_style['style_handle'] = 'classic-starter-block-styles';
  }

  register_block_style('core/button', $button_style);
});

// ブロックパターンを登録する（エディターの挿入パネル「パターン」に出る）。
// ラベル＋見出し＋説明文＋ボタン（Line スタイル）の誘導ブロック。文言は案件ごとに書き換える前提。
// CSS は block-styles.css（ボタンを含むので、ボタンブロックの条件で一緒に読み込まれる）
add_action('init', function () {
  register_block_pattern('classic-starter/call-to-action', array(
    'title'      => __('Call to action', 'classic-starter'),
    'categories' => array('call-to-action'),
    'content'    => '<!-- wp:group {"className":"cta"} -->
<div class="wp-block-group cta"><!-- wp:paragraph {"className":"cta-label"} -->
<p class="cta-label">' . esc_html__('Contact', 'classic-starter') . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"cta-title"} -->
<h2 class="wp-block-heading cta-title">' . esc_html__('Get in touch', 'classic-starter') . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"cta-text"} -->
<p class="cta-text">' . esc_html__('Feel free to contact us with any questions. We will get back to you as soon as possible.', 'classic-starter') . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-line"} -->
<div class="wp-block-button is-style-line"><a class="wp-block-button__link wp-element-button">' . esc_html__('Contact us', 'classic-starter') . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
  ));
});

// ブロックパターン「最新記事のカード」。サイドバー（ウィジェット）に置く想定で、見出しはサイト名（上にラベル）。
// 最新の1件だけアイキャッチを大きく出し、どの記事もタイトルの下に日付を置く（common.css の .latest-card）
add_action('init', function () {
  $page_for_posts = (int) get_option('page_for_posts');
  $posts_url      = $page_for_posts ? get_permalink($page_for_posts) : home_url('/');

  register_block_pattern('classic-starter/latest-posts-card', array(
    'title'      => __('Latest posts card', 'classic-starter'),
    'categories' => array('posts'),
    'content'    => '<!-- wp:group {"className":"latest-card"} -->
<div class="wp-block-group latest-card"><!-- wp:paragraph {"className":"latest-card-label"} -->
<p class="latest-card-label">' . esc_html__('Latest', 'classic-starter') . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"latest-card-title"} -->
<h2 class="wp-block-heading latest-card-title">' . esc_html(get_bloginfo('name')) . '</h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"postsToShow":7,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageSizeSlug":"medium_large","addLinkToFeaturedImage":true} /-->

<!-- wp:paragraph {"className":"latest-card-more"} -->
<p class="latest-card-more"><a href="' . esc_url($posts_url) . '">' . esc_html__('View all posts', 'classic-starter') . '</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
  ));
});

/* =========================
  CSS・JS の読み込み
========================= */

// <html> に .js を付ける。JS が使えるかを描画の前に判定し、CSS が JS 前提の見せ方（検索のモーダル・
// 開閉メニュー・アイコンボタン）を最初から適用できるようにする（header.js は defer なので、それを待つと
// JS 無効時用の表示が一瞬見えてしまう）。<head> のできるだけ早い位置で出す
add_action('wp_head', function () {
  wp_print_inline_script_tag("document.documentElement.classList.add('js');");
}, 0);

// デザイントークン（:root の CSS 変数）。フロントの共通CSSとエディターのブロックスタイルの両方が依存するので、
// wp_enqueue_scripts より前の init で登録する
add_action('init', function () {
  wp_register_style(
    'classic-starter-tokens',
    get_template_directory_uri() . '/assets/css/tokens.css',
    array(),
    wp_get_theme()->get('Version')
  );
});

/**
 * ブロックウィジェットの中に指定のブロックがあるかを返す。
 *
 * ウィジェットの中身は投稿ではないので、has_block() に内容を文字列で渡して調べる。
 * 同期パターン（再利用ブロック）の中身は別の投稿にあるため、ここでは見つけられない。
 */
function classic_starter_widgets_have_block($block_name) {
  foreach ((array) get_option('widget_block', array()) as $widget) {
    if (is_array($widget) && !empty($widget['content']) && has_block($block_name, $widget['content'])) {
      return true;
    }
  }

  return false;
}

add_action('wp_enqueue_scripts', function () {
  $version = wp_get_theme()->get('Version');

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
    array('classic-starter-style', 'classic-starter-tokens'),
    $version
  );

  // トップページ専用CSS
  if (is_front_page()) {
    wp_enqueue_style(
      'classic-starter-front-page',
      get_template_directory_uri() . '/assets/css/front-page.css',
      array('classic-starter-common'),
      $version
    );
  }

  // 404ページ専用CSS
  if (is_404()) {
    wp_enqueue_style(
      'classic-starter-404',
      get_template_directory_uri() . '/assets/css/404.css',
      array('classic-starter-common'),
      $version
    );
  }

  // 本文CSS（投稿・固定ページ共通。エディターとも共有する）
  if (is_singular()) {
    wp_enqueue_style(
      'classic-starter-content',
      get_template_directory_uri() . '/assets/css/content.css',
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

  // サイドバーが出るページか（404 と固定ページのテンプレート「No Sidebar」には出ない）
  $shows_sidebar = is_active_sidebar('sidebar-1') && !is_404() && !is_page_template('page-templates/no-sidebar.php');

  // ブロックスタイル用CSS（投稿・固定ページの本文かサイドバーのウィジェットにボタンブロックがある場合と、
  // トップページの下のウィジェットエリアを使っている場合（パターン「Call to action」を置く想定）のみ）
  if (
    (is_singular() && has_block('core/button', get_queried_object_id()))
    || (is_front_page() && is_active_sidebar('front-page'))
    || ($shows_sidebar && classic_starter_widgets_have_block('core/button'))
  ) {
    wp_enqueue_style('classic-starter-block-styles');
  }

  // ヘッダーのパネル（検索・メニュー）の開閉。検索は常に出るので条件を付けない
  wp_enqueue_script(
    'classic-starter-header',
    get_template_directory_uri() . '/assets/js/header.js',
    array(),
    $version,
    array(
      'in_footer' => true,
      'strategy'  => 'defer',
    )
  );

  // ページの先頭へ戻るボタンの表示切り替え。ボタンは全ページのフッターにあるので条件を付けない
  wp_enqueue_script(
    'classic-starter-page-top',
    get_template_directory_uri() . '/assets/js/page-top.js',
    array(),
    $version,
    array(
      'in_footer' => true,
      'strategy'  => 'defer',
    )
  );

  // 「返信」リンクでフォームをその場に移動させる（WordPress 同梱スクリプト）
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
});
