/*
 * Brands placement — legacy safety net.
 * Primary path: inc/home-section-order.php. No-ops with body.sp-php-home-order.
 */
(function () {
	'use strict';

	function placeBrands() {
		if (document.body && document.body.classList.contains('sp-php-home-order')) {
			return;
		}
		var brands = document.querySelector('.irantheme-woocommerce-box-brands-section');
		if (!brands || !brands.parentNode) {
			return;
		}

		var blog = document.querySelector('.irantheme-post-simple-section');
		if (blog && blog.parentNode === brands.parentNode) {
			if (blog.previousElementSibling !== brands) {
				blog.insertAdjacentElement('beforebegin', brands);
			}
			return;
		}

		var kids = brands.parentNode.children;
		var last = null;
		for (var i = kids.length - 1; i >= 0; i--) {
			var el = kids[i];
			if (el.classList.contains('irantheme-section') && el.tagName !== 'FOOTER' && !/irantheme-footer/.test(el.className)) {
				last = el;
				break;
			}
		}
		if (!last || last === brands) {
			return;
		}
		last.insertAdjacentElement('afterend', brands);
	}

	if (document.body && document.body.classList.contains('sp-php-home-order')) {
		return;
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', placeBrands);
	} else {
		placeBrands();
	}
	window.addEventListener('load', placeBrands);
})();
