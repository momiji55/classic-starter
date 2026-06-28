# CLAUDE.md

このファイルは、**classic-starter** テーマ内で作業する際の Claude Code（claude.ai/code）向けガイドです。テーマ自身の git リポジトリ内に置かれており、他プロジェクトで再利用する際にこれらの規約がテーマと一緒に持ち運ばれます。

Docker / 開発環境のセットアップは、一つ上の階層の `wp-theme/CLAUDE.md` に記載しています。

## 概要

**classic-starter は個人用のボイラープレート（たたき台）テンプレート**で、新規 WordPress クラシックテーマの出発点として各案件で再利用します。対象は **WordPress 6.0+** および **PHP 8.0+**。

**WordPress.org への配布を想定した作りを維持する。** 実際に配布するかは未定だが、いつでも出せる状態を保つ方針。この前提があるため、日本語案件専用なら不要に見えるルール（UI 文字列を英語で書く、テキストドメインをテーマスラッグと一致させる 等）が存在する。**日本語に戻す方向の「簡略化」はしない**（詳細は [国際化（i18n）](#国際化i18n) を参照）。

## コマンド

`package.json` の scripts を `npm run <script>` で実行する（テーマディレクトリ内、または Docker の `node` コンテナ内）。
`npm run zip` だけは **ホストで実行**する（`git archive` で HEAD を固めるため、未コミットの変更があると止まる）。

## テーマ構成

CSS 構成 / JS 構成 / PHP テンプレート / `functions.php` の要点など、**ファイル別の設計と意図はスキル `theme-architecture` にまとめてある**。
テーマの CSS・JS・PHP ファイルを編集・追加する前に読むこと。

### アイコン

- アイコンは**インライン SVG**で書く。アイコンフォントや画像は使わない（追加リクエストが増え、色の追従とアクセシビリティの制御がしにくいため）。
- `stroke` / `fill` は `currentColor` を指定し、色は CSS の `color` 側で決める。
- 装飾目的なので `aria-hidden="true"` と `focusable="false"` を付け、意味は可視ラベルか `aria-label` のテキストで伝える。
- 読み込み時のガタつき（CLS）を避けるため `width` / `height` 属性を必ず書き、表示サイズは CSS で上書きする。
- ハンバーガーと子メニューの山形（∨ / ∧）だけは例外で、変形（× 印・向きの反転）をアニメーションさせるため CSS の擬似要素で描く。
- `select` の矢印も例外で、置換要素には擬似要素を置けないため `background-image` の SVG（data URI）で描く。data URI の中では `var()` が使えないので、色を含んだアイコンごと `tokens.css` のトークン（`--icon-chevron-down`）にまとめ、色を変える時はそこだけ直す。

### 配布用ファイル

- `readme.txt` — WordPress.org の必須ファイル（Theme Check が REQUIRED で検出する）。英語で書く。
- `readme.txt` の `Tags` / `Requires at least` / `Tested up to` / `Requires PHP` は **`style.css` のヘッダーと一致させる**。`Stable tag` は `style.css` の `Version` と一致させる。バージョンを上げる時は両方＋`Changelog` を更新する。
- `Tested up to` は `style.css` にも必須（Theme Check が REQUIRED で検出する）。バージョン番号のみを書き、`WP 7.0` のような接頭辞やパッチバージョン（`7.0.4`）は書かない。
- `style.css` の `Theme URI` はテーマの紹介ページ、`Author URI` は作者のサイト（技術ブログ）。`Theme URI` は技術ブログ内の紹介記事（`https://webdesign-programming.com/classic-starter/`）。**配布前にこの URL で紹介記事が公開されていることを確認する**（リンク切れのままだと WordPress.org の審査で指摘される）。
- `readme.txt` の `Contributors` は WordPress.org のユーザー名を書く欄。実際に配布する際は登録済みのユーザー名に合わせる。
- 開発者向けの説明は `README.md`（日本語）、配布向けは `readme.txt`（英語）と役割を分ける。
- 配布用 zip は `npm run zip` で作る（`git archive` で HEAD を固めて `dist/classic-starter.zip` に出す。未コミットの変更があると止まる）。開発用のファイル（`CLAUDE.md` / `README.md` / `docs/` / `package*.json` / `stylelint.config.mjs` / `.claude/` / `.git*`）は `.gitattributes` の `export-ignore` で除外する。**開発用のファイル・フォルダを追加したら `.gitattributes` にも足す**（Theme Check は隠しファイル・隠しフォルダの同梱も指摘する）。

#### `Tags`（テーマのタグ）

- **機能を追加・削除したら `Tags` も更新する。** 実装していない機能のタグを残さない（WordPress.org の審査で、タグと実装の不一致は指摘される）。`style.css` と `readme.txt` の両方を同じ内容にそろえる。
- タグは **WordPress.org の公式タグ一覧（https://make.wordpress.org/themes/handbook/review/required/theme-tags/ ）にあるものだけ**を使う。独自のタグは書かない。
- 「Subject」（`blog` 等のサイトの用途）のタグは3つまで。「Layout」「Features」のタグには上限がない。
- 現在のタグと、その根拠になっている実装:

| タグ | 根拠の実装 |
|---|---|
| `blog` | 投稿の一覧・詳細を中心にした作り（Subject） |
| `two-columns` | 本文＋サイドバーの2カラム（`.layout` / `.layout-main` と `sidebar.php`。lg 以上で横並び） |
| `right-sidebar` | `register_sidebar()`（`sidebar-1`）と、本文の右に置くサイドバー |
| `custom-menu` | `register_nav_menus()` のメニュー位置（`primary` / `footer`） |
| `featured-images` | `add_theme_support('post-thumbnails')` と、`template-parts/post-item.php`（記事一覧）/ `single.php` / `page.php` のアイキャッチ表示 |
| `sticky-post` | `template-parts/post-item.php` の固定表示ラベル（`.post-item-sticky-label`）と `common.css` の `.sticky` のスタイル |
| `threaded-comments` | `comments.php` のスレッド表示と `comment-reply` スクリプト |
| `translation-ready` | 翻訳関数とテキストドメイン、`languages/` の翻訳ファイル |
| `wide-blocks` | `add_theme_support('align-wide')` と `.alignwide` / `.alignfull` の CSS |
| `editor-style` | `add_editor_style()` と `editor-style.css` / `content.css` |
| `block-styles` | `register_block_style()`（ボタンの Line スタイル） |
| `block-patterns` | `register_block_pattern()`（お問い合わせへの誘導パターン、最新記事のカード） |
| `full-width-template` | 固定ページのテンプレート `page-templates/no-sidebar.php`（「No Sidebar」。サイドバーを出さない） |

- 上の表の実装を削除・追加した時は、この表もあわせて更新する。

## CSS 規約

### 余白を活かしたミニマルなデザイン

- **1px の線は基本的に残す**（ヘッダー・フッター・記事一覧の区切り・ウィジェットのカード・本文の h2 の下線・押せるものの枠 等）。線を減らすのではなく、**線のまわりの余白を十分に取る**ことでミニマルに見せる。
- 余白は2層で考える。**部品の内側・部品の中の余白はスケール（`--space-*`）から直接選び**（画面幅で変わらない）、**部品どうし・まとまりの間だけ `--gap-m` / `--gap-l` を使う**（md 以上で一段広がる。`tokens.css`）。どちらを使うかは「画面幅で広がってほしいか」で決める。
- 詳細は [`docs/css-spacing.md`](docs/css-spacing.md) の「余白の段階（リズム）」を参照。

### 折り返し

- `body` に `word-break: break-all` を付け、長い URL や英数字がはみ出さないよう、英単語も行の端で途中から折り返す（英単語が途中で切れるのは許容する。日本語の折り返しと禁則処理は変わらない）。エディターも `editor-style.css` で同じにする。

### 字間（letter-spacing）

- **字間を広げるのは、小さなラベル的な文字だけ**にする（ボタン・カテゴリ・タグ・ナビ・「注目」等のラベル・パターンのラベル・投稿者バッジ）。リンクそのもの（「Read more」等）には付けない（[ラベルと小さなリンク](#ラベルと小さなリンク) を参照）。値は `--letter-spacing-wide`（`0.1em`）/ `--letter-spacing-wider`（`0.2em`）を参照し、直書きしない。ホバー・フォーカスで伸びる下線の太さも `--underline-thickness` を使う。見出し・タイトル・日付・フッター等の普通の文字には付けない（どこにでも使うと特別感がなくなり、ラベルが引き立たないため）。

### 余白（上下スペーシング）

- 上下方向の余白は **下方向（`margin-bottom`）に統一**、単位は **`rem`**、値は `:root` の **8px ベースのスケール変数 `--space-*`**（部品の中）か **`--gap-m` / `--gap-l`**（部品どうし・まとまりの間）から選ぶ。
- 詳細・適用ルールは [`docs/css-spacing.md`](docs/css-spacing.md) を参照（必読）。

### カラー（カラートークン）

- 色は `tokens.css` の `:root` に定義した **`--color-*` トークンを `var()` で参照**する。**生の HEX / `rgb()` をルール内に直書きしない**。
- 見た目の色ではなく **役割（用途）でトークンを選ぶ**（例: 同じ `#222` でも文字色は `--color-text`、暗背景は `--color-bg-inverse`）。
- **文字色は「本文（`--color-text`）」と「補足（`--color-text-muted`）」の2段階、線は「構造の薄い線（`--color-border`）」と「押せるものの濃い線（文字色）」の2種類に絞る。** 例外は入力欄（テキスト・`select`・検索フォーム）の枠で、周りと見分けられるよう（WCAG 1.4.11 の 3:1 以上）専用の `--color-border-input` にする（これ以上は薄くしない）。 中間のグレーを増やさず、見出しの強さは大きさと太さで付ける（メリハリを保つため）。
- トランジション・コンテナ幅・画面端の余白・フォームパーツの高さも同 `:root` の `--transition-duration` / `--container-max` / `--gutter` / `--control-height` を使う。`--gutter` は `.inner` の左右 padding で、画面幅に応じて Layout の `@media` で上書きしている。要素を画面幅いっぱいに広げる時は `margin-inline: calc(var(--gutter) * -1)` で `.inner` の余白を打ち消す。
- **ドロップシャドウは使わない。** 面の区切りは 1px のボーダーで作り、そのまわりの余白を十分に取る（ミニマルな見た目の維持と、影・角丸の多用による既製テンプレ感の回避。上の「余白を活かしたミニマルなデザイン」参照）。フォーカス中の入力欄も光彩は付けず、枠を文字色まで濃くしたうえで、2px の輪郭線を枠に重ねて出す。
- **角丸（`border-radius`）は使わない。** 角はすべて直角にし、ミニマルでシャープな見た目を保つ。例外は形として円を描くものだけで、アバター（`50%`）。
- **ダークモードは OS の配色設定（`prefers-color-scheme`）に従って切り替える。** `tokens.css` の `@media` で `--color-*` の値だけを差し替えるので、色はトークン経由だけで指定し（PHP・SVG・インライン `style` も含む）、色を薄く見せる目的で `opacity` 等を使わない（フェードの表示切り替えは対象外）。詳細は `docs/css-colors.md` の「ダークモード」を参照。
- 詳細・トークン一覧・適用ルールは [`docs/css-colors.md`](docs/css-colors.md) を参照（必読）。

### フォントサイズ（タイプスケール）

- 文字サイズは **`rem` 基準**で、`:root` の **`--text-*` トークンを `var()` で参照**する。**px 直書きは禁止**。画面幅に連動して伸縮させる可変サイズ（`clamp()` + `vw`）は使わず、画面幅で変える時はブレークポイントで一段ずつ切り替える。
- 親基準で相対変化させたい箇所のみ `em`（例: インラインコードの `0.9em`）を使う。
- 詳細・スケール一覧・適用ルールは [`docs/css-typography.md`](docs/css-typography.md) を参照（必読）。

### レスポンシブ（ブレークポイント）

- 設計は **モバイルファースト**。基本は狭い画面向けに書き、`@media screen and (width >= ...)`（min-width 相当）で広い画面の上書きを足す。
- ブレークポイントは標準セット **sm: 480px / md: 768px / lg: 1024px** の3段階に統一する。スケール外の値（`640px` など）を新規に使わない。
- ビルド工程がないため、メディアクエリの条件部では `var()` が使えない。値は**直書き**し、`tokens.css` の `:root` の参考コメントと本ドキュメントで一元管理する。
- 詳細・適用ルールは [`docs/css-responsive.md`](docs/css-responsive.md) を参照（必読）。

### ラベルと小さなリンク

- **大文字＋字間（`--letter-spacing-wide`）にするのは、ページの種類・区分を示す「ラベル」だけ**にする。対象は検索結果・アーカイブの見出しの上（`.archive-label`）、記事詳細の前後ナビの `Prev` / `Next`（`.post-entry-nav-label`。リンクは隣のタイトル側）、404 の「Page Not Found」（`.error-404-label`）。定義は `common.css` 末尾の Label セクション。
- **リンクそのもの（「Read more」「Reply」「View all posts」、記事一覧とコメントのページ送り）は大文字にしない。** 小さな文字（`--text-2xs`）のまま、ホバー・フォーカスで伸びる下線で示す（Small link セクション）。ラベルとリンクを見た目で分け、1画面に大文字の英字が並びすぎないようにするため。
- ウィジェットの見出しは日本語のことが多く大文字指定は効かないが、英語のタイトルの時にラベルとそろうよう `text-transform` を残している。
- 新しくラベルを足す時は、**それ自体がリンクかどうか**で判断する。リンクなら大文字にしない。
- **訳もこの区別に合わせる。** ラベル（`Blog` / `Category` / `Page Not Found` / 前後ナビの `Prev` `Next` など）は `ja.po` でも英字のままにし、リンク（`Read more` → `続きを読む`、`Reply` → `返信`、ページ送りの `Prev` `Next` → `前へ` `次へ`、`Older Comments` → `古いコメント`、`View all posts` → `記事一覧を見る`）は日本語に訳す。同じ原文をラベルとリンクの両方で使う時は、文脈（`_x()`）を付けて訳を分ける。

### フォームパーツ

- **入力欄（テキスト系 `input` / `textarea` / `select`）の見た目は `common.css` の「Form parts」で一括して定義する。** `:where()` で詳細度 0 にしているので、テーマのテンプレートだけでなくブロック・プラグインのフォームにも同じ見た目が当たり、部品ごとの指定で上書きもできる。ページ別の CSS（`comments.css` / `content.css`）には幅・高さなどの差分だけを書き、枠・余白・文字の指定を重複させない。
- チェックボックス・ラジオ・ファイル選択・範囲・色は対象外（ブラウザ標準のまま）。
- **ボタンの見た目（`.button`）は、フォームの送信ボタンにも当てる。** 対象は `form button:not([type])` / `button[type="submit"]` / `input[type="submit"]` / `input[type="reset"]` で、プラグインのお問い合わせフォームなどもテーマのボタンと同じ見た目になる。テーマのアイコンだけのボタン（ヘッダーの検索・メニューの開閉は `type="button"`、検索の虫眼鏡は `.search-form-submit` で除外）は対象外。
- **1行の部品（テキスト系 `input` / `select`）の高さは `--control-height`（`2.75rem` = 44px。タップ目標の推奨サイズ）でそろえる。** `select` は行の高さの扱いがブラウザごとに違い、余白と行の高さだけでは入力欄と高さがずれるため、高さそのものを決める。`textarea` だけ `height: auto` に戻し、中身と `min-height` に任せる。検索フォームの虫眼鏡のボタンは高さを持たせず、`align-items: stretch` で入力欄に合わせる（ヘッダーのモーダルの中だけ入力欄が `min-height` で高くなるため、そこにも自動で追従する）。
- 枠は `--color-border-input`、フォーカスすると枠を文字色（`--color-text`）まで濃くし、`:focus-visible` の 2px の輪郭線は `outline-offset: -1px` で枠に重ねる（枠との隙間も二重線も作らない）。
- 検索フォームは入力欄と虫眼鏡のボタンで1つの箱に見せているので、輪郭線は入力欄ではなくフォーム全体（`.search-form:has(.search-form-input:focus-visible)`）に出す。ヘッダーのモーダルの中だけは枠を持たないので、従来どおりフォームの下の線で示す。
- `select` の標準の矢印は OS ごとに形も位置も違うため `appearance: none` で消し、`--icon-chevron-down` を背景に置く（[アイコン](#アイコン) を参照）。

### ホバー・フォーカス

- **ホバー・フォーカスの見た目の変化は2種類だけにする。** ① リンクは**下線を左から伸ばす**（`linear-gradient` の `background-size` を `0` → 幅いっぱいに変える）、② 押せるもの（ボタン・カテゴリ・タグ・検索の虫眼鏡）は**反転する**（文字・背景・枠を入れ替える）。表現を増やすとミニマルな印象が薄れるので、新しい動き（アニメーションでの引き直し、背景だけ薄く変える等）は足さない。小さなリンク（「Read more」「Reply」「View all posts」）の下線も、ほかのリンクと同じく文字の幅いっぱいに伸ばす。
- **装飾のホバー（色・背景・下線など）は `@media (hover: hover)` の中に書く。** スマホ・タブレットではタップ後に `:hover` が残って貼り付くため、マウスなどホバーできる端末だけに出す。画面幅ではなく入力方法で判定する。
- **フォーカスの印は文字色（`--color-text`）の 2px の線にそろえる**（ブラウザ標準の色や、部品ごとの別の色にしない）。`common.css` の `:where(a, button, input, select, textarea, summary):focus-visible` で詳細度 0 で指定し、部品ごとには離し方だけを上書きする。md 以上のドロップダウンの項目（スクロールする箱なので、はみ出すと切れる）や、パネルの端で切れる場所は `outline-offset: -2px` で要素の内側に描く。md 以上の最上位の項目は、真下に開くドロップダウンに印の下の辺が隠れるので、フォーカスした時だけ `z-index: 2` で前面に出す（内側に描くと下端の太めの線に塗りつぶされる）。暗い背景の上（検索のモーダルの閉じるボタン）だけは白（`--color-text-inverse`）にし、すぐ下の白いフォームにかからないよう内側に描く。入力欄は枠を文字色まで濃くしたうえで、輪郭線を `outline-offset: -1px` で枠に重ねて出す（枠の色の変化だけでは気づきにくいため。詳細は [フォームパーツ](#フォームパーツ) を参照）。
- キーボード操作向けの表示は **`:focus` ではなく `:focus-visible`** に付ける（タップでフォーカスが残った時に出ないようにする）。`:hover, :focus` のようにまとめず、`:focus-visible` は外、`:hover` は `@media (hover: hover)` の中に分けて書く。
- 開閉の仕組みそのものに使う `:hover`（PC の子メニューのドロップダウンを開く `.header-nav-item:hover`）も `@media (hover: hover)` の中に書く。タッチ端末ではタップ後のホバーが残って閉じられなくなるため、md 以上でも子メニューの開閉ボタンで開く。例外は JS 無効時のタッチ端末だけで、開閉ボタンが使えないので `html:not(.js)` を条件にホバーで開く。

### 動き（トランジション・アニメーション）

- 時間は必ず **`--transition-duration`**（または `calc(var(--transition-duration) * 2)` のようにその倍数）で指定する。秒数を直書きしない。
- OS で動きを減らす設定（`prefers-reduced-motion: reduce`）の時は、`tokens.css` がこの変数を `0s` にしてすべての動きを止める。直書きするとこの対象から漏れる。
- 時間が `0s` でも動くよう、JS はトランジション・アニメーションの終了イベント（`transitionend` 等）に頼らない。
- ページ内リンクのスムーススクロール（`scroll-behavior: smooth`）も、`common.css` で動きを減らす設定の時は付けない。

### セレクタ

- **ID セレクタを使わない。** 詳細度が `(1,0,0)` と突出し、案件側で上書きするのに別の ID か `!important` が必要になるため。`stylelint.config.mjs` の `selector-max-id: 0` で機械的に禁止している。
- WordPress が ID しか出力しない要素（`#cancel-comment-reply-link` 等）は、親のクラスから辿って指定する（例: `.comment-reply-title small a`）。

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

### コメント

- **シンプルかつ最小限に書く。** 1ルールにつき1行を目安とし、セクション見出しと、コードから読み取れない意図（なぜその値・その手法なのか）だけを書く。設計判断に至った経緯や、セレクタを読めば分かる内容は書かない。
- **具体的なサイズ数値（px 等）を書かない。** 値を調整するたびにコメントも直すことになり二重管理になるため、「アバター幅 + 余白」のように役割で書く。
  ※ `tokens.css` の `:root` トークン定義に付く px 換算コメント（`/* 4px */` 等）はスケールの定義そのものなので対象外。
- **CSS・JS のコメントに `.md`（`CLAUDE.md` / `docs/` 等）への参照を書かない。** 配布用 zip に含まれないため、配布物の中ではリンク切れになる。参照先は配布物に含まれるファイル（`tokens.css` / `functions.php` 等）にする。
- **配布物からコメントを削除しない。** WordPress 公式テーマもコメント入り CSS を配信しており、秘匿情報もなく gzip 配信でサイズ影響も誤差の範囲。本番で消したい案件は、ソースを削らず配信時の minify（Autoptimize 等のプラグイン）で対応する。

### エスケープ

- テンプレートの全出力は `esc_html()`、`esc_url()`、または `wp_kses_post()` を使う。この方針を一貫して維持する。
- 例外として、見出しのタイトルは `the_title()` でそのまま出す（タイトルに入れた `<em>` 等のタグを生かすため。管理者・編集者以外のタイトルは保存時に危険なタグが除かれる）。属性の中では `the_title_attribute()` を使い、タグが要らない所（読み上げ用の `.screen-reader-text` 等）は `esc_html(get_the_title())` のままにする。

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

## 国際化（i18n）

WordPress.org への配布を想定し、テーマは国際化対応を維持する。テキストドメインは **`classic-starter`**（テーマスラッグと一致させることが公式の必須要件）。

### 文字列を書くときのルール

- **画面に出る文字列は必ず英語で書き、翻訳関数で囲む。** 日本語をソースに直接書かない（gettext は原文が英語である前提で翻訳フローが回るため）。日本語は `languages/ja.po` に訳として置く。
- 出力先に応じて `esc_html__()` / `esc_attr__()` を使い分ける。エスケープ規約と両立させる。
- 投稿の日付（`index.php` / `single.php` と、最新記事のカードなどコアの「最新の投稿」ブロック）は管理画面の日付形式ではなく、テーマの書式 `Y.m.d`（0埋め・`.` 区切り）で出す。書式は `functions.php` の `classic_starter_date_format()` にまとめ、`_x('Y.m.d', 'post date format', 'classic-starter')` で翻訳できるようにして、他の言語では訳で差し替える。「最新の投稿」ブロックは書式を指定せずに日付を出すため、`pre_render_block` / `render_block_core/latest-posts` でそのブロックを描画する間だけ `get_the_date` のフィルターを付け外しして差し替える。コメントの日付も `get_comment_date` のフィルターで同じ書式にそろえる（書式を指定せずに取得した時だけ差し替える。時刻は管理画面の時刻形式のまま）。
- 英字のラベル（`Category` / `Page Not Found` など）は、原文（msgid）を普通の書き方で書き、大文字にするのは CSS の `text-transform: uppercase` で行う（原文を大文字で書くと、翻訳や読み上げで不自然になるため）。「Read more」などのリンクは大文字にしない（[ラベルと小さなリンク](#ラベルと小さなリンク) を参照）。
- 単数・複数がある文字列は `_n()` を使う。件数による分岐を手書きしない。
- プレースホルダー（`%s` 等）を含む文字列には、直前に `/* translators: %s: ... */` コメントを英語で付ける。
- `style.css` の `Description` も翻訳対象（msgid）になるため英語で書く。

### 翻訳ファイルの更新手順

`languages/` の3ファイル（`classic-starter.pot` / `ja.po` / `ja.mo`）は WP-CLI で生成する。文字列を追加・変更したら以下を順に実行する。

```bash
# 1. .pot（翻訳テンプレート）を再生成
docker compose run --rm wpcli wp i18n make-pot . languages/classic-starter.pot --domain=classic-starter --exclude=node_modules

# 2. 既存の訳を保持したまま ja.po に新規分をマージ
docker compose run --rm wpcli wp i18n update-po languages/classic-starter.pot languages/ja.po

# 3. ja.po を編集して未翻訳（msgstr ""）を埋めたあと、.mo にコンパイル
docker compose run --rm wpcli wp i18n make-mo languages/
```

- コマンドはプロジェクトルート（`wp-theme/`）で実行する。`wpcli` サービスは**親リポジトリの `docker-compose.yml`** で定義しているため、**テーマを他案件へ持ち出した際は付いてこない**。移行先では同等のサービスを用意するか、Poedit（`.po` を保存すると `.mo` も出力される）で代替する。
- `Theme Name` は固有名詞なので `msgstr ""` のままでよい（msgid にフォールバックする）。
- `Author` は `style.css` ではローマ字（`Momiji`）のまま書き、`ja.po` で「もみじ」と訳す（日本語の管理画面ではひらがな、それ以外の言語ではローマ字で表示される）。案件で名義を変える時は、`style.css` と `ja.po` の両方を直す。

## 案件で使う時のスラッグ変更手順

テーマスラッグ（フォルダ名・テキストドメイン・ハンドル名・関数の接頭辞）を案件用に変える手順は、**スキル `theme-rename` にまとめてある**。

## Git運用

- classic-starter は独立した git リポジトリ。
- **コミット前に必ず `npm run lint:css` を実行**する。
- **git のコミット・push は必ずユーザー自身が行う（Claude Code はコミット・push 禁止）。**
