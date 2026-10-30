/**
 * Hero slider - dynamic behaviour (file name kept so functions.php needs no change).
 *
 * The slides themselves come from the page builder ("slider-simple" section): every item
 * added there is one slide. This script only adapts the slider to HOW MANY slides exist:
 *
 *   - 1 slide   -> no slider at all: Swiper is destroyed, so no swipe / autoplay / dots / arrows.
 *   - 2+ slides -> slider with navigation dots at the bottom (created here if the page
 *                  builder's "pagination" option is off) and autoplay every DELAY ms.
 *
 * Nothing is hard-coded to a number of slides. Styles: assets/css/hero-ratio.css (bottom part).
 */
(function () {
	'use strict';

	var DELAY    = 5000; // ms between slides
	var SELECTOR = '.irantheme-swiper-section-slider-simple';

	/** Real slides only (Swiper loop mode adds duplicates that must not be counted). */
	function countSlides(el) {
		return el.querySelectorAll('.swiper-wrapper > .swiper-slide:not(.swiper-slide-duplicate)').length;
	}

	/** One slide: turn the slider off completely and show the slide as a plain banner. */
	function makeSingle(el, swiper) {
		try {
			if (swiper.autoplay && swiper.autoplay.running) {
				swiper.autoplay.stop();
			}
		} catch (e) {}

		try {
			swiper.destroy(true, true);
		} catch (e) {}

		el.classList.add('sp-hero-single');
	}

	/** Two or more slides: dots + autoplay. */
	function makeMulti(el, swiper) {
		el.classList.remove('sp-hero-single');

		// Navigation dots: keep the page builder's own pagination if it has one,
		// otherwise create it and start Swiper's pagination module on it.
		if (!el.querySelector('.swiper-pagination')) {
			var pag = document.createElement('div');
			pag.className = 'swiper-pagination sp-hero-pagination';
			el.appendChild(pag);

			if (swiper.pagination && swiper.params.pagination && typeof swiper.params.pagination === 'object') {
				swiper.params.pagination.el        = pag;
				swiper.params.pagination.clickable = true;
				swiper.pagination.init();
				swiper.pagination.render();
				swiper.pagination.update();
			}
		}

		// Autoplay (does nothing if the page builder already turned it on).
		if (swiper.autoplay && !swiper.autoplay.running) {
			if (!swiper.params.autoplay || typeof swiper.params.autoplay !== 'object') {
				swiper.params.autoplay = {};
			}
			swiper.params.autoplay.enabled              = true;
			swiper.params.autoplay.delay                = DELAY;
			swiper.params.autoplay.disableOnInteraction = false;
			swiper.params.autoplay.pauseOnMouseEnter    = true;

			swiper.autoplay.start();
		}
	}

	function setup() {
		var sliders = document.querySelectorAll(SELECTOR);

		for (var i = 0; i < sliders.length; i++) {
			var el     = sliders[i];
			var swiper = el.swiper;

			// Not initialised yet, destroyed already, or already handled.
			if (!swiper || swiper.destroyed || el.__spHeroSwiper === swiper) {
				continue;
			}

			var count = countSlides(el);
			if (count < 1) {
				continue;
			}

			el.__spHeroSwiper = swiper;

			if (count === 1) {
				makeSingle(el, swiper);
			} else {
				makeMulti(el, swiper);
			}
		}
	}

	window.addEventListener('load', function () {
		setup();
		// Safety net in case the slider is initialised slightly later.
		setTimeout(setup, 1500);
	});

	/*
	 * Do not observe document.body. Swiper's own DOM updates were retriggering
	 * setup(), which destroyed the slider, which mutated the DOM again — an
	 * endless loop that left the landing page blank.
	 */
})();
