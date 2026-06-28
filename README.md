# classic-starter

WordPress クラシックテーマの**個人用ボイラープレート（スターターテーマ）**です。
個人ブログをはじめ、さまざまなサイト制作で再利用する出発点として、必要な機能・テンプレートを追加しながら育てていくことを想定しています。

## 概要

`classic-starter` は、新しい WordPress クラシックテーマを作る際の**ベース（出発点）**として再利用するためのボイラープレートです。
**WordPress 6.0+ / PHP 8.0+** を対象に、テンプレート・CSS 設計・コーディング規約をあらかじめ整えており、用途ごとにコピーして拡張していく前提で構成しています。

## 特徴

- 再利用前提のスターター構成で、新規テーマをすぐ立ち上げられる
- CSS 設計トークン（余白 / カラー / タイプスケール / ブレークポイント）を `:root` に定義済み
- Stylelint によるプロパティ順・整形ルールを同梱
- 出力エスケープ（`esc_html()` / `esc_url()` / `wp_kses_post()`）を徹底
- ページ種別に応じた CSS の条件付き読み込み
- 国際化対応済み（翻訳関数 + `languages/` の .pot / .po / .mo）
- 設計ルールを `docs/` にドキュメント化し、用途をまたいで規約を共有

## 現在の構成

### テンプレート

- `style.css`（テーマヘッダーと著作権表示のみ。実スタイルは `assets/css/` に記述）
- `functions.php`（テーマサポート登録 / CSS の条件付き読み込み / メニュー・ウィジェット・ブロックスタイル・パターンの登録 / 各種フィルタ）
- `header.php`（共通ヘッダー。サイト説明の帯・サイト名・ナビ・検索とメニューのボタン）
- `footer.php`（共通フッター。フッターメニュー・プライバシーポリシー・コピーライト・ページの先頭へ戻るボタン）
- `front-page.php`（トップページ。ページの本文・新着記事・ウィジェットエリア「Front page bottom」・サイドバー）
- `index.php`（メインループ。ブログのトップ・アーカイブ・検索結果）
- `single.php`（投稿詳細）
- `page.php`（固定ページ）
- `page-templates/no-sidebar.php`（固定ページのテンプレート「No Sidebar」。サイドバーを出さない）
- `sidebar.php`（サイドバー。ウィジェットがある時だけ出力）
- `comments.php`（コメント一覧 + コメントフォーム）
- `404.php`（404 エラー）
- `searchform.php`（カスタム検索フォーム）
- `template-parts/content-page.php`（固定ページ1件分。`page.php` と `page-templates/no-sidebar.php` で共用）
- `template-parts/post-item.php`（記事一覧の1件分。`index.php` と `front-page.php` で共用）

### CSS

- `assets/css/tokens.css`（`--space-*` / `--color-*` / `--text-*` などのトークンを定義。フロントとエディターの両方で読み込む）
- `assets/css/common.css`（全ページ共通。ヘッダー・フッター・記事一覧・ページ送り・サイドバー等）
- `assets/css/content.css`（本文。投稿・固定ページとエディターで共有）
- `assets/css/editor-style.css`（エディター専用の基本設定）
- `assets/css/front-page.css`（トップページ専用）
- `assets/css/single.css`（投稿詳細ページ専用）
- `assets/css/page.css`（固定ページ専用）
- `assets/css/comments.css`（コメント欄専用）
- `assets/css/404.css`（404 ページ専用）
- `assets/css/block-styles.css`（ブロックスタイル「Line」とパターン「Call to action」。ボタンブロックがある時と、トップページ下部のウィジェットエリアを使っている時だけ読み込む）

### JS

- `assets/js/header.js`（ヘッダーの検索・メニューパネルの開閉）
- `assets/js/page-top.js`（ページの先頭へ戻るボタンの表示切り替え）

### 設定・その他

- `stylelint.config.mjs`（CSS のプロパティ順・整形ルール）
- `package.json` / `package-lock.json`（BrowserSync / Stylelint 用）
- `languages/`（翻訳ファイル。`classic-starter.pot` / `ja.po` / `ja.mo`）
- `docs/`（CSS 設計ルールのドキュメント）
- `readme.txt`（WordPress.org 配布用の説明ファイル。英語）
- `screenshot.png`（テーマ一覧用スクリーンショット）
- `CLAUDE.md`（テーマ開発ガイド）
- `.claude/`（Claude Code の共有設定とスキル。`theme-architecture` / `theme-rename`）
- `.gitignore`
- `.gitattributes`（配布用 zip から除外するファイル）

## 実装済みのテーマ機能

- テーマサポート：`title-tag` / `automatic-feed-links` / `html5` / `post-thumbnails` / `responsive-embeds` / `align-wide` / `editor-styles` / `custom-logo` / `editor-color-palette` / `editor-font-sizes`
- 動きを減らす設定（`prefers-reduced-motion`）：トランジション・アニメーション・スムーススクロールを止める
- 本文の最大幅：`$content_width` を本文の列の最大幅（826px）に設定（埋め込み・挿入する画像の幅の上限）
- 国際化：テキストドメイン `classic-starter` を `load_theme_textdomain()` で読み込み。「Category」「Search Results」などの英字のラベルは日本語でも英字のまま訳し、「Read more」などのリンクは日本語に訳す
- ヘッダー
  - サイト説明（キャッチフレーズ）をヘッダーの上の帯に表示
  - ロゴ画像：`custom-logo` 対応。未設定時はサイト名のテキストを表示（外観 > カスタマイズ > サイト基本情報 で設定）
  - 検索：アイコンボタンから、画面全体を暗幕で覆う検索のモーダルを開く（PC・SP 共通。アイコンはインライン SVG）
- ナビゲーションメニュー：メニュー位置 `primary`（ヘッダー）と `footer`（フッター）を登録
  - PC：サイト名の下の行に中央寄せで並べ、現在のページとホバー時にヘッダーの下線の真上へ太めの線を引く。子メニューは親の項目の下にドロップダウンで表示（2階層まで。画面に収まらない時は中をスクロール）
  - SP：ハンバーガーから右にスライドインするパネルで開き、子メニューはアコーディオン。暗幕のタップ・閉じるボタン・Esc で閉じる
- ウィジェットエリア：サイドバー（`sidebar-1`。トップページ・投稿・固定ページ・アーカイブに表示。lg 以上で本文の右、lg 未満は本文の下）と、トップページの一番下（`front-page`）
- 記事一覧：左に画像（無い時は空の枠）、右に日付・カテゴリー・タイトル・抜粋（行数で切る）・「Read more」。480px 未満は縦並び。番号付きのページ送り（前後のリンクは「Prev」「Next」。日本語では「前へ」「次へ」）
- パンくずリスト：トップページ（1ページ目）以外のすべてのページで、ヘッダーの下に表示（構造化データは SEO プラグインに任せる）
- 見出し：検索結果・アーカイブは、小さな大文字のラベル（SEARCH RESULTS / CATEGORY 等）と大きな名前を縦に積む
- 日付：投稿・コメント・「最新の投稿」ブロックの日付を、管理画面の設定ではなくテーマの書式 `Y.m.d` にそろえる（翻訳で書式を差し替え可能）
- 抜粋：末尾を `[…]` から `…` にし、長さを言語ごとの初期値の2倍にする（一覧では CSS の行数で切る）
- ダークモード：OS の配色設定（`prefers-color-scheme`）に従い、`tokens.css` の `--color-*` の値だけを差し替えて切り替える（切り替えボタンは置かない）
- ブロックエディター
  - ブロックスタイル：ボタンブロックに「Line」（線の枠）を登録
  - ブロックパターン：お問い合わせへの誘導「Call to action」と、サイドバー向けの「Latest posts card」（最新記事のカード）を登録
  - `add_editor_style()` で、エディターの本文をフロントと同じ見た目にする
  - 色・文字サイズ：選べる範囲をデザイントークンに絞る（色5つ・文字サイズ5段階）。カラーピッカー・グラデーション・数値の直接入力は無効
- ページ種別ごとの CSS 条件付き読み込み（`is_front_page()` / `is_404()` / `is_single()` / `is_page()` / `is_singular()` / コメント欄の有無（`comments_open()` / `get_comments_number()`）/ 本文・サイドバーのウィジェットのボタンブロックの有無 / トップページ下部のウィジェットエリアの使用）
- コメント関連
  - アバター＋吹き出しのチャット風の一覧。返信は「Reply」のリンク（日本語では「返信」）、送信ボタンは汎用の `.button`
  - コメントフォームから URL 欄を削除（`comment_form_default_fields`）
  - コメント投稿者名のリンクを無効化（`get_comment_author_url`）
  - 記事投稿者のコメントにバッジを表示（`get_comment_author_link`）
  - 返信リンク用の `comment-reply` スクリプトを読み込み

## 開発環境（想定）

- WordPress（Docker）
- MariaDB
- phpMyAdmin
- Node.js（BrowserSync）
- WSL2（Ubuntu）
- Git / GitHub

## セットアップ（例）

### 1. テーマを配置

`wp-content/themes/` 配下に `classic-starter` を配置します。

### 2. テーマを有効化

WordPress 管理画面の **外観 > テーマ** から `classic-starter` を有効化します。

有効化したら、必要に応じて次を設定します。

- **外観 > メニュー**：「Primary Menu」（ヘッダー）と「Footer Menu」（フッター）にメニューを割り当てる
- **外観 > ウィジェット**：「サイドバー」に最新記事のカード（パターン「Latest posts card」）など、「トップページ下部」にお問い合わせへの誘導（パターン「Call to action」）を置き、文言とリンク先を書き換える
- **設定 > 一般**：キャッチフレーズ（ヘッダーの上の帯に表示される）

### 3. 開発サーバー起動（任意）

BrowserSync を使う場合は、テーマディレクトリで以下を実行します。

```bash
npm install
npm run dev          # BrowserSync 起動（ライブリロード）
npm run lint:css     # CSS の Lint
npm run lint:css:fix # CSS の Lint 自動修正（順序・整形）
```

## 配布用 zip の作り方

WordPress.org への提出や、管理画面の「テーマのアップロード」で使う zip を作ります。テーマディレクトリで実行します（ホストで実行。git が必要）。

```bash
npm run zip
```

- `dist/classic-starter.zip` ができます（`dist/` は git の管理外）。中は `classic-starter/` フォルダ1つです。
- **コミット済みの内容だけ**が入ります（`git archive` で作るため）。コミットしていない変更・未追跡のファイルがあると、zip を作らずに止まります。先にコミットしてください。
- 開発用のファイル（`CLAUDE.md` / `README.md` / `docs/` / `package.json` / `stylelint.config.mjs` / `.claude/` / `.gitignore` 等）は、`.gitattributes` の `export-ignore` で除外しています。`node_modules/` と `bs-config.js` は git で管理していないので、もともと入りません。
- 開発用のファイルを新しく追加した時は、`.gitattributes` にも足してください。
- 作った zip の中身は次のコマンドで確かめられます。

```bash
unzip -l dist/classic-starter.zip
```

`unzip` が入っていない環境では、Python で確かめられます。

```bash
python3 -m zipfile -l dist/classic-starter.zip
```

## 開発ドキュメント

- [CSS 余白（上下スペーシング）ルール](docs/css-spacing.md) — 上下方向の余白（下方向統一 / rem / 8px ベースのスペーシングスケール）
- [CSS カラー（カラートークン）ルール](docs/css-colors.md) — 役割ベースの `--color-*` トークン参照
- [CSS フォントサイズ（タイプスケール）ルール](docs/css-typography.md) — `--text-*` による文字サイズ設計
- [CSS レスポンシブ（ブレークポイント）ルール](docs/css-responsive.md) — モバイルファースト / sm・md・lg の3段階

## 注意点

- このテーマは**再利用前提のベーステーマ**です。用途ごとに必要なテンプレート・機能を追加して使用してください。
- 本番運用・配布前に、以下の観点を必ず確認してください。
  - **セキュリティ**：出力のエスケープ、フォーム処理（nonce / 権限チェック）、不要な情報の露出防止
  - **アクセシビリティ**：見出し構造、ランドマーク（header / main / footer）、キーボード操作、コントラスト
  - **SEO**：title / description、見出し構造、OGP/Twitter カード、構造化データ
  - **パフォーマンス**：CSS/JS の読み込み最適化、画像最適化、不要ファイルの削除
- BrowserSync / Node.js 関連は**開発補助用**であり、テーマ利用自体には必須ではありません。
- `WP_DEBUG` を有効化している場合、公開環境では無効化（または `WP_DEBUG_DISPLAY` を `false`）にしてください。
- メインビジュアルは案件ごとに作る前提のため、テーマには含めていません。`front-page.php` に追加してください。
- ブロックパターンの中身は、挿入した時点でウィジェットや本文に保存されます。テーマ側でパターンの文言を変えても、挿入済みのものには反映されません。

## 今後追加予定（例）

### テンプレートファイル

- `archive.php` / `search.php`（今は `index.php` が兼ねている。一覧の作りを分けたくなった時に追加）

### テーマ機能

- テーマカスタマイザー対応

## ライセンス

classic-starter WordPress Theme, (C) 2026 Momiji

GPL-2.0-or-later（WordPress テーマに準拠）。詳細は `style.css` 冒頭の著作権表示を参照してください。

## Author

Momiji
