---
name: theme-rename
description: classic-starter を案件に持ち出してテーマスラッグ（フォルダ名・テキストドメイン・ハンドル名・関数の接頭辞）を変更する手順。スラッグやテーマ名を変える時に読む。
---

## 案件で使う時のスラッグ変更手順

案件に持ち出してテーマのスラッグ（フォルダ名）を変える時は、**テキストドメイン・接頭辞をすべて新しいスラッグにそろえる**（テキストドメインとスラッグの一致は WordPress.org の必須要件。関数名の接頭辞は他のテーマ・プラグインとの衝突を避けるため）。以下、新しいスラッグを `new-theme`、PHP 用の接頭辞を `new_theme` とする。

### 置き換える箇所

| 置き換え前 | 置き換え後 | 主な箇所 |
|---|---|---|
| `classic-starter`（フォルダ名） | `new-theme` | `wp-content/themes/` のテーマのフォルダ |
| `'classic-starter'`（テキストドメイン） | `'new-theme'` | 全テンプレート・`functions.php` の翻訳関数、`load_theme_textdomain()` |
| `Text Domain: classic-starter` | `Text Domain: new-theme` | `style.css` |
| `classic-starter-*`（ハンドル名） | `new-theme-*` | `functions.php` の `wp_enqueue_style()` / `wp_register_style()` 等（`classic-starter-common` 等） |
| `classic-starter/*`（パターン名） | `new-theme/*` | `functions.php` の `register_block_pattern()`（`classic-starter/call-to-action`） |
| `classic_starter_`（関数の接頭辞） | `new_theme_` | `functions.php` の `classic_starter_branding()` / `classic_starter_date_format()` / `classic_starter_filter_block_date()` / `classic_starter_archive_label()` / `classic_starter_posts_pagination()` / `classic_starter_fill_pagination_gap()` / `classic_starter_breadcrumb()` / `classic_starter_widgets_have_block()` と、`header.php` / `index.php` / `single.php` / `front-page.php` / `template-parts/post-item.php` での呼び出し |
| `@package classic-starter` | `@package new-theme` | 各 PHP ファイル先頭のコメント |
| `classic-starter.pot` | `new-theme.pot` | `languages/` のファイル名（`ja.po` / `ja.mo` はロケール名なのでそのまま） |
| `"name": "classic-starter"` | `"name": "new-theme"` | `package.json` / `package-lock.json`（`zip` スクリプトの `--prefix` と出力ファイル名も） |
| `=== classic-starter ===` ほか | 新しいテーマ名 | `readme.txt`、`README.md`、この CLAUDE.md・`docs/` の記述 |

### 手順

1. テーマのフォルダ名を変える（`mv classic-starter new-theme`）。
2. テーマ内の文字列を置き換える。**ハイフン版とアンダースコア版の両方**を置き換える（`node_modules` と `.git` は除く）。

   ```bash
   grep -rIl --exclude-dir=node_modules --exclude-dir=.git 'classic[-_]starter' . \
     | xargs sed -i -e 's/classic-starter/new-theme/g' -e 's/classic_starter/new_theme/g'
   ```

3. `languages/classic-starter.pot` を `languages/new-theme.pot` にリネームする（`git mv`）。
4. `style.css` のヘッダーを案件用に書き換える（`Theme Name` / `Theme URI` / `Author` / `Author URI` / `Description` / `Version`）。著作権表示のテーマ名・年・名義も書き換える。`readme.txt` も合わせる（テーマの `CLAUDE.md` の「配布用ファイル」 のルールどおり `style.css` と一致させる）。
5. 親リポジトリの `docker-compose.yml` の `wpcli` サービスの `working_dir`（テーマのパス）を新しいフォルダ名に直す。
6. テーマの `CLAUDE.md` の「翻訳ファイルの更新手順」 で、`.pot` を新しいテキストドメイン（`--domain=new-theme`）で作り直し、`ja.po` をマージして `ja.mo` を作り直す。
7. 残りがないか確認する。何も出なければ完了。

   ```bash
   grep -rIn --exclude-dir=node_modules --exclude-dir=.git 'classic[-_]starter' .
   ```

8. 管理画面の「外観 → テーマ」で新しいテーマを有効化し直す（フォルダ名が変わると別のテーマとして扱われ、メニューの位置やカスタマイザーの設定は引き継がれないため、割り当て直す）。
