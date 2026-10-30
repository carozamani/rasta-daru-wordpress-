/* Populate the theme's native Swiper with WooCommerce product brands (inline data, no fetch). */
(function () {
	'use strict';

	function escapeHtml(value) {
		return String(value).replace(/[&<>"']/g, function (ch) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[ch];
		});
	}

	function populate(section) {
		if (section.dataset.spBrandsLoaded === '1') return;
		section.dataset.spBrandsLoaded = '1';

		var brands = (window.StorePlusBrands && Array.isArray(StorePlusBrands.brands))
			? StorePlusBrands.brands
			: null;

		if (!brands || !brands.length) {
			section.hidden = true;
			return;
		}

		var slider = section.querySelector('.irantheme-woocommerce-box-brands-post-swiper');
		var row = slider && slider.querySelector('.swiper-wrapper');
		if (!slider || !row) {
			section.hidden = true;
			return;
		}

		var firstSlide = row.querySelector('.swiper-slide');
		var slideStyle = firstSlide ? firstSlide.getAttribute('style') : '';
		row.innerHTML = brands.map(function (brand) {
			var name = escapeHtml(brand.name || '');
			var image = brand.logo
				? '<img class="irantheme-woocommerce-box-brands-item-heading-image-img" src="' + escapeHtml(brand.logo) + '" alt="' + name + '" loading="lazy" decoding="async">'
				: '<span class="sp-brand-name-fallback">' + name + '</span>';
			return '<div class="swiper-slide"' + (slideStyle ? ' style="' + escapeHtml(slideStyle) + '"' : '') + '>' +
				'<div class="irantheme-woocommerce-box-brands-col"><div class="irantheme-woocommerce-box-brands-item">' +
				'<a class="irantheme-woocommerce-box-brands-item-heading" href="' + escapeHtml(brand.url) + '" aria-label="' + name + '">' +
				'<span class="irantheme-woocommerce-box-brands-item-heading-image">' + image + '</span>' +
				'</a></div></div></div>';
		}).join('');

		if (slider.swiper) slider.swiper.update();
		section.hidden = false;
		section.classList.remove('sp-brands-loading');
		section.classList.add('sp-brands-ready');
		document.dispatchEvent(new CustomEvent('sp-brands-ready'));
	}

	function run() {
		document.querySelectorAll('.irantheme-woocommerce-box-brands-section').forEach(populate);
	}
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
	else run();
})();
