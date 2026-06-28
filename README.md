# classic-starter

WordPress クラシックテーマの**個人用ボイラープレート（スターターテーマ）**です。
個人ブログをはじめ、さまざまなサイト制作で再利用する出発点として、必要な機能・テンプレートを追加しながら育てていくことを想定しています。

## 概要

`classic-starter` は、新しい WordPress クラシックテーマを作る際の**ベース（出発点）**として再利用するためのボイラープレートです。
**WordPress 6.0+ / PHP 8.0+** を対象に、テンプレート・CSS 設計・コーディング規約をあらかじめ整えており、用途ごとにコピーして拡張していく前提で構成しています。

## 特徴

- 再利用前提のスターター構成で、新規テーマをすぐ立ち上げられる
- CSS 設計トークン（余白 / カラー / タイプスケール）を `:root` に定義済み
- Stylelint によるプロパティ順・整形ルールを同梱
- 出力エスケープ（`esc_html()` / `esc_url()` / `wp_kses_post()`）を徹底
- ページ種別に応じた CSS の条件付き読み込み
- 設計ルールを `docs/` にドキュメント化し、用途をまたいで規約を共有

## 現在の構成

### テンプレート

- `style.css`（テーマヘッダーのみ。実スタイルは `assets/css/` に記述）
- `functions.php`（テーマサポート登録 / CSS の条件付き読み込み）
- `header.php`（共通ヘッダー）
- `footer.php`（共通フッター）
- `index.php`（メインループ / アーカイブ）
- `single.php`（投稿詳細）
- `404.php`（404 エラー）
- `searchform.php`（カスタム検索フォーム）

### CSS

- `assets/css/common.css`（全ページ共通。`--space-*` / `--color-*` / `--text-*` などのトークンを定義）
- `assets/css/single.css`（投稿詳細ページ専用）
- `assets/css/404.css`（404 ページ専用）

### 設定・その他

- `stylelint.config.mjs`（CSS のプロパティ順・整形ルール）
- `package.json` / `package-lock.json`（BrowserSync / Stylelint 用）
- `screenshot.png`（テーマ一覧用スクリーンショット）
- `CLAUDE.md`（テーマ開発ガイド）
- `.gitignore`

## 実装済みのテーマ機能

- テーマサポート：`title-tag` / `html5` / `post-thumbnails`
- `<link>` タグの自己終了スラッシュ除去（`style_loader_tag` フィルタ）
- ページ種別ごとの CSS 条件付き読み込み（`is_single()` / `is_404()` / `is_page()`）

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

### 3. 開発サーバー起動（任意）

BrowserSync を使う場合は、テーマディレクトリで以下を実行します。

```bash
npm install
npm run dev          # BrowserSync 起動（ライブリロード）
npm run lint:css     # CSS の Lint
npm run lint:css:fix # CSS の Lint 自動修正（順序・整形）
```

## 開発ドキュメント

- [CSS 余白（上下スペーシング）ルール](docs/css-spacing.md) — 上下方向の余白（下方向統一 / rem / 8px ベースのスペーシングスケール）
- [CSS カラー（カラートークン）ルール](docs/css-colors.md) — 役割ベースの `--color-*` トークン参照
- [CSS フォントサイズ（タイプスケール）ルール](docs/css-typography.md) — `--text-*` / `--text-fluid-*` による文字サイズ設計

## 注意点

- このテーマは**再利用前提のベーステーマ**です。用途ごとに必要なテンプレート・機能を追加して使用してください。
- 本番運用・配布前に、以下の観点を必ず確認してください。
  - **セキュリティ**：出力のエスケープ、フォーム処理（nonce / 権限チェック）、不要な情報の露出防止
  - **アクセシビリティ**：見出し構造、ランドマーク（header / main / footer）、キーボード操作、コントラスト
  - **SEO**：title / description、見出し構造、OGP/Twitter カード、構造化データ
  - **パフォーマンス**：CSS/JS の読み込み最適化、画像最適化、不要ファイルの削除
- BrowserSync / Node.js 関連は**開発補助用**であり、テーマ利用自体には必須ではありません。
- `WP_DEBUG` を有効化している場合、公開環境では無効化（または `WP_DEBUG_DISPLAY` を `false`）にしてください。

## 今後追加予定（例）

### テンプレートファイル

- `page.php`（固定ページ。専用 CSS は `assets/css/page.css` を想定）
- `archive.php`
- `search.php`
- `comments.php`
- `sidebar.php`

### テーマ機能

- メニュー登録・ウィジェットエリア登録
- ナビゲーションメニュー対応
- テーマカスタマイザー対応

### 設計・構成の改善

- テンプレートパーツ化（`template-parts`）

## ライセンス

GPL-2.0-or-later（WordPress テーマに準拠）

## Author

momiji
