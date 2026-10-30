/**
 * Shared quantity stepper (.sp-qty).
 * One markup / look everywhere: PDP, sticky mbar, cart page, sidebar mini-cart.
 * Size via .sp-qty--sm | --md | --lg [+ --full].
 * At qty === 1 the dec control becomes trash.
 */
(function (window, document) {
	'use strict';

	var ICONS = {
		plus:
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>',
		minus:
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14"/></svg>',
		trash:
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16"/><path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/><path d="M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12"/><path d="M10 11v6M14 11v6"/></svg>'
	};

	var FA = '۰۱۲۳۴۵۶۷۸۹';

	function toFa(n) {
		return String(n).replace(/\d/g, function (d) {
			return FA.charAt(+d);
		});
	}

	function parseQty(raw) {
		var s = String(raw == null ? '' : raw)
			.replace(/[۰-۹]/g, function (d) {
				return String(FA.indexOf(d));
			})
			.replace(/[٠-٩]/g, function (d) {
				return String('٠١٢٣٤٥٦٧٨٩'.indexOf(d));
			})
			.replace(/[^\d]/g, '');
		var n = parseInt(s, 10);
		return isNaN(n) ? 0 : n;
	}

	function paint(root, qty) {
		if (!root) return;
		var n = Math.max(0, parseQty(qty));
		var val = root.querySelector('.sp-qty__val');
		var dec = root.querySelector('.sp-qty__dec');
		var show = Math.max(1, n || 1);
		if (val) val.textContent = toFa(show);
		if (!dec) return;
		var inc = root.querySelector('.sp-qty__inc');
		if (inc && !inc.innerHTML) inc.innerHTML = ICONS.plus;
		if (n <= 1) {
			dec.classList.add('is-trash');
			dec.innerHTML = ICONS.trash;
			dec.setAttribute('aria-label', 'حذف از سبد خرید');
		} else {
			dec.classList.remove('is-trash');
			dec.innerHTML = ICONS.minus;
			dec.setAttribute('aria-label', 'کاهش تعداد');
		}
	}

	/**
	 * @param {object} opts
	 * @param {string} [opts.size=md] sm|md|lg
	 * @param {boolean} [opts.full]
	 * @param {number} [opts.qty=1]
	 * @param {string} [opts.cartKey]
	 * @param {string} [opts.productId]
	 * @returns {HTMLElement}
	 */
	function create(opts) {
		opts = opts || {};
		var size = opts.size || 'md';
		var root = document.createElement('div');
		root.className = 'sp-qty sp-qty--' + size + (opts.full ? ' sp-qty--full' : '');
		root.setAttribute('role', 'group');
		root.setAttribute('aria-label', 'تعداد');
		root.setAttribute('data-sp-qty', '1');
		if (opts.cartKey) root.setAttribute('data-cart-key', opts.cartKey);
		if (opts.productId) root.setAttribute('data-product-id', String(opts.productId));

		var dec = document.createElement('button');
		dec.type = 'button';
		dec.className = 'sp-qty__btn sp-qty__dec';

		var val = document.createElement('span');
		val.className = 'sp-qty__val';

		var inc = document.createElement('button');
		inc.type = 'button';
		inc.className = 'sp-qty__btn sp-qty__inc';
		inc.setAttribute('aria-label', 'افزایش تعداد');
		inc.innerHTML = ICONS.plus;

		root.appendChild(dec);
		root.appendChild(val);
		root.appendChild(inc);
		paint(root, opts.qty == null ? 1 : opts.qty);
		return root;
	}

	function bind(root, handlers) {
		if (!root || root.getAttribute('data-sp-qty-bound')) return;
		root.setAttribute('data-sp-qty-bound', '1');
		handlers = handlers || {};
		var dec = root.querySelector('.sp-qty__dec');
		var inc = root.querySelector('.sp-qty__inc');
		if (inc) {
			inc.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				if (handlers.onInc) handlers.onInc(e, root);
			});
		}
		if (dec) {
			dec.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var qty = parseQty((root.querySelector('.sp-qty__val') || {}).textContent);
				if (qty <= 1) {
					if (handlers.onRemove) handlers.onRemove(e, root);
					else if (handlers.onDec) handlers.onDec(e, root);
				} else if (handlers.onDec) {
					handlers.onDec(e, root);
				}
			});
		}
	}

	function findQtyInput(wrap) {
		if (!wrap) return null;
		return (
			wrap.querySelector('input.sp-qty-input') ||
			wrap.querySelector('input.qty') ||
			wrap.querySelector('input.irantheme-input-text-quantity') ||
			wrap.querySelector('input[name*="quantity"]') ||
			wrap.querySelector('input[type="number"]')
		);
	}

	function syncInput(input, n) {
		if (!input) return;
		input.value = String(n);
		input.setAttribute('value', String(n));
		try {
			input.dispatchEvent(new Event('change', { bubbles: true }));
			input.dispatchEvent(new Event('input', { bubbles: true }));
		} catch (err) {
			/* IE / old */
		}
	}

	/**
	 * Woo quantity-input.php wraps (.sp-qty-wrap): bind stepper ↔ hidden input.
	 */
	function enhanceWooWraps(root) {
		root = root || document;
		var wraps = root.querySelectorAll('[data-sp-qty-wrap], .sp-qty-wrap');
		Array.prototype.forEach.call(wraps, function (wrap) {
			/* PDP buy parks its own qty — leave it alone */
			if (wrap.closest('.pp-woo-qty-hid') || wrap.closest('.pp-qty-field')) return;
			if (wrap.closest('.irantheme-physical-product .pp-buy')) return;

			var stepper = wrap.querySelector('[data-sp-qty]');
			var input = findQtyInput(wrap);
			if (!stepper || !input) return;

			/* Ensure icons painted (PHP may leave trash/minus already set). */
			paint(stepper, parseQty(input.value) || 1);

			if (stepper.getAttribute('data-sp-qty-bound')) return;

			bind(stepper, {
				onInc: function () {
					var n = parseQty(input.value) + 1;
					var max = parseQty(input.getAttribute('max')) || 9999;
					if (max > 0) n = Math.min(n, max);
					paint(stepper, n);
					syncInput(input, n);
				},
				onDec: function () {
					var n = Math.max(1, parseQty(input.value) - 1);
					paint(stepper, n);
					syncInput(input, n);
				},
				onRemove: function () {
					var row = wrap.closest('tr.cart_item, tr, .woocommerce-cart-form__cart-item');
					var rm =
						row &&
						(row.querySelector('a.remove') ||
							row.querySelector('.product-remove a') ||
							row.querySelector('[data-product_id].remove'));
					if (rm) {
						rm.click();
						return;
					}
					/* Non-cart contexts: floor at 1 */
					paint(stepper, 1);
					syncInput(input, 1);
				}
			});
		});
	}

	/**
	 * Upgrade theme sidebar qty wrappers to .sp-qty--sm.
	 * Drives theme AJAX via the hidden legacy +/- buttons.
	 */
	function enhanceSidebar(root) {
		root = root || document;
		var wrappers = root.querySelectorAll(
			'.irantheme-woocommerce-cart-navigation-list-item-content-quantity .irantheme-quantity-input-wrapper:not(.sp-qty-host)'
		);
		Array.prototype.forEach.call(wrappers, function (wrap) {
			var minus = wrap.querySelector('.irantheme-quantity-minus');
			var plus = wrap.querySelector('.irantheme-quantity-plus');
			var display = wrap.querySelector('.irantheme-quantity-display');
			var key =
				(minus && minus.getAttribute('data-cart-item-key')) ||
				(plus && plus.getAttribute('data-cart-item-key')) ||
				'';
			var qty = parseQty(display && display.textContent);
			if (!key) return;

			var item = wrap.closest('.irantheme-woocommerce-cart-navigation-list-item');
			var stepper = create({ size: 'sm', qty: qty || 1, cartKey: key });
			wrap.classList.add('sp-qty-host');
			wrap.appendChild(stepper);

			bind(stepper, {
				onInc: function () {
					if (plus) plus.click();
				},
				onDec: function () {
					if (minus) minus.click();
				},
				onRemove: function () {
					var rm =
						item &&
						item.querySelector(
							'.irantheme-woocommerce-cart-navigation-list-item-remove-button'
						);
					if (rm) rm.click();
					else if (minus) minus.click();
				}
			});
		});
	}

	/**
	 * Cart page cells that still use old theme markup (no .sp-qty-wrap yet).
	 */
	function enhanceCartPage(root) {
		root = root || document;
		if (!document.body.classList.contains('woocommerce-cart')) return;
		var cells = root.querySelectorAll('td.product-quantity');
		Array.prototype.forEach.call(cells, function (cell) {
			if (cell.querySelector('[data-sp-qty]')) return;
			var input =
				cell.querySelector('input.qty') ||
				cell.querySelector('input[type="number"]') ||
				cell.querySelector('.irantheme-input-text-quantity');
			if (!input) return;
			var qty = parseQty(input.value) || 1;
			var stepper = create({ size: 'sm', qty: qty });
			cell.insertBefore(stepper, cell.firstChild);
			if (input.closest('.quantity')) {
				input.closest('.quantity').classList.add('sp-qty-replaced');
			} else {
				input.classList.add('sp-qty-replaced');
				input.style.display = 'none';
			}

			bind(stepper, {
				onInc: function () {
					var n = parseQty(input.value) + 1;
					var max = parseQty(input.getAttribute('max')) || 9999;
					if (max > 0) n = Math.min(n, max);
					paint(stepper, n);
					syncInput(input, n);
				},
				onDec: function () {
					var n = Math.max(1, parseQty(input.value) - 1);
					paint(stepper, n);
					syncInput(input, n);
				},
				onRemove: function () {
					var row = cell.closest('tr');
					var rm =
						row &&
						(row.querySelector('a.remove') ||
							row.querySelector('.product-remove a'));
					if (rm) {
						rm.click();
						return;
					}
					syncInput(input, 0);
					var form = cell.closest('form');
					if (form) {
						if (form.requestSubmit) form.requestSubmit();
						else form.submit();
					}
				}
			});
		});
	}

	/**
	 * Any leftover theme qty (not yet upgraded) → replace with .sp-qty.
	 */
	function enhanceLegacyQuantity(root) {
		root = root || document;
		var boxes = root.querySelectorAll(
			'.irantheme-single-product-quantity:not(.sp-qty-wrap):not(.sp-qty-replaced):not(.pp-woo-qty-hid)'
		);
		Array.prototype.forEach.call(boxes, function (box) {
			if (box.closest('.pp-buy') || box.closest('.pp-qty-field')) return;
			if (box.querySelector('[data-sp-qty]')) return;
			var input =
				box.querySelector('input.qty') ||
				box.querySelector('input.irantheme-input-text-quantity') ||
				box.querySelector('input[type="number"]') ||
				box.querySelector('input[type="text"]');
			if (!input) return;

			var qty = parseQty(input.value) || 1;
			var size = document.body.classList.contains('woocommerce-cart') ? 'sm' : 'md';
			var stepper = create({ size: size, qty: qty });
			box.classList.add('sp-qty-wrap', 'sp-qty-host');
			box.insertBefore(stepper, box.firstChild);

			Array.prototype.forEach.call(
				box.querySelectorAll(
					'.irantheme-quantity-btn, .irantheme-quantity-selector-minus, .irantheme-quantity-selector-plus'
				),
				function (btn) {
					btn.style.display = 'none';
				}
			);
			input.classList.add('sp-qty-input');

			bind(stepper, {
				onInc: function () {
					var n = parseQty(input.value) + 1;
					var max = parseQty(input.getAttribute('max')) || 9999;
					if (max > 0) n = Math.min(n, max);
					paint(stepper, n);
					syncInput(input, n);
				},
				onDec: function () {
					var n = Math.max(1, parseQty(input.value) - 1);
					paint(stepper, n);
					syncInput(input, n);
				},
				onRemove: function () {
					paint(stepper, 1);
					syncInput(input, 1);
				}
			});
		});
	}

	function boot(root) {
		root = root || document;
		enhanceWooWraps(root);
		enhanceSidebar(root);
		enhanceCartPage(root);
		enhanceLegacyQuantity(root);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			boot();
		});
	} else {
		boot();
	}
	window.addEventListener('load', function () {
		boot();
	});

	/* Theme reloads sidebar HTML via AJAX — re-enhance */
	if (window.MutationObserver) {
		var nav = document.querySelector('.irantheme-woocommerce-cart-navigation-wrapper');
		if (nav) {
			new MutationObserver(function () {
				boot(nav);
			}).observe(nav, { childList: true, subtree: true });
		}
		/* Cart form fragments */
		var cartForm = document.querySelector('.woocommerce-cart-form');
		if (cartForm) {
			new MutationObserver(function () {
				boot(cartForm);
			}).observe(cartForm, { childList: true, subtree: true });
		}
	}

	/* Woo updated_cart_totals / fragments */
	if (window.jQuery) {
		window.jQuery(document.body).on(
			'updated_cart_totals updated_wc_div wc_fragments_refreshed wc_fragments_loaded',
			function () {
				boot();
			}
		);
	}

	window.storeplusQty = {
		icons: ICONS,
		create: create,
		paint: paint,
		bind: bind,
		parseQty: parseQty,
		toFa: toFa,
		enhanceSidebar: enhanceSidebar,
		enhanceCartPage: enhanceCartPage,
		enhanceWooWraps: enhanceWooWraps,
		boot: boot
	};
})(window, document);
