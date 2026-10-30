/*
 * Best sellers / mini-products cards: round cart button -> adds the product to the cart (AJAX).
 * The button is printed at render time by storeplus_child_mini_cart_button(). States: normal / loading / added.
 * After a successful add, WooCommerce's "added_to_cart" event is fired so the header cart updates.
 */
(function () {
	'use strict';

	var AJAX_URL = (window.spMiniCart && window.spMiniCart.ajax) || '/?wc-ajax=add_to_cart';

	function done(btn, cls) {
		btn.classList.remove('is-loading');
		if (cls) {
			btn.classList.add(cls);
			setTimeout(function () { btn.classList.remove(cls); }, 1600);
		}
	}

	document.addEventListener('click', function (e) {
		var btn = e.target.closest ? e.target.closest('.sp-mini-cart--ajax') : null;
		if (!btn) { return; }

		e.preventDefault();
		e.stopPropagation();

		if (btn.classList.contains('is-loading')) { return; }
		btn.classList.add('is-loading');

		var body = new URLSearchParams();
		body.append('product_id', btn.getAttribute('data-product_id'));
		body.append('quantity', '1');

		fetch(AJAX_URL, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: body.toString()
		})
			.then(function (r) { return r.json(); })
			.then(function (data) {
				if (!data || data.error) {
					done(btn);
					if (data && data.product_url) { window.location.href = data.product_url; }
					return;
				}
				done(btn, 'is-added');
				if (window.jQuery) {
					var $ = window.jQuery;
					$(document.body).trigger('added_to_cart', [data.fragments, data.cart_hash, $(btn)]);
					$(document.body).trigger('wc_fragment_refresh');
				}
			})
			.catch(function () {
				done(btn);
				window.location.href = btn.getAttribute('href');
			});
	}, true);
})();
