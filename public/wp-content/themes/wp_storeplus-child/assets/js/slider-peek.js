/**
 * Horizontal card carousels: spacing + correct Swiper sizing.
 *
 * Product cards use a fixed CSS width (--sp-card-width on .swiper-slide).
 * Numeric slidesPerView makes Swiper invent a larger slideSize than the CSS
 * width, so maxTranslate overshoots and the last swipe empties the viewport
 * (cards look like they "left the box"). Force slidesPerView:'auto' so Swiper
 * measures the real slide widths.
 *
 * Non-product carousels keep a half-card peek via slidesPerView - 0.5.
 */
(function () {
	'use strict';

	var SELECTOR = [
		'.irantheme-woocommerce-products-post-swiper',
		'.irantheme-woocommerce-grid-products-swiper',
		'.irantheme-woocommerce-slider-product-swiper',
		'.irantheme-woocommerce-product-swiper',
		'.irantheme-woocommerce-box-categories-post-swiper',
		'.irantheme-woocommerce-box-brands-post-swiper',
		'.irantheme-business-authors-post-swiper',
		'.irantheme-business-box-reviews-post-swiper',
		'.irantheme-business-viewpoints-post-swiper',
		'.irantheme-blog-post-carousel-swiper'
	].join(',');
	/* Same as CSS --sp-card-gap (tokens.css → space-2 = 16px). */
	function cardGapPx() {
		var raw = window.getComputedStyle(document.documentElement).getPropertyValue('--sp-card-gap').trim();
		var n = parseFloat(raw);
		return Number.isFinite(n) && n >= 0 ? n : 16;
	}
	var GAP = cardGapPx();

	function isFixedWidthProductRail(element) {
		return !!(
			element.closest('.irantheme-woocommerce-products-section') ||
			element.closest('.irantheme-woocommerce-offers-products-section') ||
			element.closest('.irantheme-woocommerce-products-col') ||
			element.closest('.irantheme-woocommerce-mini-products-section')
		);
	}

	function peekCount(value) {
		var count = Number(value);
		if (!Number.isFinite(count)) return value;
		return Math.max(1.5, count - 0.5);
	}

	function setSlidesPerView(config, value, processedBreakpoints) {
		if (!config || typeof config !== 'object') return;
		config.slidesPerView = value;
		config.spaceBetween = GAP;

		if (!config.breakpoints || typeof config.breakpoints !== 'object') return;
		if (processedBreakpoints.indexOf(config.breakpoints) !== -1) return;
		processedBreakpoints.push(config.breakpoints);
		Object.keys(config.breakpoints).forEach(function (key) {
			var breakpoint = config.breakpoints[key];
			if (!breakpoint || typeof breakpoint !== 'object') return;
			breakpoint.slidesPerView = value;
			breakpoint.spaceBetween = GAP;
		});
	}

	function peekConfig(config, processedBreakpoints) {
		if (!config || typeof config !== 'object') return;
		if (typeof config.slidesPerView === 'number') {
			config.slidesPerView = peekCount(config.slidesPerView);
		}
		config.spaceBetween = GAP;

		if (!config.breakpoints || typeof config.breakpoints !== 'object') return;
		if (processedBreakpoints.indexOf(config.breakpoints) !== -1) return;
		processedBreakpoints.push(config.breakpoints);
		Object.keys(config.breakpoints).forEach(function (key) {
			var breakpoint = config.breakpoints[key];
			if (!breakpoint || typeof breakpoint !== 'object') return;
			if (typeof breakpoint.slidesPerView === 'number') {
				breakpoint.slidesPerView = peekCount(breakpoint.slidesPerView);
			}
			breakpoint.spaceBetween = GAP;
		});
	}

	function tune(element) {
		// Category-products sections manage their own slide count and edge cards.
		if (element.closest('.irantheme-woocommerce-category-products-section')) return true;
		var swiper = element.swiper;
		if (!swiper || element.classList.contains('sp-peek-ready')) return false;
		if (typeof swiper.isHorizontal === 'function' && !swiper.isHorizontal()) return true;

		var configs = [swiper.params, swiper.originalParams];
		var adjusted = [];
		var processedBreakpoints = [];
		var fixed = isFixedWidthProductRail(element);

		configs.forEach(function (config) {
			if (config && adjusted.indexOf(config) === -1) {
				adjusted.push(config);
				if (fixed) {
					setSlidesPerView(config, 'auto', processedBreakpoints);
				} else {
					peekConfig(config, processedBreakpoints);
				}
			}
		});

		if (fixed) {
			swiper.params.slidesPerView = 'auto';
			if (swiper.originalParams) swiper.originalParams.slidesPerView = 'auto';
			swiper.params.spaceBetween = GAP;
			/* Keep resistance so drag can't yank the rail past the last card. */
			if (typeof swiper.params.resistanceRatio === 'number') {
				swiper.params.resistanceRatio = 0.85;
			}
			/* Centering + fixed-width slides clips cards on both edges. */
			swiper.params.centeredSlides = false;
			if (swiper.originalParams) swiper.originalParams.centeredSlides = false;
		}

		element.classList.add('sp-peek-ready');
		swiper.update();
		if (typeof swiper.slideTo === 'function') {
			/* Bestsellers: always snap to the start so the first card isn't cut. */
			var mini = element.closest('.irantheme-woocommerce-mini-products-section');
			var idx = mini ? 0 : (typeof swiper.activeIndex === 'number' ? swiper.activeIndex : 0);
			swiper.slideTo(idx, 0);
		}
		return true;
	}

	function init() {
		var root = document.getElementById('irantheme-main') || document;
		var attempts = 0;
		var timer = window.setInterval(function () {
			var pending = false;
			root.querySelectorAll(SELECTOR).forEach(function (element) {
				if (tune(element) === false) pending = true;
			});
			attempts += 1;
			if (!pending || attempts >= 60) window.clearInterval(timer);
		}, 250);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
