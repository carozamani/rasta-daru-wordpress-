/*
 * Hide empty boxes: a section that has no items inside is hidden completely.
 * To cover another box type, add [ section selector, item selector ] to RULES.
 */
(function () {
	'use strict';

	var RULES = [
		// product sliders — exclude offers (own rule) so skeleton/AJAX cannot wipe شگفت‌انگیز
		['.irantheme-woocommerce-products-section:not(.irantheme-woocommerce-offers-products-section)', '.irantheme-woocommerce-products-item'],
		// offers: product cards only (frame side column alone must not count as “has content”)
		['.irantheme-woocommerce-offers-products-section', '.irantheme-woocommerce-products-item, .sp-product-card'],
		// grid products
		['.irantheme-woocommerce-grid-products-section', '.irantheme-woocommerce-grid-products-item-image'],
		// mini products
		['.irantheme-woocommerce-mini-products-section', '.irantheme-woocommerce-mini-products-item'],
		// categories slider
		['.irantheme-woocommerce-categories-section', '.irantheme-woocommerce-categories-item'],
		// brands
		['.irantheme-woocommerce-box-brands-section', '.irantheme-woocommerce-box-brands-item'],
		// blog posts
		['.irantheme-post-simple-section', '.irantheme-post-simple-item'],
		// banner boxes: a banner box whose banners have no picture (deleted images) is empty
		['.irantheme-business-ibanner-section', '.irantheme-business-ibanner-post-item-image-src']
	];

	function run() {
		// never hide anything while the page builder is being used
		if (document.body.classList.contains('irantheme-page-builder-active')) {
			return;
		}
		RULES.forEach(function (rule) {
			var sections = document.querySelectorAll('.irantheme-section' + rule[0]);
			Array.prototype.forEach.call(sections, function (section) {
				var empty = !section.querySelector(rule[1]);
				section.classList.toggle('sp-empty-section', empty);
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', run);
	} else {
		run();
	}
	// sections filled in later (ajax / sliders)
	window.addEventListener('load', run);
})();
