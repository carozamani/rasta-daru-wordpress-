/*
 * Active filter chips above shop product cards — one chip per applied filter, × removes it.
 */
(function () {
	'use strict';

	var cfg = window.spActiveFilterChips || {};
	var labels = cfg.labels || {};
	var root;
	var track;
	var clearAllBtn;
	var syncTimer = null;
	var applying = false;
	/* null = force next render (do not use '' — empty collect() also yields ''). */
	var lastSig = null;

	function qs(sel, ctx) {
		return (ctx || document).querySelector(sel);
	}

	function qsa(sel, ctx) {
		return Array.prototype.slice.call((ctx || document).querySelectorAll(sel));
	}

	function uncheckNamed(name, value) {
		qsa('input[name="' + name + '"]').forEach(function (el) {
			if (value == null || el.value === value) {
				el.checked = false;
			}
		});
	}

	function textOf(input) {
		var label = input.closest('label');
		if (!label) {
			return (input.value || '').trim();
		}
		var textEl = label.querySelector('.sp-brand-chip__text, .irantheme-wc-archive-filter-content-label-text');
		if (textEl) {
			var clone = textEl.cloneNode(true);
			qsa('em, i, .irantheme-wc-archive-filter-content-label-toggler, .irantheme-wc-archive-filter-content-label-count', clone).forEach(function (n) {
				n.remove();
			});
			return (clone.textContent || '').replace(/\s+/g, ' ').trim();
		}
		return (label.textContent || '').replace(/\s+/g, ' ').trim() || (input.value || '').trim();
	}

	function formatNum(n) {
		try {
			return Number(n).toLocaleString('fa-IR');
		} catch (e) {
			return String(n);
		}
	}

	function priceState() {
		var from = qs('input[name="irantheme_price_filter_input_from"]');
		var to = qs('input[name="irantheme_price_filter_input_to"]');
		if (!from || !to) {
			return null;
		}
		var min = parseFloat(from.getAttribute('min'));
		var max = parseFloat(to.getAttribute('max'));
		var fv = parseFloat(from.value);
		var tv = parseFloat(to.value);
		if (isNaN(fv) || isNaN(tv) || isNaN(min) || isNaN(max)) {
			return null;
		}
		if (fv <= min && tv >= max) {
			return null;
		}
		return {
			from: fv,
			to: tv,
			min: min,
			max: max,
			fromEl: from,
			toEl: to
		};
	}

	function searchState() {
		var input = qs('#irantheme_wc_shop_search') || qs('input[name="search"].irantheme-wc-archive-search-field');
		if (!input) {
			return null;
		}
		var v = (input.value || '').trim();
		if (!v) {
			return null;
		}
		return { el: input, value: v };
	}

	function collect() {
		var items = [];
		var seen = {};

		qsa('input[name="product_category_item[]"]:checked').forEach(function (input) {
			var key = 'cat:' + input.value;
			if (seen[key]) {
				return;
			}
			seen[key] = true;
			items.push({
				key: key,
				type: 'checkbox',
				label: textOf(input) || input.value,
				input: input
			});
		});

		qsa('input[name="product_filter_item[]"]:checked').forEach(function (input) {
			var key = 'attr:' + input.value;
			if (seen[key]) {
				return;
			}
			seen[key] = true;
			items.push({
				key: key,
				type: 'checkbox',
				label: textOf(input) || input.value,
				input: input
			});
		});

		var stock = qs('input[name="product_status_in_stock"]:checked');
		if (stock) {
			items.push({
				key: 'status:in-stock',
				type: 'checkbox',
				label: labels.in_stock || 'فقط کالا های موجود',
				input: stock
			});
		}
		var sale = qs('input[name="product_status_on_sale"]:checked');
		if (sale) {
			items.push({
				key: 'status:on-sale',
				type: 'checkbox',
				label: labels.on_sale || 'فروش ویژه',
				input: sale
			});
		}

		var price = priceState();
		if (price) {
			items.push({
				key: 'price',
				type: 'price',
				label: (labels.price || 'قیمت') + ': از ' + formatNum(price.from) + ' تا ' + formatNum(price.to),
				price: price
			});
		}

		var search = searchState();
		if (search) {
			items.push({
				key: 'search',
				type: 'search',
				label: (labels.search || 'جستجو') + ': ' + search.value,
				search: search
			});
		}

		return items;
	}

	function themeApply() {
		var $ = window.jQuery;
		if (!$ || !$.fn) {
			return;
		}

		var probe =
			qs('input[name="product_status_in_stock"]') ||
			qs('input[name="product_status_on_sale"]') ||
			qs('input[name="product_category_item[]"]') ||
			qs('input[name="product_filter_item[]"]') ||
			qs('input[name="irantheme_price_filter_input_from"]');

		/*
		 * Parent theme: desktop auto-applies on change unless the input lives
		 * inside .irantheme-woocommerce-filter-navigation (mobile drawer).
		 * The Apply control always exists in DOM — do not prefer it on desktop
		 * or we skip the live-apply path.
		 */
		var inMobileNav = probe && probe.closest && probe.closest('.irantheme-woocommerce-filter-navigation');
		if (inMobileNav) {
			var $apply = $('.irantheme-woocommerce-filter-navigation-footer-apply');
			if ($apply.length) {
				$apply.trigger('click');
				return;
			}
		}

		if (probe) {
			$(probe).trigger('change');
			return;
		}

		var $applyFallback = $('.irantheme-woocommerce-filter-navigation-footer-apply');
		if ($applyFallback.length) {
			$applyFallback.trigger('click');
		}
	}

	function resetPrice(price) {
		if (!price) {
			return;
		}
		price.fromEl.value = String(price.min);
		price.toEl.value = String(price.max);
		var fromSlider = qs('#fromSliderRangeInput');
		var toSlider = qs('#toSliderRangeInput');
		if (fromSlider) {
			fromSlider.value = String(price.min);
		}
		if (toSlider) {
			toSlider.value = String(price.max);
		}
	}

	function finishApply() {
		lastSig = null;
		render();
		window.setTimeout(function () {
			applying = false;
			lastSig = null;
			render();
		}, 200);
	}

	function removeItem(item) {
		if (applying || !item) {
			return;
		}
		applying = true;
		try {
			if (item.type === 'checkbox' && item.input) {
				uncheckNamed(item.input.name, item.input.value);
			} else if (item.type === 'price') {
				resetPrice(item.price);
			} else if (item.type === 'search' && item.search && item.search.el) {
				item.search.el.value = '';
			}
			themeApply();
		} finally {
			finishApply();
		}
	}

	function clearAll() {
		if (applying) {
			return;
		}
		applying = true;
		try {
			uncheckNamed('product_category_item[]');
			uncheckNamed('product_filter_item[]');
			uncheckNamed('product_status_in_stock');
			uncheckNamed('product_status_on_sale');
			var price = priceState();
			if (price) {
				resetPrice(price);
			} else {
				var from = qs('input[name="irantheme_price_filter_input_from"]');
				var to = qs('input[name="irantheme_price_filter_input_to"]');
				if (from && from.getAttribute('min') != null) {
					from.value = from.getAttribute('min');
				}
				if (to && to.getAttribute('max') != null) {
					to.value = to.getAttribute('max');
				}
			}
			var search = searchState();
			if (search) {
				search.el.value = '';
			}
			themeApply();
		} finally {
			finishApply();
		}
	}

	function chipButton(item) {
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'sp-active-filters__chip';
		btn.setAttribute('data-sp-afc-key', item.key);
		btn.setAttribute('aria-label', (labels.remove || 'حذف فیلتر') + ': ' + item.label);

		var text = document.createElement('span');
		text.className = 'sp-active-filters__chip-label';
		text.textContent = item.label;
		btn.appendChild(text);

		var x = document.createElement('span');
		x.className = 'sp-active-filters__chip-x';
		x.setAttribute('aria-hidden', 'true');
		x.innerHTML = '<i class="fa-light fa-xmark"></i>';
		btn.appendChild(x);

		btn.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			removeItem(item);
		});
		return btn;
	}

	function sidebarFiltersVisible() {
		var priority = qs('.sp-shop-priority-filters');
		if (!priority) {
			return true;
		}
		var style = window.getComputedStyle(priority);
		return style.display !== 'none' && style.visibility !== 'hidden';
	}

	function mountActiveFiltersSlot() {
		if (!root) {
			return;
		}

		var statusBox = qs('.irantheme-wc-archive-filter-status');
		var mountParent = null;
		var mountBefore = null;
		var useSidebar = false;

		if (statusBox && statusBox.parentNode && sidebarFiltersVisible()) {
			mountParent = statusBox.parentNode;
			mountBefore = statusBox;
			useSidebar = true;
		} else {
			var topBar = qs('.irantheme-wc-archive-top-bar');
			if (topBar && topBar.parentNode) {
				mountParent = topBar.parentNode;
				mountBefore = topBar;
			}
		}

		if (!mountParent) {
			return;
		}

		var slot = qs('[data-sp-afc-slot]');
		if (!slot) {
			slot = document.createElement('div');
			slot.className = 'sp-active-filters-slot';
			slot.setAttribute('data-sp-afc-slot', '1');
		}

		slot.classList.toggle('sp-active-filters-slot--sidebar', useSidebar);

		if (slot.parentNode !== mountParent || slot.nextElementSibling !== mountBefore) {
			mountParent.insertBefore(slot, mountBefore);
		}

		if (root.parentNode !== slot) {
			slot.appendChild(root);
		}

		qsa('[data-sp-afc]').forEach(function (node) {
			if (node !== root && node.parentNode) {
				node.parentNode.removeChild(node);
			}
		});
	}

	function render() {
		if (!root || !track) {
			return;
		}
		mountActiveFiltersSlot();
		var items = collect();
		var sig = items
			.map(function (i) {
				return i.key + '=' + i.label;
			})
			.join('|');
		/* lastSig null forces a paint (e.g. after clear); '' is a valid empty state. */
		if (lastSig !== null && sig === lastSig) {
			return;
		}
		lastSig = sig;
		track.innerHTML = '';
		items.forEach(function (item) {
			track.appendChild(chipButton(item));
		});
		root.hidden = items.length === 0;
		if (clearAllBtn) {
			clearAllBtn.hidden = items.length < 2;
		}
	}

	function scheduleRender() {
		if (syncTimer) {
			window.clearTimeout(syncTimer);
		}
		syncTimer = window.setTimeout(render, 60);
	}

	function bind() {
		root = qs('[data-sp-afc]');
		if (!root) {
			return;
		}
		track = qs('[data-sp-afc-track]', root);
		clearAllBtn = qs('[data-sp-afc-clear-all]', root);
		if (!track) {
			return;
		}

		mountActiveFiltersSlot();

		if (clearAllBtn) {
			clearAllBtn.addEventListener('click', function (e) {
				e.preventDefault();
				clearAll();
			});
		}

		var archive = qs('.irantheme-wc-archive') || document.getElementById('irantheme-main') || document.body;
		archive.addEventListener('change', scheduleRender, true);
		archive.addEventListener('input', function (e) {
			var t = e.target;
			if (!t) {
				return;
			}
			if (
				t.name === 'irantheme_price_filter_input_from' ||
				t.name === 'irantheme_price_filter_input_to' ||
				t.id === 'irantheme_wc_shop_search' ||
				t.name === 'search'
			) {
				scheduleRender();
			}
		}, true);

		if (window.MutationObserver) {
			new MutationObserver(scheduleRender).observe(archive, { childList: true, subtree: true });
		}

		window.addEventListener('resize', scheduleRender);

		render();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', bind);
	} else {
		bind();
	}
})();
