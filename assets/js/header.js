/**
 * ヘッダーのパネル（検索・メニュー）の開閉
 *
 * 開閉状態はボタンの aria-expanded だけに持たせ、body のクラス（is-*-open）はそこから付け外しする。
 * 状態の持ち場を1つにして、支援技術への通知と見た目がずれないようにする。
 */
(function () {
  // 768px は CSS の md のブレークポイントと揃える
  const desktop = window.matchMedia('(min-width: 768px)');

  // 子メニューの開閉ボタン。md 未満はアコーディオン、md 以上はタッチ端末（ホバーできない）のドロップダウンに使う。
  // ボタンは functions.php が hidden で出力する
  const subToggles = Array.from(document.querySelectorAll('.header-nav-sub-toggle'));

  const closeSubToggles = function () {
    subToggles.forEach(function (button) {
      button.setAttribute('aria-expanded', 'false');
    });
  };

  // md 未満は今いるページが子の中にある時だけ最初から開き、md 以上（ドロップダウン）はすべて閉じて始める
  const resetSubToggles = function () {
    subToggles.forEach(function (button) {
      const current = !desktop.matches && button.parentElement.classList.contains('current-menu-ancestor');

      button.setAttribute('aria-expanded', current ? 'true' : 'false');
    });
  };

  subToggles.forEach(function (button, index) {
    const sublist = button.parentElement.querySelector(':scope > .header-nav-sublist');

    // 開閉する子メニューを支援技術に伝える（ボタンは JS が動く時だけ使うので、id もここで振る）
    if (sublist) {
      sublist.id = sublist.id || 'header-nav-sublist-' + (index + 1);
      button.setAttribute('aria-controls', sublist.id);
    }

    button.hidden = false;

    button.addEventListener('click', function () {
      const open = button.getAttribute('aria-expanded') !== 'true';

      // md 以上のドロップダウンは重なるので、ほかは閉じる
      if (open && desktop.matches) {
        closeSubToggles();
      }

      button.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  resetSubToggles();
  desktop.addEventListener('change', resetSubToggles);

  // md 以上のタッチ端末は、ドロップダウンの外をタップしたら閉じる
  document.addEventListener('click', function (event) {
    if (!desktop.matches) {
      return;
    }

    subToggles.forEach(function (button) {
      if (!button.parentElement.contains(event.target)) {
        button.setAttribute('aria-expanded', 'false');
      }
    });
  });

  // 子メニューのドロップダウン（md 以上）。開く時に、左端をそろえた位置で画面の右にはみ出すかを測り、
  // はみ出す時だけ右端をそろえる。閉じている間も visibility: hidden で配置されているので、開く前に測れる
  const dropdowns = [];

  document.querySelectorAll('.header-nav-list > .header-nav-item').forEach(function (item) {
    const sublist = item.querySelector(':scope > .header-nav-sublist');

    if (!sublist) {
      return;
    }

    const align = function () {
      if (!desktop.matches) {
        return;
      }

      sublist.classList.remove('is-align-right');
      sublist.classList.toggle('is-align-right', sublist.getBoundingClientRect().right > document.documentElement.clientWidth);
    };

    // Esc で閉じた印は、マウスやフォーカスが項目から離れたら外し、また開けるようにする
    const clearDismissed = function () {
      item.classList.remove('is-dismissed');
    };

    item.addEventListener('mouseenter', align);
    item.addEventListener('focusin', align);
    item.addEventListener('click', align);
    item.addEventListener('mouseleave', clearDismissed);
    item.addEventListener('focusout', function (event) {
      if (!item.contains(event.relatedTarget)) {
        clearDismissed();
      }
    });

    dropdowns.push({ item: item, sublist: sublist, align: align });
  });

  // 開いたままリサイズした時は、開いているドロップダウンだけを測り直す（開く条件は CSS と揃える）。
  // リサイズ中は何度も発火するので、1回の描画につき1回にまとめる
  let resizeFrame = 0;

  window.addEventListener('resize', function () {
    if (resizeFrame) {
      return;
    }

    resizeFrame = window.requestAnimationFrame(function () {
      resizeFrame = 0;

      dropdowns.forEach(function (dropdown) {
        if (dropdown.item.matches(':hover, :has(:focus-visible), :has(> [aria-expanded="true"])')) {
          dropdown.align();
        }
      });
    });
  });

  // Esc で開いているドロップダウンを閉じる（ホバーで出た内容も、マウスを動かさずに消せるようにする）
  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape' || !desktop.matches) {
      return;
    }

    dropdowns.forEach(function (dropdown) {
      const item = dropdown.item;
      const subToggle = item.querySelector(':scope > .header-nav-sub-toggle');

      if (!item.matches(':hover, :has(:focus-visible), :has(> [aria-expanded="true"])')) {
        return;
      }

      // 子メニューの中にフォーカスがある時は、隠れる前に親のリンクへ戻す
      if (dropdown.sublist.contains(document.activeElement)) {
        item.querySelector(':scope > .header-nav-link').focus();
      }

      if (subToggle) {
        subToggle.setAttribute('aria-expanded', 'false');
      }

      item.classList.add('is-dismissed');
    });
  });

  const toggles = Array.from(document.querySelectorAll('.header-action'));

  if (!toggles.length) {
    return;
  }

  // パネルを開いている間、キーボード操作の対象から外す領域。
  // 検索のモーダルはヘッダーごと覆う。メニューのパネルはヘッダーの中にあるのでヘッダーは含めず、
  // 暗幕の下に隠れるサイト名と検索ボタンだけを外す
  const covered = {
    search: document.querySelectorAll('.skip-link, .header, .breadcrumb, .main, .footer'),
    nav: document.querySelectorAll('.skip-link, .header-branding, .header-search-toggle, .breadcrumb, .main, .footer'),
  };
  const searchPanel = document.getElementById('header-search');

  // 検索パネルは JS が動く時だけモーダルとして扱う
  if (searchPanel) {
    searchPanel.setAttribute('role', 'dialog');
    searchPanel.setAttribute('aria-modal', 'true');
    searchPanel.setAttribute('aria-label', searchPanel.querySelector('.search-form-input')?.getAttribute('aria-label') || '');
  }

  const isOpen = function (toggle) {
    return toggle.getAttribute('aria-expanded') === 'true';
  };

  // パネルごとの見せ方は CSS 側で決める（is-nav-open は右からのスライドイン、検索は全画面のモーダル）。
  // is-header-open はどれかが開いている印で、背面のスクロール停止に使う
  const sync = function () {
    toggles.forEach(function (toggle) {
      document.body.classList.toggle('is-' + toggle.dataset.headerPanel + '-open', isOpen(toggle));
    });

    const opened = toggles.find(isOpen);

    document.body.classList.toggle('is-header-open', Boolean(opened));

    Object.keys(covered).forEach(function (panel) {
      covered[panel].forEach(function (element) {
        element.inert = false;
      });
    });

    if (opened && covered[opened.dataset.headerPanel]) {
      covered[opened.dataset.headerPanel].forEach(function (element) {
        element.inert = true;
      });
    }
  };

  const setOpen = function (toggle, open) {
    // どちらのパネルも背面を覆うので、同時には開かない
    if (open) {
      toggles.forEach(function (other) {
        other.setAttribute('aria-expanded', other === toggle ? 'true' : 'false');
      });
    } else {
      toggle.setAttribute('aria-expanded', 'false');
    }

    sync();

    if (!open) {
      return;
    }

    // 検索のように入力欄を持つパネルは、開いた直後に入力を始められるようにする
    const panel = document.getElementById(toggle.getAttribute('aria-controls'));
    const field = panel && panel.querySelector('input[type="search"], input[type="text"]');

    if (field) {
      field.focus();

      // 表示の切り替えが間に合わずフォーカスできなかった時は、次の描画でもう一度当てる
      if (document.activeElement !== field) {
        window.requestAnimationFrame(function () {
          field.focus();
        });
      }
    }
  };

  // JS が動く環境でだけボタンを出し、閉じた状態から始める
  toggles.forEach(function (toggle) {
    toggle.hidden = false;
    toggle.setAttribute('aria-expanded', 'false');

    toggle.addEventListener('click', function () {
      setOpen(toggle, !isOpen(toggle));
    });
  });

  sync();

  // 検索のモーダルの閉じるボタン。JS が動く時だけ表示する
  const searchClose = searchPanel && searchPanel.querySelector('.header-search-close');

  if (searchClose) {
    searchClose.hidden = false;

    searchClose.addEventListener('click', function () {
      const opened = toggles.find(isOpen);

      if (opened) {
        setOpen(opened, false);
        opened.focus();
      }
    });
  }

  // 検索のモーダルは、フォームの外（暗幕の部分）のクリックで閉じる
  if (searchPanel) {
    searchPanel.addEventListener('click', function (event) {
      const opened = toggles.find(isOpen);

      if (event.target === searchPanel && opened) {
        setOpen(opened, false);
        opened.focus();
      }
    });
  }

  // メニューのパネルは、背面の暗幕（ヘッダーの ::before）のクリックで閉じる
  const header = document.querySelector('.header');

  if (header) {
    header.addEventListener('click', function (event) {
      const opened = toggles.find(isOpen);

      if (event.target === header && opened && opened.dataset.headerPanel === 'nav') {
        setOpen(opened, false);
        opened.focus();
      }
    });
  }

  // SP のメニューでページ内のリンク（#）を押した時も、パネルを閉じて移動先を見せる（別のページへのリンクはそのまま移動する）
  const navPanel = document.querySelector('.header-nav');

  if (navPanel) {
    navPanel.addEventListener('click', function (event) {
      const opened = toggles.find(isOpen);

      if (event.target.closest('a') && opened && opened.dataset.headerPanel === 'nav') {
        setOpen(opened, false);
      }
    });
  }

  // Esc で閉じ、フォーカスを開いていたボタンへ戻す
  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') {
      return;
    }

    const opened = toggles.find(isOpen);

    if (opened) {
      setOpen(opened, false);
      opened.focus();
    }
  });

  // md 以上ではメニューが常時表示になり開閉ボタンも隠れるため、開いた状態を持ち越さない。
  // 検索のモーダルはどの幅でも同じなので閉じない
  desktop.addEventListener('change', function (event) {
    const nav = toggles.find(function (toggle) {
      return toggle.dataset.headerPanel === 'nav';
    });

    if (event.matches && nav && isOpen(nav)) {
      setOpen(nav, false);
    }
  });
})();
