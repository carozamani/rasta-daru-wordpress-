/*
 * Single product gallery:
 *  1) hover zoom on the current image (follows the cursor; desktop pointers only)
 *  2) click opens a full-size viewer (modal) with prev / next, counter, Esc / backdrop to close
 * Works on top of the theme's own Swiper markup; it does not touch the slider logic.
 * The click is handled in the capture phase so the parent's own magnifier does not open a second modal.
 */
(function () {
  var WRAP = '.irantheme-physical-product .product-image-wrapper';
  var ITEM = '.product-image-hero-item';
  var canHover = window.matchMedia && window.matchMedia('(hover:hover) and (pointer:fine)').matches;

  /* ---------- hover zoom ---------- */
  if (canHover) {
    document.addEventListener('mousemove', function (e) {
      var item = e.target.closest && e.target.closest(WRAP + ' ' + ITEM);
      if (!item) return;
      var r = item.getBoundingClientRect();
      if (!r.width || !r.height) return;
      var x = Math.max(0, Math.min(100, ((e.clientX - r.left) / r.width) * 100));
      var y = Math.max(0, Math.min(100, ((e.clientY - r.top) / r.height) * 100));
      var img = item.querySelector('img');
      if (!img) return;
      img.style.transformOrigin = x + '% ' + y + '%';
      item.classList.add('pg-zooming');
    });
    document.addEventListener('mouseout', function (e) {
      var item = e.target.closest && e.target.closest(WRAP + ' ' + ITEM);
      if (!item || item.contains(e.relatedTarget)) return;
      item.classList.remove('pg-zooming');
    });
  }

  /* ---------- viewer ---------- */
  var viewer, imgEl, countEl, closeBtn, prevBtn, nextBtn;
  var list = [], index = 0, lastFocus = null;

  function biggestSrc(img) {
    var srcset = img.getAttribute('srcset') || '';
    var best = '', bestW = 0;
    srcset.split(',').forEach(function (part) {
      var bits = part.trim().split(/\s+/);
      var w = parseInt(bits[1], 10) || 0;
      if (bits[0] && w >= bestW) { best = bits[0]; bestW = w; }
    });
    return best || img.currentSrc || img.getAttribute('src') || '';
  }

  function build() {
    if (viewer) return;
    viewer = document.createElement('div');
    viewer.className = 'pg-viewer';
    viewer.setAttribute('role', 'dialog');
    viewer.setAttribute('aria-modal', 'true');
    viewer.setAttribute('aria-label', 'تصویر محصول');
    viewer.innerHTML =
      '<div class="pg-viewer__stage">' +
      '<img class="pg-viewer__img" alt="" draggable="false">' +
      '<button type="button" class="pg-viewer__btn pg-viewer__close" aria-label="بستن">&times;</button>' +
      '<button type="button" class="pg-viewer__btn pg-viewer__prev" aria-label="تصویر قبلی">&rsaquo;</button>' +
      '<button type="button" class="pg-viewer__btn pg-viewer__next" aria-label="تصویر بعدی">&lsaquo;</button>' +
      '<span class="pg-viewer__count" aria-live="polite"></span>' +
      '</div>';
    document.body.appendChild(viewer);
    imgEl = viewer.querySelector('.pg-viewer__img');
    countEl = viewer.querySelector('.pg-viewer__count');
    closeBtn = viewer.querySelector('.pg-viewer__close');
    prevBtn = viewer.querySelector('.pg-viewer__prev');
    nextBtn = viewer.querySelector('.pg-viewer__next');

    closeBtn.addEventListener('click', close);
    prevBtn.addEventListener('click', function () { go(-1); });
    nextBtn.addEventListener('click', function () { go(1); });
    viewer.addEventListener('click', function (e) {
      if (e.target === viewer || e.target.classList.contains('pg-viewer__stage')) close();
    });
    document.addEventListener('keydown', onKey);
  }

  function show() {
    imgEl.src = list[index].src;
    imgEl.alt = list[index].alt;
    countEl.textContent = (index + 1).toLocaleString('fa-IR') + ' / ' + list.length.toLocaleString('fa-IR');
    var multi = list.length > 1;
    prevBtn.style.display = nextBtn.style.display = countEl.style.display = multi ? '' : 'none';
  }

  function go(step) {
    if (list.length < 2) return;
    index = (index + step + list.length) % list.length;
    show();
  }

  function open(items, start) {
    build();
    list = items;
    index = start;
    lastFocus = document.activeElement;
    show();
    viewer.classList.add('is-open');
    document.body.classList.add('pg-viewer-open');
    closeBtn.focus();
  }

  function close() {
    if (!viewer) return;
    viewer.classList.remove('is-open');
    document.body.classList.remove('pg-viewer-open');
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }

  function onKey(e) {
    if (!viewer || !viewer.classList.contains('is-open')) return;
    var rtl = getComputedStyle(document.documentElement).direction === 'rtl';
    if (e.key === 'Escape') { close(); return; }
    if (e.key === 'ArrowRight') { go(rtl ? -1 : 1); return; }
    if (e.key === 'ArrowLeft') { go(rtl ? 1 : -1); return; }
    if (e.key === 'Tab') {
      var btns = [closeBtn, prevBtn, nextBtn].filter(function (b) { return b.style.display !== 'none'; });
      var i = btns.indexOf(document.activeElement);
      e.preventDefault();
      btns[(i + (e.shiftKey ? -1 : 1) + btns.length) % btns.length].focus();
    }
  }

  /* ---------- click (ignore drags / swipes) ---------- */
  var down = null;
  document.addEventListener('pointerdown', function (e) {
    down = { x: e.clientX, y: e.clientY };
  }, true);

  document.addEventListener('click', function (e) {
    var item = e.target.closest && e.target.closest(WRAP + ' ' + ITEM);
    if (!item) return;
    if (down && (Math.abs(e.clientX - down.x) > 6 || Math.abs(e.clientY - down.y) > 6)) return;
    var wrap = item.closest(WRAP);
    var items = [].slice.call(wrap.querySelectorAll(ITEM)).filter(function (n) {
      return !n.classList.contains('swiper-slide-duplicate') && n.querySelector('img');
    });
    var at = items.indexOf(item);
    if (at < 0) return;
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    open(items.map(function (n) {
      var im = n.querySelector('img');
      return { src: biggestSrc(im), alt: im.getAttribute('alt') || '' };
    }), at);
  }, true);

  /* Thumbs as a horizontal square row under the main image (override theme vertical layout). */
  function forceThumbRow() {
    var el = document.querySelector(WRAP + ' .product-image-thumb-slider');
    if (!el) { return; }
    var sw = el.swiper;
    if (!sw) { return; }
    try {
      if (typeof sw.changeDirection === 'function') { sw.changeDirection('horizontal', false); }
      sw.params.direction = 'horizontal';
      sw.params.slidesPerView = 'auto';
      sw.params.spaceBetween = 8;
      sw.params.watchOverflow = true;
      sw.params.breakpoints = {
        0: { direction: 'horizontal', slidesPerView: 'auto', spaceBetween: 8 }
      };
      sw.update();
    } catch (err) { /* ignore */ }
  }
  function nudge() {
    forceThumbRow();
    window.dispatchEvent(new Event('resize'));
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      setTimeout(nudge, 80);
      setTimeout(nudge, 400);
    });
  } else {
    setTimeout(nudge, 80);
    setTimeout(nudge, 400);
  }
  window.addEventListener('load', function () { setTimeout(nudge, 150); setTimeout(nudge, 700); });
})();

/* Extras: sharp image while zooming + arrow keys that match the on-screen buttons (prev = right, next = left). */
(function () {
  var WRAP = '.irantheme-physical-product .product-image-wrapper';
  var ITEM = '.product-image-hero-item';

  function biggest(img) {
    var best = '', bestW = 0;
    (img.getAttribute('srcset') || '').split(',').forEach(function (p) {
      var b = p.trim().split(/\s+/), w = parseInt(b[1], 10) || 0;
      if (b[0] && w >= bestW) { best = b[0]; bestW = w; }
    });
    return best;
  }

  /* first hover on an image: load its full-size file so the zoom is not blurry */
  document.addEventListener('mouseover', function (e) {
    var item = e.target.closest && e.target.closest(WRAP + ' ' + ITEM);
    if (!item) return;
    var img = item.querySelector('img');
    if (!img || img.hasAttribute('data-pg-hires')) return;
    img.setAttribute('data-pg-hires', '1');
    var big = biggest(img);
    if (!big || big === img.getAttribute('src')) return;
    var pre = new Image();
    pre.onload = function () {
      img.removeAttribute('srcset');
      img.removeAttribute('sizes');
      img.src = big;
    };
    pre.src = big;
  });

  document.addEventListener('keydown', function (e) {
    var v = document.querySelector('.pg-viewer.is-open');
    if (!v || (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft')) return;
    e.stopImmediatePropagation();
    var btn = v.querySelector(e.key === 'ArrowRight' ? '.pg-viewer__prev' : '.pg-viewer__next');
    if (btn && btn.style.display !== 'none') btn.click();
  }, true);
})();
