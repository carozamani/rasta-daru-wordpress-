/*
 * Bestsellers placement — legacy safety net.
 * Primary path: inc/home-section-order.php (server-side section order).
 * No-ops when body.sp-php-home-order is present or already in place.
 */
(function () {
	'use strict';

	if (document.body && document.body.classList.contains('sp-php-home-order')) {
		return;
	}

	function headingText(section) {
		var h = section.querySelector('h1, h2, h3, h4, [class*="heading-title"], [class*="section-title"]');
		return h ? h.textContent.replace(/\s+/g, ' ').trim() : '';
	}

	function findLatest() {
		var list = document.querySelectorAll('.irantheme-woocommerce-products-section');
		var plain = [];
		for (var i = 0; i < list.length; i++) {
			var s = list[i];
			if (/offers-products|category-products|ajax-categories|hotspot/.test(s.className)) {
				continue;
			}
			if (headingText(s).indexOf('آخرین محصولات') !== -1) {
				return s;
			}
			plain.push(s);
		}
		return plain.length ? plain[0] : null;
	}

	function findBestSellers() {
		var list = document.querySelectorAll('.irantheme-woocommerce-mini-products-section');
		for (var i = 0; i < list.length; i++) {
			if (headingText(list[i]).replace(/\s/g, '').indexOf('پرفروشترین') !== -1) {
				return list[i];
			}
		}
		return list.length ? list[0] : null;
	}

	function move() {
		if (document.body && document.body.classList.contains('sp-php-home-order')) {
			return;
		}
		var latest = findLatest();
		var best = findBestSellers();
		if (!latest || !best || latest === best) {
			return;
		}
		if (latest.nextElementSibling === best) {
			return;
		}
		latest.insertAdjacentElement('afterend', best);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', move);
	} else {
		move();
	}
	window.addEventListener('load', move);
})();
