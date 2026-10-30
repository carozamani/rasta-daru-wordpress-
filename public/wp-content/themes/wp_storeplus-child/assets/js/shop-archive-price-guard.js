/*
 * Prevent parent-theme archive filters from injecting price_from=NaN when
 * price inputs are absent (price filter disabled / not in DOM).
 */
(function () {
	'use strict';

	var PRICE_KEYS = ['price_from', 'price_to'];

	function isBadPrice(v) {
		if (v == null || v === '') {
			return false;
		}
		return v === 'NaN' || v === 'undefined' || Number.isNaN(Number(v));
	}

	function scrubSearch(search) {
		if (!search) {
			return null;
		}
		try {
			var u = new URL(search.charAt(0) === '?' ? search : '?' + search, window.location.origin);
			var changed = false;
			PRICE_KEYS.forEach(function (k) {
				var v = u.searchParams.get(k);
				if (v != null && isBadPrice(v)) {
					u.searchParams.delete(k);
					changed = true;
				}
			});
			return changed ? u.search : null;
		} catch (e) {
			return null;
		}
	}

	function scrubUrlString(url) {
		if (typeof url !== 'string' || !url) {
			return url;
		}
		try {
			var abs = new URL(url, window.location.origin);
			var next = scrubSearch(abs.search);
			if (next == null) {
				return url;
			}
			abs.search = next;
			/* Keep relative form when input was relative. */
			if (url.indexOf('://') === -1) {
				return abs.pathname + abs.search + abs.hash;
			}
			return abs.toString();
		} catch (e) {
			return url;
		}
	}

	function patchHistory() {
		if (!window.history || history.__spPriceGuard) {
			return;
		}
		history.__spPriceGuard = true;
		['pushState', 'replaceState'].forEach(function (method) {
			var orig = history[method];
			if (typeof orig !== 'function') {
				return;
			}
			history[method] = function (state, title, url) {
				if (typeof url === 'string') {
					url = scrubUrlString(url);
				}
				return orig.call(history, state, title, url);
			};
		});
	}

	/**
	 * When price inputs are missing, seed hidden 0…max so parent theme's
	 * addURLFilterParameter treats the range as "full" and skips writing prices.
	 */
	function ensurePriceInputs() {
		if (document.querySelector('input[name="irantheme_price_filter_input_from"]')) {
			return;
		}
		var host =
			document.querySelector('.irantheme-wc-archive-filter.filter-enable') ||
			document.querySelector('.irantheme-wc-archive-main-filter') ||
			document.querySelector('.irantheme-wc-archive');
		if (!host) {
			return;
		}

		var max = '999999999';
		var from = document.createElement('input');
		from.type = 'hidden';
		from.name = 'irantheme_price_filter_input_from';
		from.id = 'irantheme_price_filter_input_from';
		from.value = '0';
		from.setAttribute('min', '0');
		from.setAttribute('max', max);
		from.setAttribute('data-sp-price-guard', '1');

		var to = document.createElement('input');
		to.type = 'hidden';
		to.name = 'irantheme_price_filter_input_to';
		to.id = 'irantheme_price_filter_input_to';
		to.value = max;
		to.setAttribute('min', '1');
		to.setAttribute('max', max);
		to.setAttribute('data-sp-price-guard', '1');

		host.appendChild(from);
		host.appendChild(to);
	}

	function reapplyIfNeeded(cleaned) {
		if (!cleaned) {
			return;
		}
		var $ = window.jQuery;
		if (!$ || !$.fn) {
			return;
		}
		/* Prefer theme Apply control (handler lives on the wrapper). */
		var $apply = $('.irantheme-woocommerce-filter-navigation-footer-apply');
		if ($apply.length) {
			$apply.trigger('click');
			return;
		}
		var probe =
			document.querySelector('input[name="product_status_in_stock"]') ||
			document.querySelector('input[name="irantheme_price_filter_input_from"]');
		if (probe) {
			$(probe).trigger('change');
		}
	}

	function cleanCurrentUrl() {
		var next = scrubSearch(window.location.search || '');
		if (next == null) {
			return false;
		}
		var url = window.location.pathname + next + window.location.hash;
		window.history.replaceState(window.history.state, '', url);
		return true;
	}

	function boot() {
		patchHistory();
		ensurePriceInputs();
		var cleaned = cleanCurrentUrl();
		if (cleaned) {
			window.setTimeout(function () {
				reapplyIfNeeded(true);
			}, 50);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
