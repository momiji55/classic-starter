---
name: theme-architecture
description: classic-starter のファイル別の設計と意図（CSS 構成・JS 構成・PHP テンプレート・functions.php の要点）。テーマの CSS / JS / PHP ファイルを編集・追加する前に読む。
---

# classic-starter のファイル別の設計

テーマの各ファイルが何を担当し、なぜその作りなのかをまとめる。
全体の規約（CSS 規約・PHP 規約・国際化・Git運用）はテーマの `CLAUDE.md` にある。

### CSS 構成

スタイルは**ページ種別ごとにファイルを分割**し、`functions.php` で各ページに必要なものだけを条件付き enqueue する。全ページ共通の量を `common.css` に抑え、ページ固有のスタイルは該当ページ表示時のみ読み込むことで、不要な CSS の配信を避け、責務の所在を明確にする。これは PageSpeed Insights / Lighthouse の「使用していない CSS の削減（Reduce unused CSS）」対策も兼ねており、各ページで未使用の CSS を配信しないことを目的とする。

- `style.css` — テーマヘッダーと著作権表示のみ（スタイルは書かない）。実際のスタイルは `assets/css/` に置く。著作権表示は Theme Check の必須項目なので消さない（案件で使う際はテーマ名・年・名義を書き換える）。
- `assets/css/tokens.css` — デザイントークン（`--space-*` / `--color-*` / `--text-*` / `--font-*` / `--icon-*` 等）を `:root` に定義。エディターのパレット・文字サイズの変数（`--wp--preset--*`）も `body` でトークンに差し替える。フロントの全ページと、ブロックエディター（`add_editor_style()` と `block-styles.css` の依存）の両方で読み込む。
- `assets/css/common.css` — 全ページで読み込むグローバルスタイル。本文＋サイドバーのレイアウト（`.layout` / `.layout-main`）とウィジェットのスタイルもここで定義する（サイドバーのウィジェットはすべて 1px の線で囲んだカードにし、カードどうしは `--gap-m` で空ける。見出しは字間を広げた小さな文字（`--text-xs`。英字は大文字）にし、リストのリンクは色を変えずに下線を左から伸ばす。階層のあるリストは子の項目を `--space-4` 字下げする。タグクラウド（`.tag-cloud-link`）は記事詳細のタグと同じ線の枠のラベルにし、WordPress がインラインで付ける文字の大きさは `!important` でそろえる。カレンダー（`.wp-calendar-table`）は本文の表と同じく薄い線で区切り、今日（WordPress が `id` しか付けないので `td[id="today"]`）を太字にする。ドロップダウン表示の `select` は下のフォームパーツの見た目のままカードの幅いっぱいに広げ、ブロックの「検索」は `functions.php` がテーマの検索フォームに差し替えるので、ラベル（`.search-block-label`）だけをウィジェットの見出しと同じ見た目にする。トップページの一番下のウィジェットエリア（`.front-widgets`）も、クラシックの見出しとリストのリンクはサイドバーと同じ見た目にする（カードの枠と、パターンの見出しには効かせない）。最新記事のカード `.latest-card` はサイドバー内では自前の枠を消し、ウィジェットの枠を使う）。サイドバーの有無は `:has(> .sidebar)` で判定し、lg 未満は本文の下、lg 以上は本文の右（幅は `--sidebar-width`）に並べる。サイドバーがある時は本文の列（`.layout-main`）をコンテナにし、幅広・全幅は本文の列の幅までに収める。また、行幅を絞った本文（`.post-entry` / `.page-entry` / `.comments` / `.front-content`）は中央寄せをやめて左端に寄せ、ヘッダー等と左端をそろえる。lg 以上で横に並ぶ時は、最大幅（`--container-reading`）も外して本文の列いっぱいまで広げる。本文（`.entry-content`）の幅広・全幅（`.alignwide` / `.alignfull`）もここで定義し、`.main` をコンテナ（`container-type: inline-size`）にして `cqi` 単位で広げる（`100vw` と違いスクロールバーを含まないため横スクロールが出ない）。フォームパーツ（入力欄・`textarea`・`select`）とボタンの共通の見た目もここで定義する（詳細は テーマの `CLAUDE.md` の「フォームパーツ」 を参照）。小さな英字のラベル（`.archive-label` など、リンクではない区分の表示）と小さなリンク（「Read more」「Reply」「Prev」「Next」など）の文字は、ファイル末尾の Label / Small link セクションにまとめて定義し、各ファイルには配置と下線だけを書く（使い分けは テーマの `CLAUDE.md` の「ラベルと小さなリンク」 を参照）。ウィジェットエリアに置いたブロック（引用・表・コード・区切り線）の最小限のスタイルもここに持つ（`content.css` は投稿・固定ページでしか読み込まないため）。
- `assets/css/content.css` — 本文（`the_content()` の中身）のスタイル。投稿・固定ページ（`is_singular()`）で読み込み、`add_editor_style()` でエディターとも共有する。セレクタは `:is(.entry-content, .wp-block-post-content)` から辿る（`.entry-content` は `single.php` / `page.php` の本文、`.wp-block-post-content` はエディターの本文の根元）。本文の装飾を変える時はこのファイルだけを直す。本文のリンクはリンク色（`--color-link`）のまま、色だけでなく下線でも見分けられるよう下線を残し、1px の細い線を文字から少し離す（`text-underline-offset`）。ホバー・フォーカスで下線を消す。引用は左の線と字下げだけで示し、出典（`cite`）は斜体をやめて `--text-xs` にする。引用のスタイル「プレーン」は線と字下げを出さず、プルクオートは中の引用の線をやめて上下の線と `--text-xl` の文字で強調する（スタイル「単色」は線を引かない）。ファイルブロックのダウンロードボタン（`.wp-block-file__button`）も WordPress 本体の丸いボタンをやめ、`.button` と同じ線の枠（文字は `--text-xs`）にする。本文の行間は 1.8（日本語の長文向け。サイト全体の基本は 1.6）。本文直下の見出しは h2（20px）が下線付き、h3（18px）・h4（16px）は大きさで段階を付け、見出しの前は直前の要素の `margin-bottom` を広げて空ける（h2 の前は `--gap-l`、h3・h4 の前は `--space-8`）。見出しが続く時（h2 → h3 等）は上の見出しの通常の下余白のままにして詰める。
- `assets/css/editor-style.css` — エディター専用（`add_editor_style()`）。フロントの `common.css` の基本設定（フォント・文字色等）と、本文の行幅・幅広の幅、タイトル欄（`.editor-post-title`）の大きさだけを合わせる。WordPress がセレクタを `.editor-styles-wrapper` 配下に変換する。
- `assets/css/single.css` — 投稿詳細ページ（`is_single()`）のみで読み込む。本文以外（日付・カテゴリー・タイトル・アイキャッチ・タグ・前後ナビ）を定義。
- `assets/css/front-page.css` — トップページ（`is_front_page()`）のみで読み込む。ページの本文（`.front-content`。文章が読みやすい行幅で中央に置く。サイドバーがある時は `common.css` が左端に寄せる）と、新着記事などのまとまり（`.front-section`）を定義。新着記事の画像は、サイドバーが無い時に本文が横幅いっぱいに広がるので、lg 以上でもほかのページ（サイドバーがある時）の一覧と同じ幅になるよう、`--sidebar-width` と余白から計算する（`.layout:not(:has(> .sidebar))` の時だけ。サイドバーがある時はほかのページと同じ列幅なので、`common.css` の初期値のままにする）。最新の記事の見出しは `common.css` の `.archive-label` / `.archive-name` を使い回す。
- `assets/css/404.css` — 404 ページ（`is_404()`）のみで読み込む。
- `assets/css/page.css` — 固定ページ（`is_page()`）のみで読み込む。`.page-entry-*` のスタイル（本文以外）を定義。
- `assets/css/comments.css` — 投稿詳細・固定ページ（`is_singular()`）でコメントが開いている、または存在する場合に読み込む。`comments.php` のスタイルを定義（入力欄・`textarea` の見た目は `common.css` のフォームパーツに任せ、幅だけ広げる）。コメント欄専用のローカルトークン（`--comment-avatar` / `--comment-lane`）はこのファイル先頭の `:root` で定義する。アバターの表示サイズを変える場合は `--comment-avatar` と `comments.php` の `avatar_size`（Retina 用に2倍）の両方を合わせる。返信は SP でも `--space-6` 字下げし（md 以上はアバター列の幅）、2階層目までにする。アバターが無い時（ピンバック・トラックバックと、「アバターを表示」をオフにした時）は `:not(:has(.avatar))` でアバター用の字下げを出さない。
- `assets/css/block-styles.css` — `register_block_style()` で登録したブロックスタイル（ボタンの `is-style-line` 等）と、ボタンブロックの初期の見た目。WordPress 本体（`classic-themes.css`）の丸い濃いグレーのボタンをやめ、`.button` と同じ大きさ（`--text-base`・行間 1.8）・字間・直角にする。初期のスタイル（塗りつぶし）は暗い背景（`--color-bg-inverse`）でホバー・フォーカスで透明に反転し、Line とコアのアウトラインは線の枠でホバー・フォーカスで暗い背景に反転する。クラシックテーマでは `style_handle` の CSS が全ページに読み込まれるため、`style_handle` はエディター（`is_admin()`）だけに付け、フロントは投稿・固定ページの本文にボタンブロックがある時（`has_block('core/button')`）だけ読み込む。`tokens.css` に依存させているため、エディターでもトークンが使え、フロントと同じ見た目になる。

CSS ファイルは `functions.php` で `wp_enqueue_style()` により条件付きで enqueue し、`classic-starter-style` → `classic-starter-tokens` → `classic-starter-common` → `classic-starter-content`（投稿・固定ページのみ）→ ページ別 CSS の順に読み込む（`common` が `style` と `tokens` の両方に依存）。`tokens` はエディターでも使うため、`init` で登録している。

### JS 構成

- `assets/js/header.js` — ヘッダーのパネル（検索・メニュー）の開閉。`.header-action` のボタンを総称で拾い、開閉状態は `aria-expanded` だけで表す（CSS も `:has()` でその属性を見る）。開いているパネルに応じて `body` に `is-search-open` / `is-nav-open`（見せ方は CSS 側で決める）と、どれかが開いている印の `is-header-open`（背面のスクロール停止）を付け、隠れる領域を `inert` にする（検索は `.skip-link` / `.header` / `.breadcrumb` / `.main` / `.footer`、メニューは `.skip-link` と、暗幕の下に隠れる `.header-branding` / `.header-search-toggle` と `.breadcrumb` / `.main` / `.footer`）。検索パネルには JS が動く時だけ `role="dialog"` と `aria-modal` を付ける。検索はフォームのすぐ上の右端の「× 閉じる」ボタン（`.header-search-close`。JS が動く時だけ表示）・フォームの外（暗幕）のクリック・Esc、メニューは閉じるボタン・背面の暗幕（`.header::before`）のクリック・Esc で閉じる。検索を開いた直後は入力欄にフォーカスを移す（CSS は開く時だけ `visibility` を即座に切り替え、フォーカスが効くようにしている）。あわせて、子メニューの開閉ボタン（`.header-nav-sub-toggle`）を表示して `aria-expanded` で開閉させ、子メニューに `id` を振って `aria-controls` を付ける（md 未満は今いるページが子の中にあれば最初から開き、md 以上はすべて閉じて始める。md 以上のタッチ端末では、ほかのドロップダウンと外のタップで閉じる）。md 以上のドロップダウンは Esc で閉じ（子メニューの中にフォーカスがあれば親のリンクへ戻す）、マウスやフォーカスが項目から離れるまで `.is-dismissed` で隠す。SP のメニューでリンクを押した時はパネルを閉じる（ページ内リンクでも移動先が見えるように）。md 以上の子メニューのドロップダウンは、開く時（`mouseenter` / `focusin`）に左端をそろえた位置で画面の右にはみ出すかを測り、はみ出す時だけ `.is-align-right` を付けて右端をそろえる。開いたままリサイズした時は、開いているドロップダウンだけを測り直す（`resize` を `requestAnimationFrame` で1回の描画につき1回にまとめる）。検索はメニューの有無に関わらず出るため、条件を付けずに enqueue する。
- `assets/js/page-top.js` — ページの先頭へ戻るボタン（`.page-top`）の表示切り替え。`IntersectionObserver` で `.header` を監視し、ヘッダーが画面から外れている間だけ `.is-visible` を付ける（scroll イベントは使わない）。ボタンは全ページのフッターにあるため、条件を付けずに enqueue する。

追加する際は CSS と同様、PageSpeed Insights / Lighthouse の指標に引っかからないことを前提に、以下を既定方針とする。

- **ページ単位の条件付き enqueue**（`functions.php`）で、各ページで未使用の JS を配信しない（「Reduce unused JavaScript」対策）。
- **`defer` 属性 + フッター読み込み（`$in_footer = true`）** でレンダリングブロックを避ける（「Eliminate render-blocking resources」対策）。同期実行が必要な最小限のものを除き、原則 `defer`。
- **jQuery は使用しない。** テーマ自前のフロント JS はバニラ JS（`querySelector` / `classList` / `fetch` 等）で書く。※管理画面やプラグインが読み込む jQuery は対象外。
- **軽量に保つ**。大型依存を安易に増やさず、メインスレッドの実行時間を抑える（「Minimize main-thread work」「Reduce JavaScript execution time」対策）。
- モダンブラウザ前提で書き、不要なポリフィルを避ける（「Avoid serving legacy JavaScript」対策）。

### PHP テンプレート

- `header.php` — `<html>`、`<head>`、`<body>`、および `<header>` + `<main>` の開始タグを出力。`<body>` の先頭に本文（`<main id="main">`）へのスキップリンク（`.skip-link`）を置く（`.screen-reader-text` で隠し、キーボードでフォーカスした時だけ左上に表示。パネルを開いている間は `header.js` が `inert` にする）。サイト説明（キャッチフレーズ）は、設定されている時だけヘッダーの上の全幅の帯（`.header-top`。背景はメニューの開閉ボタンと同じ `--color-bg-subtle`。線は引かない）に出す。帯は `<header>` の外（前）に置く。検索とメニューのアイコンボタン（`.header-action`）を持ち、ボタンの `data-header-panel`（`search` / `nav`）がパネルの種類を表す。**検索は PC・SP 共通で、ヘッダーごと画面全体を暗幕で覆うモーダル**（`.header-search`）。ヘッダーごと覆うため、パネルは `</header>` の外に置く。メニューは md 未満では画面の右からスライドインするパネル（背景は白の `--color-bg`。左に暗幕が見えるよう画面幅より狭くする）で開き、**md 以上は1行目にサイト名と検索ボタン、2行目にナビ**を置いてメニューの開閉ボタンは隠す。HTML の並びは「サイト名 → ボタン → ナビ」にし、SP でメニューを開いた後の Tab が開閉ボタンからメニューの項目へ進むようにする（md 以上は grid でナビを2行目に置くので見た目は変わらない）。2行目のナビは区切りの点や線を付けず、項目どうしの間隔をそろえて中央にまとめる（`justify-content: center`。項目が少ない案件でも間延びしないよう、横幅いっぱいには広げない）。メニューの項目数は案件ごとに変わるため、サイト名・ナビ・検索を1行に並べる構成は採らない（1行に収まらず折り返して崩れるため）。モーダル化・メニューを閉じておく・アイコンボタンを出す CSS は、`<html>` の `.js` クラス（`functions.php` が `<head>` の先頭で付ける）を条件にしているため、**JS 無効時は検索フォームがヘッダーの下に、メニューがヘッダー内にそのまま並ぶ**。`.js` は描画前に付くので、`defer` の `header.js` を待つ間に JS 無効時用の表示が一瞬見えることはない（`aria-expanded` を条件にすると、読み込み時に検索フォームやメニューが一瞬見えてしまう）。開閉の状態（`aria-expanded`）は `header.js` が付け外しする。サイトタイトルはトップページ（`is_front_page()`。「表示設定」がどちらの時も）では `<h1>`、それ以外では `<p>` でレンダリングし、中身は `classic_starter_branding()`（ロゴ画像が設定されていればロゴ、なければテキスト）が出力する。ナビゲーションはメニューが割り当てられている時だけ出力し、階層は2階層まで（`depth => 2`）。子メニューは md 以上で親の項目の真下に縦に並べるドロップダウンとして出す（白地を 1px の線で囲み、影は付けない。上端はヘッダーの下線に重ね、親から動かす途中で閉じないようにする。画面に収まらない時は中だけをスクロールさせ、左端をそろえると画面の右にはみ出す時だけ、`header.js` が `.is-align-right` を付けて右端をそろえる。ホバー（`@media (hover: hover)`）とキーボードのフォーカス（`:has(:focus-visible)`）の CSS で開閉し、フェードで表示。タッチ端末（`hover: none`）は親の項目の横に開閉ボタンを出して開閉する。Esc で閉じられる。ナビにマウスが乗っている間は、その項目のドロップダウンだけを出す）。md 以上の最上位の項目は、ヘッダーの下線の真上にリンクの幅（文字の幅）の 3px の線（構造の薄い線の色 `--color-border`）を引く。現在のページ（`current-menu-item`。子のページにいる時はその親の `current-menu-ancestor` も）は常に出し（支援技術には WordPress が付ける `aria-current="page"` で伝わる）、ほかの項目はホバー・フォーカスで左から伸ばす（文字の下の細い下線は出さない）。ホバーできる端末ではホバー時、キーボードではフォーカス時に、文字の下線が左から伸びる（md 未満のメニューと md 以上の子メニューのドロップダウン。md 以上の最上位の項目は上の太めの線が伸びる）。md 未満はアコーディオン（親のリンクと山形のボタンを分け、ボタンで子を開閉。今いるページが子の中にあれば最初から開く）。子を持つ項目の目印（1px の線の山形）は、開閉ボタン（`.header-nav-sub-toggle`）を出す md 未満のアコーディオンと md 以上のタッチ端末にだけ出し、子が開いている間は横軸で上下に裏返して上向きにする。**md 以上のホバーできる端末では出さない**（ホバーとフォーカスでドロップダウンが開くため、目印を置かずにミニマルな見た目を優先する）。md 未満のメニューは1項目1行で区切り線を引く。線はパネルの左右の余白（`--gutter`）の内側に収め、端まで伸ばさない。文字の頭は線の端から少し（`--space-2`）内側に入れる。開いている間は開閉ボタン（× 印）を画面の右上に固定してパネルの上に重ね、項目が多い時はパネルの中だけがスクロールする。
- パンくずリスト — `header.php` がヘッダーの下（`<main>` の手前。本文へのスキップリンクで飛ばせる位置）で `classic_starter_breadcrumb()` を呼ぶ。トップページ以外のすべてのページに出し、SEO プラグインの有無に関わらずテーマのものを出す。構造化データ（`BreadcrumbList`）は SEO プラグインの役割なので付けない。投稿は「ホーム / カテゴリー（親から順に）/ 記事」、固定ページは「ホーム / 親ページ / ページ」、日付は年・月・日の項目（書式は訳で変えられる）、2ページ目以降は最後にページ番号を足す。検索結果は「「検索語」の検索結果」（検索語は先にエスケープして、タグとして消えないようにする。空の時は「検索結果」）。今いるページはリンクにせず `aria-current="page"` を付ける。見た目は `common.css` の `.breadcrumb-*`（サイドバーが無い投稿・固定ページでは、中央に置いた本文と左端をそろえるため、リストの幅を本文の行幅（`--container-reading`）に絞る。ヘッダーの下線との間は `--gap-m`、本文の領域（`.main`）との間は `--gap-l`（パンくずの無いトップページなどで `.main` の上に空く量と同じ）にし、パンくずをヘッダー側のまとまりに寄せる。補足色の小さな文字、区切りは CSS で描く細い斜線で読み上げに含めない、リンクは下線を左から伸ばす）。
- `sidebar.php` — サイドバー（ウィジェットエリア `sidebar-1`）。`index.php` / `single.php` / `page.php` / `front-page.php` の `.layout` の中で `get_sidebar()` から読み込む。ウィジェットが無い時は何も出力せず、1カラムのままにする。
- `footer.php` — `</main>` を閉じ、`<footer>` を出力、`wp_footer()` を呼び、`</body></html>` を閉じる。フッターメニュー（メニュー位置 `footer`、1階層のみ）は割り当てられている時だけ出力する。その下にプライバシーポリシーへのリンク（`the_privacy_policy_link()`。ページが設定・公開されている時だけ出力される）とコピーライトを、この順に中央に縦に積む。フッターの中（`.inner` の後）に、ページの先頭へ戻るボタン（`.page-top`。`header.php` の `<body id="top">` への `href="#top"` のリンクで、JS 無効時も動く。リンク先の要素があるので、キーボードで押した後の Tab も先頭（スキップリンク）から始まる。画面の右下に固定した正方形で、文字色の 1px の線で囲み、ホバー・フォーカスで `.button` と同じく反転する。山形はインライン SVG。JS が動く時はヘッダーが画面から外れるまで隠す）を置く。フッターの中に置くのは、パネルを開いた時に `header.js` がフッターごと `inert` にする対象に含めるため。背景はヘッダーの上のサイト説明の帯と同じ `--color-bg-subtle` にし（ページの上下を同じ色の面で挟む）、白との差が小さいので上に 1px の線を引いて本文との境目を示す。フッターメニューはヘッダーのナビと同じく間隔をそろえて中央にまとめ、リンクは色を変えずに下線を左から伸ばす（押せる範囲は上下の padding で広げ、線は content-box に描いて文字の下に引く）。
- `front-page.php` — トップページ。「設定 → 表示設定」のホームページの表示がどちらの時も使われる。ほかのページと同じく `.layout` / `.layout-main` の中に置き、サイドバー（`sidebar-1`）はウィジェットがある時だけ本文の右に出す。メインビジュアルは案件ごとに作るので、テーマには持たない。上から、ページの本文（固定ページをトップにした時だけ。タイトルは出さず、本文が空なら枠ごと出さない）→ 最新の記事（「最新の投稿」の時はメインのループとページ送り、固定ページの時は「表示設定」の件数を取得し（ページ送りを出さないので `no_found_rows`）、投稿ページが設定されていれば「View all posts」のリンクを添える。記事が無い時、固定ページの時は節ごと出さず、「最新の投稿」の時は記事の一覧がページの本文なので見出しと案内文（`h3`）を出す）→ ウィジェットエリア `front-page`（「Front page bottom」。お問い合わせへの誘導のパターン「Call to action」を置き、文言とリンク先を管理画面で書き換える想定）の順に並べる。
- `template-parts/content-page.php` — 固定ページ1件分（本文とコメント）。`page.php` と `page-templates/no-sidebar.php` のループから `get_template_part()` で読み込む。
- `template-parts/post-item.php` — 記事一覧の1件分。`index.php` と `front-page.php` のループから `get_template_part()` で読み込む。
- `index.php` — メインループのテンプレート（アーカイブ・ホーム・検索結果）。記事1件分は `template-parts/post-item.php`（`post_class('post-item')`）、ページ送りは `classic_starter_posts_pagination()` を使う。固定ページ（検索結果に出る）は日付・カテゴリーの行を出さない。各記事は、左に画像（3:2 に切りそろえる。タイトルと同じリンクなので `tabindex="-1"` と `aria-hidden` で読み上げ・Tab 移動から外す）、右に日付・カテゴリー（日付との間は 1px の縦線。日付と同じ補足色で、ホバー・フォーカスで文字色になる）・タイトル・抜粋（`.post-item-excerpt`。補足色で一段小さく、長さは文字数ではなく `-webkit-line-clamp` の行数で切る。sm 以上 md 未満は画像の高さにそろえて2行、それ以外は3行）・「Read more」リンク（小さな英字のリンク。ラベルと違い大文字にも字間を広げもせず、ホバー・フォーカスで下線を左から伸ばす。リンクなので `ja.po` では日本語に訳す（`続きを読む`）。読み上げ用にタイトルを `.screen-reader-text` で添える）を並べる。sm 未満は画像を全幅にして上に積み（空の枠は出さない）、sm 以上は画像と文字を 1:2 に分ける（md 以上は間を広げる）。アイキャッチが無い記事も同じ大きさの空の枠（`.post-item-thumbnail-placeholder`）を出し、文字の左端をそろえる。ページ送りは番号付き（`the_posts_pagination()`）。番号と前後のリンク（「Read more」と同じ小さなリンク。原文は `Prev` / `Next` で、`ja.po` では日本語に訳す（`前へ` / `次へ`）。記事詳細の前後ナビの大文字ラベルと訳を分けるため、文脈 `pagination` を付けている）は枠で囲まず、現在のページは数字の下に 1px の線を引き、ホバー・フォーカスではタイトルと同じく線を左から伸ばす（線は背景を content-box に描いて文字の幅に合わせ、押せる範囲は padding で広げる）。現在地の前後1ページずつの番号も出す（`mid_size => 1`。スマホでは折り返すことがある。1ページ分だけの省略は番号に置き換える）。検索結果は 0 件の時も見出し・件数（`_n()`）・検索フォーム（今の検索語入り。404 と同じく `35rem` に絞る）を出し、検索語が空の時は名前（空の括弧）を出さずラベルと件数だけにする。0 件の時は検索用の案内文を出す（線の箱には入れない）。固定ページをトップにした時の投稿ページ（`is_home() && !is_front_page()`）は、サイト名が `h1` ではないので、アーカイブと同じ形でラベル「Blog」と投稿ページのタイトルを `h1` にする。アーカイブ（カテゴリー・タグ等）も、記事が 0 件の時に見出しを出し、案内文の見出しは `h2` にする（WordPress は記事の無いカテゴリー・タグを 404 にしないため、見出しが無いとどのアーカイブか分からない）。検索結果・アーカイブの見出し（`h1`）は、小さな大文字のラベル（`common.css` の Label セクション。検索結果は「Search Results」、アーカイブは種類に応じて `classic_starter_archive_label()` が返す「Category」「Tag」「Author」「Archive」）と大きな名前（`.archive-name`。検索語・アーカイブ名）を縦に積み、検索結果は件数は見出しの横に置いて検索語の行のベースラインにそろえる（`align-items: last baseline`）。検索語を囲む記号は言語で違うので `_x('&ldquo;%s&rdquo;', 'search query', ...)` で翻訳する（日本語は「」）。ブログのトップの1ページ目（`is_home() && !is_paged()`）では、先頭に固定表示した記事（`is_sticky()`、`post_class()` が `.sticky` を付ける）の日付の前にラベル（`.post-item-sticky-label`）を出す。ラベルの文字は翻訳できるよう CSS の `content` ではなく PHP で出力する。
- `single.php` — 投稿詳細テンプレート。`post_class('post-entry')` を使い、本文の div に `.entry-content` を付ける（`content.css` の対象）。本文を改ページで分けた時のページ番号（`wp_link_pages()`。固定ページも同じ）は、記事一覧のページ送りと同じ見た目にする（`common.css` の `.page-links`。ラベル「Pages:」は「Tags:」と同じ補足色の小さな文字）。記事の頭は一覧と同じく「日付｜カテゴリー」→ タイトル → アイキャッチの順（日付とカテゴリーの見た目も一覧にそろえる）。記事の終わりはタグだけを線の枠のラベルで並べ（カテゴリーは頭にあるので重ねない。タグが無ければ線ごと出さない）、その下に前後の記事を、上に 1px の線を引いて左右に振り分ける（間は `--gap-m`。sm 未満は列が細くなるので縦に積む。Next はまとまりを右端に寄せ、折り返したタイトルは行頭がそろうよう左寄せにし、ラベルだけ右寄せにする）。前後の記事は小さな大文字のラベル（`Prev` / `Next`。リンクはタイトル側なので、ここはラベル扱い）の下に記事のタイトルを出し、ホバー・フォーカスでタイトルの下線を左から伸ばす（前後どちらも無い時は線ごと隠す）。任意でコメントを表示。
- `page.php` — 固定ページテンプレート。ページ1件分（本文とコメント）は `template-parts/content-page.php` を使う。`post_class('page-entry')` を使い、本文の div に `.entry-content` を付ける。サムネイル・`wp_link_pages()` を表示し、任意でコメントを表示。
- `page-templates/no-sidebar.php` — 固定ページのテンプレート「No Sidebar」（日本語の管理画面では「サイドバーなし」）。お問い合わせ・LP など、サイドバーを出さずに見せたいページ用。`page.php` から `get_sidebar()` を除いただけで、中身は同じ `template-parts/content-page.php` を使う。本文の行幅は通常のまま（`--container-reading`）で、幅いっぱいに見せたい部分はブロックの幅広・全幅で広げる（サイドバーが無いので、全幅は画面の端まで広がる）。専用の CSS は持たない。
- `404.php` — 404 エラーテンプレート。見出し・説明文（1文にまとめる。行は検索フォームと同じ `35rem` に絞る）・検索フォーム・ホームへのボタン（汎用の `.button`。文字は「Back to Home」で、日本語はパンくずの「ホーム」にそろえて「ホームへ戻る」）を中央揃えで縦に並べる。見出しは数字の「404」を表示用の大きさ（`--text-4xl`・太字）で出し、その下に小さな大文字のラベル「Page Not Found」を置く（`h1` は1つのまま、読み上げでは続けて読まれる）。`.button` は `common.css` で定義し、カテゴリ・タグ・Line ボタンと同じ濃い 1px の枠と広い字間にしている。文字の大きさ（`--text-base`）・行間（1.8）・透明の背景も、本文に置いた Line スタイルのボタンに合わせ、コメントの送信ボタンにも使う。
- `searchform.php` — カスタム検索フォーム。404 ページ・検索結果・ヘッダーの検索パネルから `get_search_form()` で読み込み、ブロックの「検索」も `functions.php` がこのフォームに差し替える。虫眼鏡アイコンはインライン SVG。フォーカスはフォームの枠の色と、フォーム全体に重ねた輪郭線で示し（入力欄の `outline` は透明で残して強制カラーモードでも輪郭を出す）、虫眼鏡のボタンはホバー・フォーカスでほかの押せるものと同じく反転し、暗い面の上でも見えるよう輪郭線は白で内側に描く。案内文（placeholder）の色は補足色 `--color-text-muted`。
- `comments.php` — コメント一覧＋コメントフォーム。`single.php` / `page.php` から `comments_template()` 経由で読み込む。HTML5 の `comment-list` / `comment-form` マークアップを使用。コメント一覧はアバター＋背景を敷いた本文の箱（しっぽは付けず、アバターの位置で誰の発言かを示す）。返信のリンクは `wp_list_comments()` の `reply_text` で原文を `Reply` にし（`ja.po` では `返信` に訳す）、「Read more」と同じ小さな英字のリンクにし、ホバー・フォーカスで下線を左から伸ばす。フォームの見出しは `title_reply_before` / `title_reply_after` で `h2` にする（初期値の `h3` ではコメントが無い時に見出しの階層が飛ぶため）。承認待ちのメッセージ（`.comment-awaiting-moderation`）は斜体をやめ、名前・日時の下の行に補足色で出す。送信ボタンは `comment_form()` の `class_submit` で汎用の `.button` を付ける（md 未満は全幅、md 以上は文字の幅で中央に置く）。コメントのページ送り（`the_comments_navigation()`）は、記事一覧のページ送りの「Prev」「Next」と同じ小さな英字のリンク（`Older Comments` / `Newer Comments`。矢印なし。`ja.po` では `古いコメント` / `新しいコメント` に訳す）にし、ホバー・フォーカスで下線を左から伸ばす。

### `functions.php` の要点

ファイルは以下の見出しの順にグループ分けしている。処理を足す時は該当するグループに置く。

#### テーマの初期設定

- `$content_width`（埋め込み・本文に挿入する画像の幅の上限）を `826` に設定する（`after_setup_theme` の優先度 0）。値は本文が最も広くなる、lg 以上でサイドバーがある時の本文の列（`--container-max` − `--gutter` × 2 − `--sidebar-width` − `--gap-l`）。これらのトークン（`--gutter` は lg の値）を変えた時は計算し直す。
- `title-tag`、`automatic-feed-links`、`html5`、`post-thumbnails`、`responsive-embeds`、`align-wide`、`editor-styles`、`custom-logo` のテーマサポートを登録。`add_editor_style()` で `tokens.css` / `editor-style.css` / `content.css` をエディターに読み込む。
- `load_theme_textdomain()` で `languages/*.mo` を読み込む。
- `editor-color-palette` / `editor-font-sizes` で、エディターで選べる色（本文・補足・背景・薄い背景・やや濃い背景）と文字サイズ（`--text-xs` 〜 `--text-2xl` の5段階）をトークンに絞り、`disable-custom-colors` / `disable-custom-gradients` / `disable-custom-font-sizes` と空の `editor-gradient-presets` で自由指定を止める。登録する HEX・`size` は色見本用で、実際の値は `tokens.css` の `body` で `--wp--preset--*` をトークンに差し替えて決める（ダークモードでトークンを差し替えた時に追従させるため）。トークンの値を変えた時は `functions.php` の HEX・`size` もそろえる。詳細は `docs/css-colors.md` / `docs/css-typography.md`。
- `register_nav_menus()` でメニュー位置 `primary`（ヘッダー）と `footer`（フッター）を登録。
- `widgets_init` で `register_sidebar()` によりサイドバーのウィジェットエリア（`sidebar-1`）と、トップページの一番下のウィジェットエリア（`front-page`）を登録する。`front-page` を使っている時は、トップページでも `block-styles.css` を読み込む（パターン「Call to action」のボタン用）。

#### テンプレート用の関数

- `classic_starter_branding()` — ヘッダーのサイト名部分（ロゴ画像 or テキスト）を出力する。見出し要素は `header.php` 側が用意する。
- `classic_starter_posts_pagination()` — 記事一覧の番号付きのページ送りを出力する（`index.php` と `front-page.php` で共用）。出力する間だけ `paginate_links_output` に `classic_starter_fill_pagination_gap()` を付け、省略（…）が1ページ分だけを隠している時はその番号に置き換える（WordPress は「1 … 3 4 5」のように1ページだけでも省略するため）。
- `classic_starter_breadcrumb()` — パンくずリストを出力する（詳細は「PHP テンプレート」のパンくずリストの項）。
- `classic_starter_archive_label()` — アーカイブの見出しの上に出すラベル（アーカイブの種類）を返す。コメントの投稿者バッジの「Author」と訳を分けるため、文脈 `archive label` を付けて英字のまま訳す。あわせて `get_the_archive_title_prefix` で、見出しの名前から「カテゴリー:」などの前置きを外す（種類はラベルで示す）。
- `the_title` で、タイトルを空のまま公開した投稿・固定ページに「(no title)」（日本語は「（タイトルなし）」）を出す（一覧・パンくずのリンクの文字が無くならないように。管理画面と REST API は元のまま）。

#### 日付

- `classic_starter_date_format()` — 投稿の日付の書式（`Y.m.d`）を返す。`classic_starter_filter_block_date()` で、コアの「最新の投稿」ブロックの日付にも同じ書式を当てる（詳細は テーマの `CLAUDE.md` の「国際化（i18n）」）。
- コメントの日付を投稿と同じ `Y.m.d` にそろえる（`get_comment_date`。書式を指定せずに取得した時だけ差し替え、時刻はそのまま）。

#### 抜粋

- `excerpt_more` で、自動抜粋の末尾を `[…]` から `…` にする（続きへは一覧の「Read more」リンクで誘導する）。
- `excerpt_length` で、自動抜粋の長さを言語ごとの初期値の2倍にする（一覧の抜粋は CSS の行数で切るので、広い画面でも行を埋められるようにする）。

#### 検索フォーム

- `render_block_core/search` で、ブロックの「検索」の出力をテーマの検索フォーム（`searchform.php`）に差し替え、どこでも同じ見た目にする。ラベルだけを上に出し（`.search-block-label`）、ボタンの文字・案内文などブロックの設定は使わない。エディターの中の表示はコアのままになる。

#### パスワード保護

- `the_password_form` で、パスワード保護のフォームの送信ボタンに汎用の `.button` を付ける（入力欄の見た目は `common.css` のフォームパーツで、コメント欄の入力欄と同じ。幅だけ `content.css` の `.post-password-form` で広げる）。

#### コメント

- URL 欄の削除（`comment_form_default_fields`）、投稿者名リンクの無効化（`get_comment_author_url`）、記事投稿者バッジの付与（`get_comment_author_link`）。

#### ナビゲーションメニュー

- `nav_menu_link_attributes` / `nav_menu_css_class` / `nav_menu_submenu_css_class` で `.header-nav-link` / `.header-nav-item` / `.header-nav-sublist` を付与する（CSS を要素セレクタで書かず詳細度を平坦に保つため）。
- `nav_menu_item_title` で、メニューの文字を `<span class="header-nav-text">` で包む。SP ではリンクが行全体に広がるため、ホバー・フォーカス時の下線をこの span の幅（文字の幅）に引く。
- `wp_nav_menu_objects` で、ページ内アンカー（`#` 付き）のカスタムリンクから現在地のクラス（`current-menu-item` と、それが原因で親に付いた祖先のクラス）を外す。WordPress は `#` 以降を無視して判定するため、外さないとトップページで全項目が現在地扱いになる。
- `walker_nav_menu_start_el` で、子を持つ最上位のメニュー項目に子メニューの開閉ボタン（`.header-nav-sub-toggle`。md 未満のアコーディオンと、md 以上のタッチ端末のドロップダウンに使う）を `hidden` で足す。JS 無効時は子メニューが開いたまま見える。

#### ブロック（スタイル・パターン）

- `init` で `register_block_style()` によりボタンブロックに「Line」（線の枠）スタイルを登録する（Theme Check の推奨対応を兼ねる）。
- `init` で `register_block_pattern()` により、見出し＋説明文＋ボタン（Line スタイル）の誘導パターン `classic-starter/call-to-action` を登録する（Theme Check の推奨対応を兼ねる。文言は案件ごとに書き換える）。グループに `.cta` クラスを付け、CSS は `block-styles.css` に置く（ボタンを含むので、ボタンブロックの条件で一緒に読み込まれる）。
- `init` で `register_block_pattern()` により、最新記事のカード `classic-starter/latest-posts-card` を登録する（見出しはサイト名で、その上にお問い合わせのパターンと同じ小さなラベル `Latest`（日本語でも英字のまま訳す）を置く。コアの「最新の投稿」ブロックで最新の1件だけアイキャッチを大きく出し、どの記事もタイトルの下に日付を置く。記事一覧へのリンク付き。リンクの文言は「Read more」と同じ小さなリンクなので `View all posts`（`ja.po` では `記事一覧を見る` に訳す））。パターンの中身は挿入した時点でウィジェットに保存されるので、文言を変えても挿入済みのカードには反映されない（入れ直すか、ウィジェットの編集画面で直す）。主にサイドバーに置く想定で、CSS は `common.css` の `.latest-card`。

#### CSS・JS の読み込み

- `wp_head`（優先度 0）で `<html>` に `.js` クラスを付ける1行のインラインスクリプトを出力する（`wp_print_inline_script_tag()`）。CSS はこれで JS の有無を描画前に判定する。
- ページごとに CSS を条件付きで enqueue。ボタンブロックの CSS（`block-styles.css`）は、本文にボタンブロックがある時（`has_block()`）に加えて、**サイドバーのウィジェットにボタンブロックがある時**（`classic_starter_widgets_have_block()` が `widget_block` オプションの中身を調べる）にも読み込む。同期パターン（再利用ブロック）の中のボタンは中身が別の投稿にあるため検出できない。
- スレッド返信が有効なら `comment-reply` スクリプトを enqueue。
