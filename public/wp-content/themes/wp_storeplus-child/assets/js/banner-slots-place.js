/*
 * Home banner slot placement — legacy safety net.
 * Primary path: inc/home-section-order.php (+ home-banners slot tagging).
 * No-ops with body.sp-php-home-order.
 */
(function () {
	'use strict';

	function headingText(section) {
		var h = section.querySelector('h1, h2, h3, h4, [class*="heading-title"], [class*="section-title"]');
		return h ? h.textContent.replace(/\s+/g, ' ').trim() : '';
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

	function bannerBySlot(slot) {
		return document.querySelector('.irantheme-business-ibanner-section[data-sp-hb-slot="' + slot + '"]');
	}

	function allBanners() {
		return document.querySelectorAll('.irantheme-business-ibanner-section');
	}

	function place() {
		if (document.body && document.body.classList.contains('sp-php-home-order')) {
			return;
		}
		var banners = allBanners();
		if (!banners.length) {
			return;
		}

		var box2 = bannerBySlot(2) || banners[1] || null;
		var box3 = bannerBySlot(3) || banners[2] || null;
		var best = findBestSellers();
		var brands = document.querySelector('.irantheme-woocommerce-box-brands-section');

		if (box2 && best && best.parentNode) {
			if (best.nextElementSibling !== box2) {
				best.insertAdjacentElement('afterend', box2);
			}
		}

		box3 = bannerBySlot(3) || document.querySelectorAll('.irantheme-business-ibanner-section')[2] || null;
		if (box3 && brands && brands.parentNode) {
			if (brands.previousElementSibling !== box3) {
				brands.insertAdjacentElement('beforebegin', box3);
			}
		}
	}

	if (document.body && document.body.classList.contains('sp-php-home-order')) {
		return;
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', place);
	} else {
		place();
	}
	window.addEventListener('load', place);
})();
