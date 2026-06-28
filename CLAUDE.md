# CLAUDE.md

このファイルは、**classic-starter** テーマ内で作業する際の Claude Code（claude.ai/code）向けガイドです。テーマ自身の git リポジトリ内に置かれており、他プロジェクトで再利用する際にこれらの規約がテーマと一緒に持ち運ばれます。

Docker / 開発環境のセットアップは、一つ上の階層の `wp-theme/CLAUDE.md` に記載しています。

## 概要

**classic-starter は個人用のボイラープレート（たたき台）テンプレート**で、新規 WordPress クラシックテーマの出発点として各案件で再利用します。対象は **WordPress 6.0+** および **PHP 8.0+**。

## コマンド

テーマディレクトリ内（または Docker の `node` コンテナ内）でローカル実行します:

```bash
npm install
npm run dev          # BrowserSync を起動（WordPress をプロキシ、ライブリロード）
npm run lint:css     # 全 CSS ファイルを lint
npm run lint:css:fix # CSS の lint エラー（順序・フォーマット）を自動修正
```

## テーマ構成

### CSS 構成

スタイルは**ページ種別ごとにファイルを分割**し、`functions.php` で各ページに必要なものだけを条件付き enqueue する。全ページ共通の量を `common.css` に抑え、ページ固有のスタイルは該当ページ表示時のみ読み込むことで、不要な CSS の配信を避け、責務の所在を明確にする。これは PageSpeed Insights / Lighthouse の「使用していない CSS の削減（Reduce unused CSS）」対策も兼ねており、各ページで未使用の CSS を配信しないことを目的とする。

- `style.css` — テーマヘッダーのみ（スタイルは書かない）。実際のスタイルは `assets/css/` に置く。
- `assets/css/common.css` — 全ページで読み込むグローバルスタイル。スペーシングスケールのトークン（`--space-*`）を `:root` に定義。
- `assets/css/single.css` — 投稿詳細ページ（`is_single()`）のみで読み込む。
- `assets/css/404.css` — 404 ページ（`is_404()`）のみで読み込む。
- `assets/css/page.css` — 固定ページ（`is_page()`）のみで読み込む。ファイルは未作成。
- `assets/css/comments.css` — 投稿詳細・固定ページ（`is_singular()`）でコメントが開いている、または存在する場合に読み込む。`comments.php` のスタイルを定義。

CSS ファイルは `functions.php` で `wp_enqueue_style()` により条件付きで enqueue し、`classic-starter-style` → `classic-starter-common` を依存チェーンとする。

### JS 構成

現状 JS ファイルは持たない（必要になった案件で追加する）。追加する際も CSS と同様、PageSpeed Insights / Lighthouse の指標に引っかからないことを前提に、以下を既定方針とする。

- **ページ単位の条件付き enqueue**（`functions.php`）で、各ページで未使用の JS を配信しない（「Reduce unused JavaScript」対策）。
- **`defer` 属性 + フッター読み込み（`$in_footer = true`）** でレンダリングブロックを避ける（「Eliminate render-blocking resources」対策）。同期実行が必要な最小限のものを除き、原則 `defer`。
- **jQuery は使用しない。** テーマ自前のフロント JS はバニラ JS（`querySelector` / `classList` / `fetch` 等）で書く。※管理画面やプラグインが読み込む jQuery は対象外。
- **軽量に保つ**。大型依存を安易に増やさず、メインスレッドの実行時間を抑える（「Minimize main-thread work」「Reduce JavaScript execution time」対策）。
- モダンブラウザ前提で書き、不要なポリフィルを避ける（「Avoid serving legacy JavaScript」対策）。

### PHP テンプレート

- `header.php` — `<html>`、`<head>`、`<body>`、および `<header>` + `<main>` の開始タグを出力。サイトタイトルはフロントページでは `<h1>`、それ以外では `<p>` でレンダリングする。
- `footer.php` — `</main>` を閉じ、`<footer>` を出力、`wp_footer()` を呼び、`</body></html>` を閉じる。
- `index.php` — メインループのテンプレート（アーカイブ・ホーム）。`post_class('post-item')` を使用。
- `single.php` — 投稿詳細テンプレート。`post_class('post-entry')` を使い、サムネイル・日付・カテゴリー・タグ・前後ナビを表示し、任意でコメントを表示。
- `404.php` — 404 エラーテンプレート。
- `searchform.php` — カスタム検索フォーム。
- `comments.php` — コメント一覧＋コメントフォーム。`single.php` / `page.php` から `comments_template()` 経由で読み込む。HTML5 の `comment-list` / `comment-form` マークアップを使用。

### `functions.php` の要点

- `title-tag`、`html5`、`post-thumbnails` のテーマサポートを登録。
- `style_loader_tag` フィルターで `<link>` タグの自己終了スラッシュを除去。
- ページごとに CSS を条件付きで enqueue。

## CSS 規約

### 余白（上下スペーシング）

- 上下方向の余白は **下方向（`margin-bottom`）に統一**、単位は **`rem`**、値は `:root` の **8px ベースのスケール変数 `--space-*`** から選ぶ。
- 詳細・適用ルールは [`docs/css-spacing.md`](docs/css-spacing.md) を参照（必読）。

### カラー（カラートークン）

- 色は `common.css` 先頭の `:root` に定義した **`--color-*` トークンを `var()` で参照**する。**生の HEX / `rgb()` をルール内に直書きしない**。
- 見た目の色ではなく **役割（用途）でトークンを選ぶ**（例: 同じ `#333` でも文字色は `--color-text`、暗背景は `--color-dark`）。
- 影・トランジション・コンテナ幅も同 `:root` の `--shadow` / `--transition` / `--container-max` を使う。
- 詳細・トークン一覧・適用ルールは [`docs/css-colors.md`](docs/css-colors.md) を参照（必読）。

### フォントサイズ（タイプスケール）

- 文字サイズは **`rem` 基準**で、`:root` の **`--text-*` / `--text-fluid-*` トークンを `var()` で参照**する。**px 直書きは禁止**。
- 親基準で相対変化させたい箇所のみ `em`（例: インラインコードの `0.9em`）を使う。
- 詳細・スケール一覧・適用ルールは [`docs/css-typography.md`](docs/css-typography.md) を参照（必読）。

### レスポンシブ（ブレークポイント）

- 設計は **モバイルファースト**。基本は狭い画面向けに書き、`@media screen and (width >= ...)`（min-width 相当）で広い画面の上書きを足す。
- ブレークポイントは標準セット **sm: 480px / md: 768px / lg: 1024px** の3段階に統一する。スケール外の値（`640px` など）を新規に使わない。
- ビルド工程がないため、メディアクエリの条件部では `var()` が使えない。値は**直書き**し、`common.css` 先頭 `:root` の参考コメントと本ドキュメントで一元管理する。
- 詳細・適用ルールは [`docs/css-responsive.md`](docs/css-responsive.md) を参照（必読）。

### プロパティ順（Stylelint）

CSS のプロパティは `stylelint.config.mjs` で定義された順序に従う必要がある。グループは以下の順:

1. レイアウト・表示モード (display, position, z-index, float…)
2. Flex / Grid
3. ボックスサイズ (width, height…)
4. 余白 (margin, padding)
5. スクロール・オーバーフロー
6. リスト・テーブル・置換要素
7. 文字 (font, text-*, color)
8. 背景・境界線・装飾 (background, border, box-shadow, clip-path…)
9. アニメーション・変化 (transform, transition, animation…)
10. 擬似要素・補助的なプロパティ (content)

未指定のプロパティは末尾にアルファベット順で並ぶ。順序違反は `npm run lint:css:fix` で自動修正する。

### エスケープ

- テンプレートの全出力は `esc_html()`、`esc_url()`、または `wp_kses_post()` を使う。この方針を一貫して維持する。

## PHP 規約

### インデント（`<?php` ブロック）

- HTML 中に差し込む `<?php` 〜 `?>` ブロックでは、**ブロック直下の文を開始タグ `<?php` と同じインデントに揃える**（一段下げない）。閉じ `?>` も同じ位置に置く。
- `if` / `while` などの制御構造の**中身**は、従来どおり一段深くインデントする。
- 既存テンプレート（`index.php`、`page.php`、`single.php`）がこの書き方で統一されている。

```php
          <?php
          wp_link_pages(   // ← <?php と同じインデント
            array(
              ...
            )
          );
          ?>
```

## Git運用

- classic-starter は独立した git リポジトリ。
- **コミット前に必ず `npm run lint:css` を実行**する。
- **git のコミット・push は必ずユーザー自身が行う（Claude Code はコミット・push 禁止）。**
