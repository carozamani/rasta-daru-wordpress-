/*
 * Mobile shop filter chips → bottom-sheet modals → theme AJAX apply + active chips.
 */
(function () {
	'use strict';

	var MQ = '(max-width: 991px)';
	var cfg = window.spMobileFilterChips || {};
	var titles = cfg.titles || {
		category: 'دسته‌بندی',
		price: 'محدوده قیمت',
		status: 'وضعیت',
		brand: 'برند'
	};

	var overlay;
	var sheet;
	var bodyEl;
	var titleEl;
	var openKey = null;
	var parking = null; /* { el, parent, next } */

	var SELECTORS = {
		category: '.irantheme-wc-archive-filter-category',
		price: '.irantheme-wc-archive-filter-price',
		status: '.irantheme-wc-archive-filter-status',
		brand: '.sp-brand-chips.sp-brand-filter, .sp-brand-chips'
	};

	function isMobile() {
		return window.matchMedia(MQ).matches;
	}

	function qs(sel, root) {
		return (root || document).querySelector(sel);
	}

	function qsa(sel, root) {
		return Array.prototype.slice.call((root || document).querySelectorAll(sel));
	}

	function findPanel(key) {
		var sel = SELECTORS[key];
		if (!sel) {
			return null;
		}
		/* Prefer panel still in its home (not already inside our sheet). */
		var nodes = qsa(sel);
		for (var i = 0; i < nodes.length; i++) {
			if (!bodyEl || !bodyEl.contains(nodes[i])) {
				return nodes[i];
			}
		}
		return nodes[0] || null;
	}

	function park(el) {
		if (!el) {
			return null;
		}
		return {
			el: el,
			parent: el.parentNode,
			next: el.nextSibling
		};
	}

	function restore() {
		if (!parking || !parking.el || !parking.parent) {
			parking = null;
			return;
		}
		if (parking.next && parking.next.parentNode === parking.parent) {
			parking.parent.insertBefore(parking.el, parking.next);
		} else {
			parking.parent.appendChild(parking.el);
		}
		parking = null;
	}

	function stopLiveApply(e) {
		/* Parent theme auto-applies on change unless inside filter-navigation.
		   While the sheet is open we defer apply until the user taps Apply. */
		e.stopPropagation();
	}

	function openModal(key) {
		if (!isMobile() || !overlay || !bodyEl) {
			return;
		}
		var panel = findPanel(key);
		if (!panel) {
			return;
		}

		if (openKey) {
			closeModal(false);
		}

		openKey = key;
		parking = park(panel);
		titleEl.textContent = titles[key] || 'فیلتر';
		bodyEl.appendChild(panel);
		overlay.hidden = false;
		document.documentElement.classList.add('sp-mfc-open');
		sheet.focus();

		/* Hide parent theme filter drawer chrome if somehow open. */
		var nav = qs('.irantheme-woocommerce-filter-navigation');
		if (nav) {
			nav.classList.remove('active');
		}
	}

	function closeModal(restorePanel) {
		if (restorePanel !== false) {
			restore();
		} else {
			restore();
		}
		openKey = null;
		if (overlay) {
			overlay.hidden = true;
		}
		document.documentElement.classList.remove('sp-mfc-open');
		syncActive();
	}

	function clearCurrent() {
		if (!openKey || !bodyEl) {
			return;
		}
		if (openKey === 'category') {
			qsa('input[name="product_category_item[]"]', bodyEl).forEach(function (i) {
				i.checked = false;
			});
		} else if (openKey === 'brand') {
			qsa('input[name="product_filter_item[]"]', bodyEl).forEach(function (i) {
				i.checked = false;
			});
		} else if (openKey === 'status') {
			qsa('input[name="product_status_in_stock"], input[name="product_status_on_sale"]', bodyEl).forEach(function (i) {
				i.checked = false;
			});
		} else if (openKey === 'price') {
			var from = qs('input[name="irantheme_price_filter_input_from"]', bodyEl);
			var to = qs('input[name="irantheme_price_filter_input_to"]', bodyEl);
			var fromSlider = qs('#fromSliderRangeInput', bodyEl);
			var toSlider = qs('#toSliderRangeInput', bodyEl);
			if (from && from.getAttribute('min') != null) {
				from.value = from.getAttribute('min');
			}
			if (to && to.getAttribute('max') != null) {
				to.value = to.getAttribute('max');
			}
			if (fromSlider && fromSlider.getAttribute('min') != null) {
				fromSlider.value = fromSlider.getAttribute('min');
			}
			if (toSlider && toSlider.getAttribute('max') != null) {
				toSlider.value = toSlider.getAttribute('max');
			}
		}
	}

	function themeApply() {
		var $ = window.jQuery;
		if ($ && $.fn) {
			var $btn = $('.irantheme-woocommerce-filter-navigation-footer-apply a');
			if ($btn.length) {
				$btn.trigger('click');
				return;
			}
		}
		/* Fallback: nudge a watched input so detectsChangesFilters runs. */
		var probe = qs('input[name="irantheme_price_filter_input_from"]') ||
			qs('input[name="product_status_in_stock"]') ||
			qs('input[name="product_filter_item[]"]');
		if (probe && $) {
			$(probe).trigger('change');
		}
	}

	function applyAndClose() {
		/* Restore panels first so parent selectors (filter-enable / priority) still match if needed,
		   then apply — inputs stay in document either way. */
		var key = openKey;
		restore();
		openKey = null;
		if (overlay) {
			overlay.hidden = true;
		}
		document.documentElement.classList.remove('sp-mfc-open');
		themeApply();
		syncActive();
		/* Re-mark the chip that was just applied after AJAX settles. */
		window.setTimeout(syncActive, 400);
		return key;
	}

	function priceActive() {
		var from = qs('input[name="irantheme_price_filter_input_from"]');
		var to = qs('input[name="irantheme_price_filter_input_to"]');
		if (!from || !to) {
			return false;
		}
		var min = parseFloat(from.getAttribute('min'));
		var max = parseFloat(to.getAttribute('max'));
		var fv = parseFloat(from.value);
		var tv = parseFloat(to.value);
		if (isNaN(fv) || isNaN(tv)) {
			return false;
		}
		return fv > min || tv < max;
	}

	function chipActive(key) {
		if (key === 'category') {
			return qsa('input[name="product_category_item[]"]:checked').length > 0;
		}
		if (key === 'brand') {
			return qsa('.sp-brand-chips input[name="product_filter_item[]"]:checked').length > 0;
		}
		if (key === 'status') {
			return qsa('input[name="product_status_in_stock"]:checked, input[name="product_status_on_sale"]:checked').length > 0;
		}
		if (key === 'price') {
			return priceActive();
		}
		return false;
	}

	function syncActive() {
		qsa('.sp-mfc-chip[data-sp-mfc-open]').forEach(function (btn) {
			var key = btn.getAttribute('data-sp-mfc-open');
			var on = chipActive(key);
			btn.classList.toggle('is-active', on);
			btn.setAttribute('aria-pressed', on ? 'true' : 'false');
		});
	}

	function bind() {
		overlay = qs('[data-sp-mfc-overlay]');
		if (!overlay) {
			return;
		}
		/* Fixed sheet must escape archive overflow / transform contexts. */
		if (overlay.parentNode !== document.body) {
			document.body.appendChild(overlay);
		}
		sheet = qs('.sp-mfc-sheet', overlay);
		bodyEl = qs('[data-sp-mfc-body]', overlay);
		titleEl = qs('#sp-mfc-title', overlay);
		if (!sheet || !bodyEl || !titleEl) {
			return;
		}

		document.addEventListener('click', function (e) {
			var openBtn = e.target.closest && e.target.closest('[data-sp-mfc-open]');
			if (openBtn) {
				if (!isMobile()) {
					return;
				}
				e.preventDefault();
				openModal(openBtn.getAttribute('data-sp-mfc-open'));
				return;
			}
			if (e.target.closest && e.target.closest('[data-sp-mfc-close]')) {
				e.preventDefault();
				closeModal(true);
				return;
			}
			if (e.target.closest && e.target.closest('[data-sp-mfc-clear]')) {
				e.preventDefault();
				clearCurrent();
				return;
			}
			if (e.target.closest && e.target.closest('[data-sp-mfc-apply]')) {
				e.preventDefault();
				applyAndClose();
				return;
			}
			if (e.target === overlay) {
				closeModal(true);
			}
		});

		sheet.addEventListener('change', stopLiveApply, false);
		sheet.addEventListener('keyup', stopLiveApply, false);
		sheet.addEventListener('input', stopLiveApply, false);

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && openKey) {
				closeModal(true);
			}
		});

		window.addEventListener('resize', function () {
			if (!isMobile() && openKey) {
				closeModal(true);
			}
			syncActive();
		});

		/* After theme AJAX replaces product grid, re-sync chip states. */
		var archive = qs('.irantheme-wc-archive');
		if (archive && window.MutationObserver) {
			var t = null;
			new MutationObserver(function () {
				if (t) {
					window.clearTimeout(t);
				}
				t = window.setTimeout(syncActive, 80);
			}).observe(archive, { childList: true, subtree: true });
		}

		syncActive();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', bind);
	} else {
		bind();
	}
})();
