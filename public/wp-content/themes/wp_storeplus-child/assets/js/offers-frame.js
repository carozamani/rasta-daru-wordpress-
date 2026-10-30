/**
 * Offers section ("شگفت‌انگیز") — ONE component for home + PDP.
 * - builds the title column (.of-side): % icon + title + optional timer + "view all"
 * - removes the parent-theme purple banner slide (its link is reused on .of-side)
 * - strips legacy parent cards so only .sp-product-card remains
 * - countdown end time: Settings > تایمر شگفت‌انگیز → window.storeplusOffersTimer = { end: <ms> }
 * Styling: assets/css/offers-frame.css
 */
(function () {
  'use strict';

  var SECTION = '.irantheme-woocommerce-offers-products-section';
  var WRAPPER = '.irantheme-woocommerce-products-wrapper';
  var SWIPER = '.irantheme-woocommerce-product-swiper';
  var BANNER = '.irantheme-woocommerce-offers-products-side';

  var ICON =
    '<svg viewBox="0 0 76 50" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="6.5" stroke-linecap="round">' +
    '<circle cx="23" cy="15" r="8"/>' +
    '<circle cx="53" cy="35" r="8"/>' +
    '<path d="M57 7 19 43"/>' +
    '</svg>';

  var ARROW =
    '<svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true" focusable="false">' +
    '<path d="M15 5l-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>' +
    '</svg>';

  var FA_DIGITS = '۰۱۲۳۴۵۶۷۸۹';

  function fa(value) {
    return String(value).replace(/\d/g, function (d) {
      return FA_DIGITS.charAt(+d);
    });
  }

  function pad(n) {
    return n < 10 ? '0' + n : String(n);
  }

  function timerEnd() {
    var cfg = window.storeplusOffersTimer;
    var end = cfg && Number(cfg.end);
    return end && end > Date.now() ? end : 0;
  }

  function timerHtml() {
    return (
      '<span class="of-timer" role="timer" aria-label="زمان باقی‌مانده">' +
      '<span class="of-t"><b data-u="h">' + fa('00') + '</b><i>ساعت</i></span>' +
      '<span class="of-sep">:</span>' +
      '<span class="of-t"><b data-u="m">' + fa('00') + '</b><i>دقیقه</i></span>' +
      '<span class="of-sep">:</span>' +
      '<span class="of-t"><b data-u="s">' + fa('00') + '</b><i>ثانیه</i></span>' +
      '</span>'
    );
  }

  function startCountdown(side, end) {
    var timer = side.querySelector('.of-timer');
    if (!timer) return;
    var h = timer.querySelector('[data-u="h"]');
    var m = timer.querySelector('[data-u="m"]');
    var s = timer.querySelector('[data-u="s"]');
    if (!h || !m || !s) return;

    var id = 0;
    function tick() {
      var left = Math.floor((end - Date.now()) / 1000);
      if (left <= 0) {
        if (id) clearInterval(id);
        if (timer.parentNode) timer.parentNode.removeChild(timer);
        side.classList.remove('has-timer');
        return;
      }
      h.textContent = fa(pad(Math.floor(left / 3600)));
      m.textContent = fa(pad(Math.floor((left % 3600) / 60)));
      s.textContent = fa(pad(left % 60));
    }

    tick();
    id = setInterval(tick, 1000);
  }

  function buildSide(href) {
    var end = timerEnd();
    var side = document.createElement(href ? 'a' : 'div');
    side.className = 'of-side' + (end ? ' has-timer' : '');
    if (href) side.setAttribute('href', href);
    side.innerHTML =
      '<span class="of-icon">' + ICON + '</span>' +
      '<span class="of-title">' +
      '<span class="of-title-lg">شگفت‌انگیز</span>' +
      '</span>' +
      (end ? timerHtml() : '') +
      (href ? '<span class="of-cta"><span>مشاهده همه</span>' + ARROW + '</span>' : '');

    if (end) startCountdown(side, end);
    return side;
  }

  function slideIndex(el, slideEl) {
    var list = el.querySelectorAll('.swiper-wrapper > .swiper-slide');
    return Array.prototype.indexOf.call(list, slideEl);
  }

  function removeBanner(el, swiper, banner) {
    var slideEl = banner.closest('.swiper-slide');
    if (!slideEl) return;

    var index = slideIndex(el, slideEl);
    var done = false;
    if (index > -1 && typeof swiper.removeSlide === 'function') {
      try {
        swiper.removeSlide(index);
        done = true;
      } catch (e) {
        done = false;
      }
    }
    if (!done && slideEl.parentNode) {
      slideEl.parentNode.removeChild(slideEl);
    }
  }

  /** Drop parent-theme cards that are not the child .sp-product-card component. */
  function stripLegacyCards(el, swiper) {
    var items = el.querySelectorAll('.irantheme-woocommerce-products-item:not(.sp-product-card)');
    if (!items.length) return;

    var slides = [];
    for (var i = 0; i < items.length; i++) {
      var slide = items[i].closest('.swiper-slide') || items[i].closest('.irantheme-woocommerce-products-col');
      if (slide && slides.indexOf(slide) === -1) slides.push(slide);
    }

    for (var s = slides.length - 1; s >= 0; s--) {
      var slideEl = slides[s];
      var index = slideIndex(el, slideEl);
      var done = false;
      if (index > -1 && swiper && typeof swiper.removeSlide === 'function') {
        try {
          swiper.removeSlide(index);
          done = true;
        } catch (e) {
          done = false;
        }
      }
      if (!done && slideEl.parentNode) {
        slideEl.parentNode.removeChild(slideEl);
      }
    }
  }

  function apply(section) {
    var wrapper = section.querySelector(WRAPPER);
    var el = section.querySelector(SWIPER);
    if (!wrapper || !el) return true;
    if (section.classList.contains('of-ready')) return true;

    var swiper = el.swiper;
    if (!swiper) return false;

    var href = '';
    var banner = el.querySelector(BANNER);
    if (banner) {
      var link = banner.querySelector('a[href]');
      if (link) href = link.getAttribute('href') || '';
    }

    if (!wrapper.querySelector('.of-side')) {
      wrapper.insertBefore(buildSide(href), el);
    }

    if (banner) {
      try {
        removeBanner(el, swiper, banner);
      } catch (e) {
        /* banner stays hidden by CSS */
      }
    }

    try {
      stripLegacyCards(el, swiper);
    } catch (e) {
      /* ignore */
    }

    section.classList.add('of-ready');
    try {
      swiper.update();
    } catch (e) {
      /* ignore */
    }
    return true;
  }

  function run() {
    var sections = document.querySelectorAll(SECTION);
    if (!sections.length) return;

    var tries = 0;
    var timer = setInterval(function () {
      var pending = false;
      sections.forEach(function (section) {
        try {
          if (apply(section) === false) pending = true;
        } catch (e) {
          /* never let one error stop the others */
        }
      });
      tries += 1;
      if (!pending || tries > 60) clearInterval(timer);
    }, 250);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', run);
  } else {
    run();
  }
})();
