/**
 * ページの先頭へ戻るボタンの表示切り替え
 *
 * ヘッダーが画面から外れている間だけ表示する。scroll イベントではなく IntersectionObserver で
 * 監視し、スクロール中にメインスレッドで処理が走らないようにする。
 */
(function () {
  const button = document.querySelector('.page-top');
  const header = document.querySelector('.header');

  if (!button || !header) {
    // ヘッダーが無い時は常に表示する
    if (button) {
      button.classList.add('is-visible');
    }
    return;
  }

  new IntersectionObserver(function (entries) {
    button.classList.toggle('is-visible', !entries[0].isIntersecting);
  }).observe(header);
})();
