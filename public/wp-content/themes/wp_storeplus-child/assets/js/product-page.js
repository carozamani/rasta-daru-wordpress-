/**
 * Rasta Daru PDP — GeneSabz structure, Rasta tokens
 *
 *  pp-stage: gallery | identity  (display:contents → 3-col with buy)
 *  pp-buy:   sticky commerce (vars → seller → price → CTA⇄qty → signals)
 *  pp-trust-bar + pp-doc: full-width document below hero
 */
(function () {
  'use strict';

  var Q = function (s, r) { return (r || document).querySelector(s); };
  var QA = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  /* Shared by buy-box + sticky mbar — never click off-screen CTA (that scrolls). */
  var commerceCtl = null;

  function mainSummary() {
    return Q('.irantheme-physical-product .pp-buy > .irantheme-single-product-shopping-summary') ||
      Q('.irantheme-physical-product .irantheme-single-product-main-content-wrapper > .irantheme-single-product-shopping-summary:not(.irantheme-single-product-content-shopping-summary)');
  }

  function vis(key) {
    var cfg = window.spProductPage || {};
    if (!cfg.visibility || typeof cfg.visibility[key] === 'undefined') { return true; }
    return !!cfg.visibility[key];
  }

  /* ---------- sticky offset for header ---------- */
  function stickyOffset() {
    var header = document.getElementById('irantheme-header-main');
    var ticking = false;
    function update() {
      ticking = false;
      var top = 100;
      if (header) {
        var r = header.getBoundingClientRect();
        var menuH = 0;
        if (header.classList.contains('sp-menu-hidden')) {
          menuH = parseFloat(getComputedStyle(header).getPropertyValue('--sp-menu-h')) || 0;
        }
        top = Math.max(0, r.bottom - menuH) + 10;
      }
      document.documentElement.style.setProperty('--pp-sticky-top', Math.round(top) + 'px');
    }
    function request() { if (!ticking) { ticking = true; requestAnimationFrame(update); } }
    update();
    window.addEventListener('scroll', request, { passive: true });
    window.addEventListener('resize', request);
    if (header) {
      new MutationObserver(request).observe(header, { attributes: true, attributeFilter: ['class', 'style'] });
      header.addEventListener('transitionend', request);
    }
  }

  function quantity() {
    QA('.irantheme-physical-product .irantheme-quantity-btn').forEach(function (b) {
      b.setAttribute('type', 'button');
      b.setAttribute('aria-label', b.classList.contains('irantheme-quantity-selector-minus') ? 'کاهش تعداد' : 'افزایش تعداد');
    });
    QA('.irantheme-physical-product .irantheme-input-text-quantity').forEach(function (i) {
      i.setAttribute('inputmode', 'numeric');
      i.setAttribute('autocomplete', 'off');
    });
  }

  function productIdFrom(el) {
    var cfg = window.spProductPage || {};
    if (cfg.productId) { return String(cfg.productId); }
    if (!el) { return ''; }
    var form = el.closest('form.cart') || el.closest('form');
    var field = form && (Q('input[name="product_id"]', form) || Q('button[name="add-to-cart"]', form) || Q('input[name="add-to-cart"]', form));
    if (field && field.value) { return String(field.value); }
    if (el.getAttribute && el.getAttribute('data-product_id')) { return el.getAttribute('data-product_id'); }
    return '';
  }

  function parseQty(raw) {
    var s = String(raw == null ? '' : raw)
      .replace(/[۰-۹]/g, function (d) { return String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d)); })
      .replace(/[٠-٩]/g, function (d) { return String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)); })
      .replace(/[^\d]/g, '');
    var n = parseInt(s, 10);
    return isNaN(n) ? 0 : n;
  }

  function cartAjaxUrls() {
    var cfg = window.spProductPage || {};
    var urls = [];
    var params = window.wc_add_to_cart_params || window.wc_cart_fragments_params || {};
    if (params.wc_ajax_url) {
      urls.push(params.wc_ajax_url.toString().replace('%%endpoint%%', 'pp_set_cart_qty'));
    }
    if (cfg.ajaxUrl) { urls.push(cfg.ajaxUrl); }
    if (cfg.ajaxFallback) {
      urls.push(cfg.ajaxFallback + (cfg.ajaxFallback.indexOf('?') >= 0 ? '&' : '?') + 'action=pp_set_cart_qty');
    }
    urls.push('/?wc-ajax=pp_set_cart_qty');
    urls.push('/wp-admin/admin-ajax.php?action=pp_set_cart_qty');
    /* de-dupe + drop broken "https:/?…" */
    var seen = {};
    return urls.filter(function (u) {
      if (!u || !/^(\/|https?:\/\/[^/])/i.test(u)) { return false; }
      if (seen[u]) { return false; }
      seen[u] = 1;
      return true;
    });
  }

  function collectVariation(form) {
    var out = { variation_id: 0, variation: {} };
    if (!form) { return out; }
    var vid = Q('input[name="variation_id"]', form) || Q('input.variation_id', form);
    if (vid && vid.value) { out.variation_id = parseInt(vid.value, 10) || 0; }
    QA('select[name^="attribute_"], input[name^="attribute_"]', form).forEach(function (el) {
      if (el.name && el.value) { out.variation[el.name] = el.value; }
    });
    return out;
  }

  function setCartQty(productId, quantity, cartKey, opts) {
    opts = opts || {};
    var urls = cartAjaxUrls();
    if (!urls.length) { return Promise.reject(new Error('no ajax')); }

    function buildBody() {
      var body = new FormData();
      var cfg = window.spProductPage || {};
      body.append('action', 'pp_set_cart_qty');
      body.append('product_id', productId || '');
      body.append('cart_item_key', cartKey || cfg.cartKey || '');
      if (cfg.cartNonce) { body.append('nonce', cfg.cartNonce); }
      var form = Q('.irantheme-physical-product .pp-buy form.cart') ||
        Q('.irantheme-physical-product form.cart');
      var v = collectVariation(form);
      if (v.variation_id) { body.append('variation_id', String(v.variation_id)); }
      Object.keys(v.variation).forEach(function (k) {
        body.append('variation[' + k + ']', v.variation[k]);
      });
      if (opts.get) { body.append('get', '1'); }
      else { body.append('quantity', String(quantity)); }
      return body;
    }

    function tryUrl(i) {
      if (i >= urls.length) { return Promise.reject(new Error('set qty failed')); }
      return fetch(urls[i], { method: 'POST', body: buildBody(), credentials: 'same-origin' })
        .then(function (r) {
          if (!r.ok) { throw new Error('http ' + r.status); }
          return r.json();
        })
        .then(function (json) {
          var data = json && (json.data || json);
          if (json && json.success === false) { throw new Error('set qty failed'); }
          if (window.spProductPage && data) {
            window.spProductPage.cartQty = data.qty || 0;
            window.spProductPage.cartKey = data.key || '';
          }
          if (!opts.get && window.jQuery && data && data.fragments) {
            try {
              window.jQuery.each(data.fragments, function (sel, html) {
                window.jQuery(sel).replaceWith(html);
              });
              window.jQuery(document.body).trigger('wc_fragments_refreshed');
              window.jQuery(document.body).trigger('wc_fragment_refresh');
            } catch (err) { /* ignore */ }
          }
          return data || { qty: quantity, key: cartKey || '' };
        })
        .catch(function () { return tryUrl(i + 1); });
    }

    return tryUrl(0);
  }

  function qtyApi() {
    return window.storeplusQty || null;
  }

  /**
   * Senior commerce controller:
   *  cartQty === 0 → only CTA
   *  cartQty >= 1  → only stepper (trash at 1, minus above)
   *  every +/-/CTA writes through to Woo cart
   */
  function priceQtyRow() {
    var buy = Q('.irantheme-physical-product .pp-buy');
    if (!buy || Q('.pp-price-row', buy)) { return; }

    var price = Q('.product-add-to-card-price', buy);
    if (!price) { return; }

    var form = Q('.pp-buy-form', buy) || Q('form.cart', buy) || Q('form.variations_form', buy);
    var scope = form || buy;
    var cta = Q('button.single_add_to_cart_button:not(.outofstock)', scope) ||
      Q('a.single_add_to_cart_button:not(.outofstock)', scope) ||
      Q('button.single_add_to_cart_button:not(.outofstock)', buy) ||
      Q('.single_add_to_cart_button:not(.outofstock)', buy);
    if (!cta) { return; }
    /* Never treat a wrapper DIV as the CTA */
    if (cta.tagName === 'DIV') {
      cta = Q('button.single_add_to_cart_button:not(.outofstock), a.single_add_to_cart_button:not(.outofstock)', cta) || cta;
      if (cta.tagName === 'DIV') { return; }
    }

    /* Prefer qty inside the real form; never leave theme +/- visible beside CTA */
    var qtyBox = Q('.irantheme-single-product-quantity.quantity', scope) ||
      Q('.quantity', scope) ||
      Q('.irantheme-single-product-quantity.quantity', buy) ||
      Q('.quantity', buy);
    QA('.irantheme-single-product-quantity.quantity, .quantity', buy).forEach(function (el) {
      if (el !== qtyBox) {
        el.classList.add('pp-woo-qty-hid');
        el.setAttribute('hidden', '');
      }
    });

    var row = document.createElement('div');
    row.className = 'pp-price-row pp-no-cart';
    row.setAttribute('data-pp-commerce', '1');
    price.parentNode.insertBefore(row, price);
    row.appendChild(price);

    var shell = document.createElement('div');
    shell.className = 'pp-commerce is-empty';

    /* --- shared .sp-qty stepper (same component as cart / sidebar) --- */
    var field = document.createElement('div');
    field.className = 'pp-qty-field';
    field.setAttribute('hidden', '');
    field.setAttribute('aria-hidden', 'true');

    var api = qtyApi();
    var stepper = api
      ? api.create({ size: 'md', full: true, qty: 1 })
      : (function () {
          /* Fallback only if qty-stepper.js failed to load — same markup. */
          var d = document.createElement('div');
          d.className = 'sp-qty sp-qty--md sp-qty--full';
          d.setAttribute('data-sp-qty', '1');
          d.innerHTML =
            '<button type="button" class="sp-qty__btn sp-qty__dec is-trash" aria-label="حذف از سبد خرید">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16"/><path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/><path d="M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12"/><path d="M10 11v6M14 11v6"/></svg>' +
            '</button>' +
            '<span class="sp-qty__val">۱</span>' +
            '<button type="button" class="sp-qty__btn sp-qty__inc" aria-label="افزایش تعداد">' +
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>' +
            '</button>';
          return d;
        })();
    var btnDec = stepper.querySelector('.sp-qty__dec');
    var display = stepper.querySelector('.sp-qty__val');
    var btnInc = stepper.querySelector('.sp-qty__inc');
    field.appendChild(stepper);
    shell.appendChild(field);

    /* keep Woo qty input in sync (hidden) for form integrity */
    var wooInput = qtyBox && (
      Q('.irantheme-input-text-quantity', qtyBox) ||
      Q('input.qty', qtyBox) ||
      Q('input[type="number"]', qtyBox) ||
      Q('input[type="text"]', qtyBox)
    );
    if (qtyBox) {
      qtyBox.classList.add('pp-woo-qty-hid');
      qtyBox.setAttribute('hidden', '');
      /* park original qty out of the visible chrome */
      shell.appendChild(qtyBox);
    }

    var span = Q('span', cta);
    if (span) { span.textContent = 'ثبت در سبد خرید'; }
    else { cta.textContent = 'ثبت در سبد خرید'; }
    cta.classList.add('pp-cta');
    shell.appendChild(cta);
    row.appendChild(shell);

    /* hide leftover theme cart chrome in buy card */
    QA('.product-add-to-card-button', buy).forEach(function (el) {
      if (!shell.contains(el) && !row.contains(el)) {
        el.classList.add('pp-dup-cart-hide');
      }
    });

    bindCommerce(row, {
      cta: cta,
      field: field,
      stepper: stepper,
      btnDec: btnDec,
      btnInc: btnInc,
      display: display,
      wooInput: wooInput,
      form: form
    });
  }

  function bindCommerce(row, ui) {
    if (row.getAttribute('data-pp-bound')) { return; }
    row.setAttribute('data-pp-bound', '1');

    var busy = false;
    var pid = productIdFrom(ui.cta) || productIdFrom(row);
    var shown = 1;

    function cartQty() {
      return (window.spProductPage && parseQty(window.spProductPage.cartQty)) || 0;
    }

    function maxQty() {
      if (!ui.wooInput) { return 9999; }
      var m = parseQty(ui.wooInput.getAttribute('max'));
      return m > 0 ? m : 9999;
    }

    function writeLocal(n) {
      shown = Math.max(1, Math.min(maxQty(), parseQty(n) || 1));
      if (qtyApi() && ui.stepper) {
        qtyApi().paint(ui.stepper, shown);
      } else if (ui.display) {
        ui.display.textContent = String(shown);
      }
      if (ui.wooInput) {
        ui.wooInput.value = String(shown);
        ui.wooInput.setAttribute('value', String(shown));
      }
    }

    function setMode(inCart) {
      var shell = ui.cta && ui.cta.closest ? ui.cta.closest('.pp-commerce') : null;
      row.classList.toggle('pp-has-cart', !!inCart);
      row.classList.toggle('pp-no-cart', !inCart);
      if (shell) {
        shell.classList.toggle('is-in-cart', !!inCart);
        shell.classList.toggle('is-empty', !inCart);
      }
      if (inCart) {
        ui.field.removeAttribute('hidden');
        ui.field.setAttribute('aria-hidden', 'false');
        ui.cta.setAttribute('hidden', '');
        ui.cta.setAttribute('aria-hidden', 'true');
        ui.cta.tabIndex = -1;
        ui.cta.style.display = 'none';
      } else {
        ui.field.setAttribute('hidden', '');
        ui.field.setAttribute('aria-hidden', 'true');
        ui.cta.removeAttribute('hidden');
        ui.cta.setAttribute('aria-hidden', 'false');
        ui.cta.tabIndex = 0;
        ui.cta.style.display = '';
      }
    }

    function syncMode() {
      var q = cartQty();
      if (q > 0) {
        setMode(true);
        writeLocal(q);
      } else {
        setMode(false);
        writeLocal(1);
      }
    }

    function applyState(data) {
      var q = data && typeof data.qty !== 'undefined' ? parseQty(data.qty) : 0;
      if (!window.spProductPage) { window.spProductPage = {}; }
      window.spProductPage.cartQty = q;
      window.spProductPage.cartKey = (data && data.key) || '';
      syncMode();
    }

    function setBusy(on) {
      busy = !!on;
      row.classList.toggle('is-busy', busy);
      row.setAttribute('aria-busy', busy ? 'true' : 'false');
      if (ui.cta) {
        ui.cta.classList.toggle('is-loading', busy);
        ui.cta.setAttribute('aria-busy', busy ? 'true' : 'false');
        if (busy) { ui.cta.setAttribute('disabled', 'disabled'); }
        else { ui.cta.removeAttribute('disabled'); }
      }
      if (ui.stepper) {
        ui.stepper.classList.toggle('is-loading', busy);
        ui.stepper.setAttribute('aria-busy', busy ? 'true' : 'false');
      }
      var mbar = document.querySelector('.pp-mbar');
      if (mbar) {
        mbar.classList.toggle('is-busy', busy);
        var mcta = mbar.querySelector('.pp-mbar__cta');
        var mqty = mbar.querySelector('.pp-mbar__qty');
        if (mcta) {
          mcta.classList.toggle('is-loading', busy);
          mcta.disabled = busy;
        }
        if (mqty) { mqty.classList.toggle('is-loading', busy); }
      }
    }

    function pushCart(n) {
      n = Math.max(0, Math.min(maxQty(), parseQty(n)));
      if (busy) { return Promise.resolve(); }
      setBusy(true);
      return setCartQty(pid, n, window.spProductPage && window.spProductPage.cartKey)
        .then(function (data) { applyState(data); })
        .catch(function () { syncMode(); })
        .then(function () { setBusy(false); });
    }

    ui.cta.addEventListener('click', function (e) {
      if (row.classList.contains('pp-has-cart')) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return;
      }
      if (variationForm() && isBlocked(ui.cta)) { return; }
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      pushCart(1);
    }, true);

    ui.btnInc.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      pushCart(shown + 1);
    });

    ui.btnDec.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (shown <= 1) { pushCart(0); }
      else { pushCart(shown - 1); }
    });

    document.body.addEventListener('added_to_cart', function () {
      window.setTimeout(function () {
        setCartQty(pid, 0, window.spProductPage && window.spProductPage.cartKey, { get: true })
          .then(function (data) { applyState(data); })
          .catch(function () { /* keep */ });
      }, 80);
    });

    commerceCtl = {
      needsVariation: function () {
        return !!(variationForm() && isBlocked(ui.cta));
      },
      add: function () {
        if (this.needsVariation()) { return Promise.reject(new Error('variation')); }
        return pushCart(1);
      },
      inc: function () {
        return pushCart(shown + 1);
      },
      dec: function () {
        return pushCart(shown - 1);
      },
      remove: function () {
        return pushCart(0);
      },
      qty: cartQty,
      sync: syncMode
    };

    syncMode();
  }

  /* ---------- variation hint ---------- */
  var hintTimer = null;
  function variationForm() {
    return Q('.irantheme-physical-product .pp-buy .variations_form') ||
      Q('.irantheme-physical-product .variations_form');
  }

  function isBlocked(btn) {
    return btn.classList.contains('disabled') || btn.classList.contains('wc-variation-selection-needed') ||
      btn.classList.contains('wc-variation-is-unavailable') || btn.disabled;
  }

  function showHint(opts) {
    opts = opts || {};
    var form = variationForm();
    if (!form) { return false; }
    var missing = QA('select', form).filter(function (s) { return !s.value; });
    var rows = missing.map(function (s) { return s.closest('tr'); }).filter(Boolean);
    var names = rows.map(function (tr) {
      var l = Q('.irantheme-variation-label label', tr);
      return l ? l.textContent.replace(/[:：]\s*$/, '').trim() : '';
    }).filter(Boolean);

    var table = Q('table.irantheme-variations', form) || form;
    var hint = Q('.pp-select-hint', form);
    if (!hint) {
      hint = document.createElement('div');
      hint.className = 'pp-select-hint';
      hint.setAttribute('role', 'alert');
      if (table.parentNode) { table.parentNode.insertBefore(hint, table.nextSibling); }
    }
    hint.textContent = names.length ? ('لطفاً ابتدا ' + names.join(' و ') + ' را انتخاب کنید.') : 'لطفاً گزینه‌های محصول را انتخاب کنید.';

    rows.forEach(function (tr) { tr.classList.add('pp-attention'); });
    clearTimeout(hintTimer);
    hintTimer = setTimeout(function () {
      rows.forEach(function (tr) { tr.classList.remove('pp-attention'); });
      if (hint.parentNode) { hint.parentNode.removeChild(hint); }
    }, 5000);

    /* Mbar / dock must never yank the page to the buy box. */
    if (!opts.noScroll) {
      var target = rows[0] || table;
      target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
    }
    if (missing[0]) { try { missing[0].focus({ preventScroll: true }); } catch (e) { /* ignore */ } }
    return true;
  }

  function parseMoney(raw) {
    var s = String(raw == null ? '' : raw)
      .replace(/[۰-۹]/g, function (d) { return String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d)); })
      .replace(/[٠-٩]/g, function (d) { return String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)); })
      .replace(/[^\d.]/g, '');
    var n = parseFloat(s);
    return isNaN(n) ? 0 : n;
  }

  function toFaDigits(n) {
    return String(n).replace(/\d/g, function (d) {
      return '۰۱۲۳۴۵۶۷۸۹'.charAt(+d);
    });
  }

  /** Keep discount chip (left) in sync with current price (variations). */
  function syncBuyDiscount() {
    var rating = Q('.irantheme-physical-product .pp-buy .pp-buy-rating');
    var price = Q('.irantheme-physical-product .pp-buy .product-add-to-card-price') ||
      Q('.irantheme-physical-product .pp-price-row .product-add-to-card-price');
    if (!rating) { return; }
    var del = price && Q('del', price);
    var ins = price && Q('ins', price);
    var label = '';
    if (del && ins) {
      var regular = parseMoney(del.textContent);
      var sale = parseMoney(ins.textContent);
      if (regular > 0 && sale >= 0 && sale < regular) {
        label = toFaDigits(Math.round(100 - (sale / regular * 100))) + '٪';
      }
    }
    var chip = Q('.pp-buy-discount', rating);
    if (label) {
      if (!chip) {
        chip = document.createElement('span');
        chip.className = 'pp-buy-discount irantheme-woocommerce-products-discount';
        rating.appendChild(chip); /* last = physical left in RTL row */
      }
      chip.textContent = label;
      chip.hidden = false;
    } else if (chip) {
      chip.hidden = true;
      chip.textContent = '';
    }
  }

  function variationGuard() {
    document.addEventListener('click', function (e) {
      var btn = e.target.closest && e.target.closest('.irantheme-physical-product .single_add_to_cart_button');
      if (!btn || btn.classList.contains('outofstock') || !variationForm()) { return; }
      if (isBlocked(btn)) {
        e.preventDefault();
        e.stopImmediatePropagation();
        showHint();
      }
    }, true);
    var form = variationForm();
    if (form) {
      form.addEventListener('change', function () {
        var h = Q('.pp-select-hint', form);
        if (h && h.parentNode) { h.parentNode.removeChild(h); }
        QA('.pp-attention', form).forEach(function (tr) { tr.classList.remove('pp-attention'); });
        var cta = Q('.pp-buy .single_add_to_cart_button');
        if (cta && !isBlocked(cta)) {
          cta.classList.add('pp-cta-ready');
          window.setTimeout(function () { cta.classList.remove('pp-cta-ready'); }, 600);
        }
        window.setTimeout(syncBuyDiscount, 50);
      });
      if (window.jQuery) {
        window.jQuery(form).on('found_variation hide_variation reset_data', function () {
          window.setTimeout(syncBuyDiscount, 30);
        });
      }
    }
  }

  /* ---------- GeneSabz 3-col layout ---------- */
  function layoutPdp() {
    var wrap = Q('.irantheme-physical-product .irantheme-single-product-main-content-wrapper');
    var image = Q('.irantheme-physical-product .product-image');
    var info = Q('.irantheme-physical-product .pp-stage__identity .product-info') ||
      Q('.irantheme-physical-product .product-info');
    var summary = mainSummary();
    if (!summary) {
      summary = Q('.irantheme-physical-product .pp-buy > .irantheme-single-product-shopping-summary') ||
        Q('.irantheme-physical-product .irantheme-single-product-main-content-wrapper > .irantheme-single-product-shopping-summary:not(.irantheme-single-product-content-shopping-summary)');
    }
    if (!wrap || !image || !info || !summary) { return; }

    /* Prefer SSR wrappers from PHP — only build if missing (avoids FOUC). */
    var stage = Q('.pp-stage', wrap);
    var buy = Q('.pp-buy', wrap);
    if (!stage || !buy) {
      stage = document.createElement('div');
    stage.className = 'pp-stage';
      buy = document.createElement('div');
    buy.className = 'pp-buy';

    var identity = document.createElement('div');
    identity.className = 'pp-stage__identity';
    identity.appendChild(info);

    var gallery = document.createElement('div');
    gallery.className = 'pp-stage__gallery';
    gallery.appendChild(image);

      stage.appendChild(gallery);
    stage.appendChild(identity);
    buy.appendChild(summary);

      wrap.insertBefore(stage, wrap.firstChild);
      wrap.insertBefore(buy, stage.nextSibling);
    }

    var tools = null;
    var boxInner = null;
    Array.prototype.forEach.call(summary.children, function (child) {
      if (child.classList.contains('irantheme-single-product-shopping-summary-tools')) { tools = child; }
      if (child.classList.contains('irantheme-single-product-shopping-summary-wrapper')) { boxInner = child; }
    });
    if (tools && boxInner && tools.nextElementSibling !== boxInner) {
      summary.insertBefore(tools, boxInner);
    }

    /* Pull full PDP body into the grid so sticky buy rides until related. */
    var root = Q('.irantheme-physical-product');
    if (root) {
      var pull = [];
      Array.prototype.forEach.call(root.children, function (ch) {
        if (!ch || !ch.classList) { return; }
        if (
          ch.classList.contains('pp-trust-bar') ||
          ch.classList.contains('pp-doc') ||
          ch.classList.contains('irantheme-single-product-upsell-wrapper') ||
          ch.classList.contains('irantheme-single-product-content')
        ) {
          pull.push(ch);
        }
      });
      pull.forEach(function (ch) { wrap.appendChild(ch); });
    }

    var tabsEl = Q('.irantheme-single-product-content-tabs', wrap) ||
      Q('.irantheme-physical-product .irantheme-single-product-content-tabs');
    if (tabsEl) { tabsEl.classList.add('pp-specs'); }

    var box = Q('.irantheme-single-product-shopping-summary-wrapper', summary);
    var last = box && Q('.product-last-update', box);
    if (last && box) {
      last.classList.add('pp-meta-quiet');
      box.appendChild(last);
    }

    moveVariationsToBuy(info, buy);
    enhanceFeatures(info);

    if (Q('.pp-doc')) {
      document.body.classList.add('sp-pdp-has-doc');
      document.documentElement.classList.add('sp-pdp-has-doc');
    }
  }

  /**
   * GeneSabz: options + real Woo form live in sticky buy box.
   * Theme also prints a duplicate woocommerce_single_variation in the
   * sidebar — hide that shell and drive commerce from the moved form.
   */
  function moveVariationsToBuy(info, buy) {
    if (!info || !buy) { return; }
    var form = Q('form.variations_form', info) || Q('form.cart', info);
    var box = Q('.irantheme-single-product-shopping-summary-wrapper', buy);
    if (!form || !box || form.closest('.pp-buy')) { return; }

    var features = Q('.product-selected-features', box);
    var addCard = Q('.product-add-to-card', box);

    form.classList.add('pp-buy-form');

    if (features) {
      box.insertBefore(form, features.nextSibling);
    } else if (addCard) {
      box.insertBefore(form, addCard);
    } else {
      box.appendChild(form);
    }

    /* fold price + signals around the real form CTA */
    if (addCard) {
      var price = Q('.product-add-to-card-price', addCard);
      var stock = Q('.product-add-to-card-stock-status', addCard);
      var signals = Q('.sp-pdp-buy-signals', addCard) || Q('.sp-pdp-buy-signals', box);
      var wrap = Q('.single_variation_wrap, .woocommerce-variation-add-to-cart', form) || form;

      if (price) { form.insertBefore(price, wrap); }
      if (stock) { form.insertBefore(stock, wrap); }

      /* hide duplicate sidebar button from do_action('woocommerce_single_variation') */
      var dupBtn = Q('.product-add-to-card-button', addCard);
      if (dupBtn) { dupBtn.classList.add('pp-dup-cart-hide'); }

      if (signals && wrap.parentNode) {
        wrap.parentNode.insertBefore(signals, wrap.nextSibling);
      }
    }
  }

  function enhanceFeatures(info) {
    var short = Q('.product-short-description', info);
    if (!short || Q('.pp-features', info)) { return; }
    var existingUl = Q('ul', short);
    if (existingUl) {
      existingUl.classList.add('pp-features');
      short.classList.add('pp-features-wrap');
      return;
    }
    var html = short.innerHTML.replace(/<br\s*\/?>/gi, '\n');
    var tmp = document.createElement('div');
    tmp.innerHTML = html;
    var text = (tmp.textContent || '').trim();
    if (!text) { return; }
    var lines = text.split(/\n+/).map(function (l) { return l.replace(/^[\s\u2022\-\*]+/, '').trim(); }).filter(Boolean);
    if (lines.length < 2) {
      short.classList.add('pp-features-wrap', 'pp-features-wrap--prose');
      return;
    }
    var ul = document.createElement('ul');
    ul.className = 'pp-features';
    lines.forEach(function (line) {
      var li = document.createElement('li');
      li.textContent = line;
      ul.appendChild(li);
    });
    short.innerHTML = '';
    short.classList.add('pp-features-wrap');
    short.appendChild(ul);
  }

  /* ---------- doc marker (about box; no section nav) ---------- */
  function docNav() {
    /* Reviews / sticky nav removed — about-only document needs no in-page tabs. */
  }

  /**
   * About-product fold: clamp tall copy, fade-to-clear gradient,
   * drawer toggle «مشاهده بیشتر / کمتر».
   */
  function readMore() {
    var LIMIT = 168; /* ~6–7 lines at 14px / line-height 2 */

    QA('.irantheme-physical-product .pp-doc__prose').forEach(function (prose) {
      if (prose.getAttribute('data-pp-more')) { return; }
      if (prose.closest('.pp-doc__fold')) { return; }
      /* SEO copy clipped from UI — do not build «مشاهده بیشتر» chrome */
      if (prose.closest('[data-sp-ui-hidden], .sp-crawler-copy')) { return; }

      /* measure natural height before clamping */
      var natural = prose.scrollHeight;
      if (natural <= LIMIT + 24) { return; }

      prose.setAttribute('data-pp-more', '1');

      var fold = document.createElement('div');
      fold.className = 'pp-doc__fold is-collapsed';
      prose.parentNode.insertBefore(fold, prose);
      fold.appendChild(prose);

      var fade = document.createElement('div');
      fade.className = 'pp-doc__fade';
      fade.setAttribute('aria-hidden', 'true');
      fold.appendChild(fade);

      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'pp-doc__more';
      btn.setAttribute('aria-expanded', 'false');
      btn.innerHTML = '<span class="pp-doc__more-label">مشاهده بیشتر</span><i class="fa-light fa-angle-down" aria-hidden="true"></i>';
      fold.appendChild(btn);

      function setOpen(open) {
        fold.classList.toggle('is-collapsed', !open);
        fold.classList.toggle('is-expanded', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        var label = Q('.pp-doc__more-label', btn);
        if (label) { label.textContent = open ? 'نمایش کمتر' : 'مشاهده بیشتر'; }
        var icon = Q('i', btn);
        if (icon) {
          icon.className = open ? 'fa-light fa-angle-up' : 'fa-light fa-angle-down';
        }
      }

      btn.addEventListener('click', function () {
        setOpen(fold.classList.contains('is-collapsed'));
      });
    });
  }

  /**
   * Bottom dock: shows when the buy section leaves the viewport
   * (desktop + mobile). Buy card itself stays static.
   */
  function scrollDockBar() {
    if (Q('.pp-mbar')) { return; }
    var buy = Q('.irantheme-physical-product .pp-buy');
    if (!buy) { return; }
    var price = Q('.pp-price-row .product-add-to-card-price, .product-add-to-card-price', buy) ||
      Q('.product-add-to-card-price', buy);
    var cta = Q('.pp-buy .single_add_to_cart_button:not(.outofstock)', buy) ||
      Q('.pp-buy button.single_add_to_cart_button', buy) ||
      Q('.pp-buy .single_add_to_cart_button', buy);
    if (!price) { return; }

    var titleEl = Q('.irantheme-physical-product .product-title');
    var imgEl = Q('.irantheme-physical-product .pp-stage__gallery .product-image-hero-item img') ||
      Q('.irantheme-physical-product .product-image-hero-item img') ||
      Q('.irantheme-physical-product .product-image img');

    var bar = document.createElement('div');
    bar.className = 'pp-mbar';
    bar.setAttribute('role', 'region');
    bar.setAttribute('aria-label', 'خرید سریع');
    bar.hidden = true;

    var inner = document.createElement('div');
    inner.className = 'pp-mbar__inner';

    var product = document.createElement('div');
    product.className = 'pp-mbar__product';

    if (imgEl && imgEl.src) {
      var thumb = document.createElement('img');
      thumb.className = 'pp-mbar__thumb';
      thumb.src = imgEl.currentSrc || imgEl.src;
      thumb.alt = '';
      thumb.width = 56;
      thumb.height = 56;
      thumb.decoding = 'async';
      product.appendChild(thumb);
    }

    var info = document.createElement('div');
    info.className = 'pp-mbar__info';
    if (titleEl) {
      var t = document.createElement('div');
      t.className = 'pp-mbar__title';
      t.textContent = (titleEl.textContent || '').trim();
      info.appendChild(t);
    }
    product.appendChild(info);
    inner.appendChild(product);

    var actions = document.createElement('div');
    actions.className = 'pp-mbar__actions';

    var priceSlot = document.createElement('div');
    priceSlot.className = 'pp-mbar__price';
    priceSlot.innerHTML = price.innerHTML;
    actions.appendChild(priceSlot);

    var api = qtyApi();
    var stepper = api ? api.create({ size: 'sm', qty: 1 }) : null;
    if (stepper) {
      stepper.classList.add('pp-mbar__qty');
      stepper.hidden = true;
      actions.appendChild(stepper);
    }

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'pp-mbar__cta';
    btn.innerHTML = '<span class="pp-mbar__cta-label">افزودن به سبد خرید</span>';

    var mbarHint = document.createElement('div');
    mbarHint.className = 'pp-mbar__hint';
    mbarHint.hidden = true;
    mbarHint.setAttribute('role', 'alert');

    function flashMbarHint(msg) {
      mbarHint.textContent = msg || 'لطفاً ابتدا گزینه‌های محصول را انتخاب کنید.';
      mbarHint.hidden = false;
      window.clearTimeout(flashMbarHint._t);
      flashMbarHint._t = window.setTimeout(function () {
        mbarHint.hidden = true;
      }, 4000);
    }

    function mbarAdd() {
      if (commerceCtl) {
        if (commerceCtl.needsVariation()) {
          showHint({ noScroll: true });
          flashMbarHint();
          return;
        }
        /* pushCart() toggles .is-loading on CTA + stepper + mbar */
        commerceCtl.add().finally(syncMbarCommerce);
        return;
      }
      /* Fallback if buy-box commerce not ready yet — AJAX only, never scroll. */
      var pid = productIdFrom(cta) || productIdFrom(buy) ||
        (window.spProductPage && window.spProductPage.productId);
      if (!pid) { return; }
      btn.classList.add('is-loading');
      btn.disabled = true;
      setCartQty(pid, 1, window.spProductPage && window.spProductPage.cartKey)
        .then(function () {
          if (commerceCtl) { commerceCtl.sync(); }
          syncMbarCommerce();
        })
        .finally(function () {
          btn.classList.remove('is-loading');
          btn.disabled = false;
        });
    }

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      mbarAdd();
    });
    actions.appendChild(btn);
    inner.appendChild(actions);
    bar.appendChild(inner);
    bar.appendChild(mbarHint);
    document.body.appendChild(bar);

    function cartQtyNow() {
      if (commerceCtl) { return commerceCtl.qty(); }
      return (window.spProductPage && parseQty(window.spProductPage.cartQty)) || 0;
    }

    function syncMbarCommerce() {
      var q = cartQtyNow();
      var inCart = q > 0;
      if (stepper && api) {
        api.paint(stepper, Math.max(1, q || 1));
        stepper.hidden = !inCart;
      }
      btn.hidden = !!inCart;
      bar.classList.toggle('is-in-cart', inCart);
      bar.classList.toggle('is-empty', !inCart);
    }

    if (stepper && api) {
      api.bind(stepper, {
        onInc: function () {
          if (commerceCtl) { commerceCtl.inc().finally(syncMbarCommerce); }
        },
        onDec: function () {
          if (commerceCtl) { commerceCtl.dec().finally(syncMbarCommerce); }
        },
        onRemove: function () {
          if (commerceCtl) { commerceCtl.remove().finally(syncMbarCommerce); }
        }
      });
    }

    if (window.MutationObserver) {
      new MutationObserver(function () {
        priceSlot.innerHTML = price.innerHTML;
        syncMbarCommerce();
      }).observe(price, { childList: true, subtree: true, characterData: true });
      var commerce = Q('.pp-price-row', buy);
      if (commerce) {
        new MutationObserver(syncMbarCommerce).observe(commerce, {
          attributes: true,
          attributeFilter: ['class'],
          subtree: true,
          childList: true,
          characterData: true
        });
      }
    }
    syncMbarCommerce();
    /* MutationObserver above already keeps the dock in sync — no polling. */

    function setVisible(on) {
      bar.hidden = !on;
      document.documentElement.classList.toggle('pp-mbar-on', !!on);
      if (on) { syncMbarCommerce(); }
    }

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        var en = entries[0];
        if (!en) { return; }
        /* show dock once buy card is mostly off-screen */
        setVisible(!en.isIntersecting || en.intersectionRatio < 0.15);
      }, { threshold: [0, 0.15, 0.4], rootMargin: '-80px 0px 0px 0px' }).observe(buy);
    } else {
      setVisible(false);
    }
  }

  /**
   * Stop page-builder from wiping SSR homepage rails (bestsellers / offers).
   * Do NOT re-init or retune those Swipers here — theme + slider-peek +
   * category-card-fixed own them; PDP overrides were scratching the look.
   */
  function protectDiscovery() {
    QA('.pp-discovery .pp-home-section, .pp-related-rail').forEach(function (el) {
      el.setAttribute('data-pp-static', '1');
      ['data-ajax', 'data-page', 'data-type', 'data-value', 'data-section', 'data-json'].forEach(function (a) {
        el.removeAttribute(a);
        QA('[' + a + ']', el).forEach(function (n) { n.removeAttribute(a); });
      });
      QA('.irantheme-woocommerce-ajax-categories-products-swiper', el).forEach(function (n) {
        n.classList.remove('irantheme-woocommerce-ajax-categories-products-swiper');
        n.classList.add('pp-discovery-swiper');
      });
    });
  }

  /** Wake home-section / related category swipers only if the theme never did. */
  function discoverySwipers() {
    var nodes = QA(
      '.irantheme-physical-product .pp-discovery .irantheme-woocommerce-products-post-swiper,' +
      '.irantheme-physical-product .pp-related-rail .irantheme-woocommerce-products-post-swiper,' +
      '.irantheme-physical-product .pp-discovery .irantheme-woocommerce-product-swiper,' +
      '.irantheme-physical-product .pp-related-rail .irantheme-woocommerce-product-swiper'
    );
    if (!nodes.length || typeof window.Swiper !== 'function') { return; }
    nodes.forEach(function (el) {
      if (el.swiper) {
        try { el.swiper.update(); } catch (err) { /* ignore */ }
        return;
      }
      try {
        // eslint-disable-next-line no-new
        new window.Swiper(el, {
          slidesPerView: 'auto',
          spaceBetween: 16,
          watchOverflow: true,
          observer: true,
          observeParents: true,
          navigation: {
            nextEl: el.querySelector('.swiper-button-next'),
            prevEl: el.querySelector('.swiper-button-prev')
          }
        });
      } catch (err) { /* ignore */ }
    });
  }

  /**
   * Rails boot gate: related / bestsellers / offers stay behind a skeleton
   * until Swiper (and offers-frame) finish — kills the jumble flash on load.
   */
  function markRailReady(el) {
    if (!el || el.classList.contains('is-rails-ready')) { return; }
    el.classList.add('is-rails-ready');
  }

  function sectionSwipersReady(section) {
    var swipers = QA('.swiper', section).filter(function (el) {
      /* Ignore nested tiny swipers that are not product rails. */
      return el.classList.contains('irantheme-woocommerce-product-swiper') ||
        el.classList.contains('irantheme-woocommerce-products-post-swiper') ||
        el.classList.contains('pp-discovery-swiper');
    });
    if (!swipers.length) {
      swipers = QA('.swiper', section);
    }
    if (!swipers.length) { return true; }
    return swipers.every(function (el) {
      return !!(el.swiper || el.classList.contains('swiper-initialized'));
    });
  }

  function sectionOffersReady(section) {
    var offers = section.classList.contains('irantheme-woocommerce-offers-products-section')
      ? section
      : Q('.irantheme-woocommerce-offers-products-section', section);
    if (!offers) { return true; }
    return offers.classList.contains('of-ready');
  }

  function tickRailsBoot(force) {
    var sections = QA(
      '.irantheme-physical-product .pp-related-rail,' +
      '.irantheme-physical-product .pp-discovery .pp-home-section'
    );
    if (!sections.length) {
      document.documentElement.classList.add('pp-rails-ready');
      return true;
    }
    var pending = 0;
    sections.forEach(function (section) {
      if (section.classList.contains('is-rails-ready')) { return; }
      if (force || (sectionSwipersReady(section) && sectionOffersReady(section))) {
        markRailReady(section);
      } else {
        pending += 1;
      }
    });
    if (!pending) {
      document.documentElement.classList.add('pp-rails-ready');
      return true;
    }
    return false;
  }

  function bootRails() {
    var tries = 0;
    function step() {
      tries += 1;
      discoverySwipers();
      if (tickRailsBoot(false)) { return; }
      if (tries >= 35) {
        tickRailsBoot(true); /* ~3.5s safety: never leave skeleton forever */
        return;
      }
      window.setTimeout(step, 100);
    }
    step();
  }

  /** Reviews list: show 5, reveal the rest via «نمایش بیشتر». */
  function reviewsMore() {
    var btn = Q('[data-pp-reviews-more]');
    var list = Q('[data-pp-reviews-list]');
    if (!btn || !list) { return; }
    var label = Q('.pp-reviews__more-label', btn);
    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') === 'true';
      var extras = list.querySelectorAll('.pp-reviews__item.is-extra');
      if (open) {
        extras.forEach(function (el) { el.hidden = true; });
        btn.setAttribute('aria-expanded', 'false');
        if (label) { label.textContent = 'نمایش بیشتر'; }
      } else {
        extras.forEach(function (el) { el.hidden = false; });
        btn.setAttribute('aria-expanded', 'true');
        if (label) { label.textContent = 'نمایش کمتر'; }
      }
    });
  }
  function init() {
    protectDiscovery();
    try { layoutPdp(); } catch (err) { /* keep commerce alive */ }
    stickyOffset();
    quantity();
    try { priceQtyRow(); } catch (err) { /* ignore */ }
    syncBuyDiscount();
    docNav();
    readMore();
    reviewsMore();
    scrollDockBar();
    variationGuard();
    discoverySwipers();
    bootRails();
    var priceWatch = Q('.irantheme-physical-product .pp-buy .product-add-to-card-price');
    if (priceWatch && window.MutationObserver) {
      new MutationObserver(function () { syncBuyDiscount(); })
        .observe(priceWatch, { childList: true, subtree: true, characterData: true });
    }
    window.setTimeout(protectDiscovery, 0);
    window.setTimeout(discoverySwipers, 400);
    window.setTimeout(discoverySwipers, 1200);
    /* Mark ready after first paint enhancements — no opacity flash. */
    requestAnimationFrame(function () {
      document.documentElement.classList.add('pp-pdp');
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
