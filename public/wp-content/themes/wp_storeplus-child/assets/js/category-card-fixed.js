/*
 * Category-products section: keep the green category card fixed, slide only the product cards.
 *
 * The theme renders the category card as the first slide of the products Swiper, so it moves
 * with every swipe. This script:
 *   1. copies the card and removes the original slide from the Swiper (removeSlide(), or DOM removal + update() on Swiper 9+)
 *   2. puts the copy beside the slider inside a flex row (.cp-row)
 *   3. lowers the slider's slidesPerView by one (also in its breakpoints) so each product card
 *      keeps the width it had before, and sets the card width / gap to match
 *   4. decides which side the card is on and gives the slider its own explicit direction
 *      (so the slider no longer inherits the row's direction:ltr and its first card is not clipped)
 *
 * Which side the card is on:
 *   - data-cp-side="left" | "right" on the <section> wins (set it in the section's custom attributes / HTML)
 *   - otherwise the sections alternate: 1st = card on the right, 2nd = card on the left, 3rd = right ...
 * Layout rules are in assets/css/category-card-fixed.css.
 */
(function () {
	var SECTION = '.irantheme-woocommerce-category-products-section';
	var MAX_TRIES = 50; // x 200ms = 10s to wait for the theme to create the Swiper
	/* Same as CSS --sp-card-gap and slider-peek.js (tokens.css → space-2 = 16px). */
	function cardGapPx() {
		var raw = window.getComputedStyle(document.documentElement).getPropertyValue('--sp-card-gap').trim();
		var n = parseFloat(raw);
		return isFinite(n) && n >= 0 ? n : 16;
	}
	var GAP = cardGapPx();

	function lowerByOne(obj, seen) {
		if (!obj || seen.indexOf(obj) !== -1) {
			return;
		}
		seen.push(obj);
		var v = parseFloat(obj.slidesPerView);
		if (!isNaN(v) && v > 1) {
			obj.slidesPerView = v - 1;
		}
	}

	/* Undo lowerByOne when the category card is back inside the Swiper (mobile). */
	function raiseByOne(obj, seen) {
		if (!obj || seen.indexOf(obj) !== -1) {
			return;
		}
		seen.push(obj);
		var v = parseFloat(obj.slidesPerView);
		if (!isNaN(v) && v >= 1) {
			obj.slidesPerView = v + 1;
		}
	}

	function forEachSpvConfig(sw, fn) {
		var seen = [];
		fn(sw.params, seen);
		fn(sw.originalParams, seen);
		[sw.params && sw.params.breakpoints, sw.originalParams && sw.originalParams.breakpoints].forEach(function (bps) {
			if (bps && seen.indexOf(bps) === -1) {
				seen.push(bps);
				Object.keys(bps).forEach(function (k) {
					if (bps[k] && typeof bps[k] === 'object') {
						fn(bps[k], seen);
					}
				});
			}
		});
	}

	/* Keep the rail inside the viewport: no page-level horizontal drag at the edges. */
	function hardenSwipe(sw) {
		if (!sw || !sw.params) return;
		sw.params.resistance = true;
		sw.params.resistanceRatio = 0.55;
		sw.params.touchReleaseOnEdges = true;
		sw.params.edgeSwipeDetection = false;
		sw.params.watchOverflow = true;
		if (sw.originalParams) {
			sw.originalParams.resistance = true;
			sw.originalParams.resistanceRatio = 0.55;
			sw.originalParams.touchReleaseOnEdges = true;
			sw.originalParams.edgeSwipeDetection = false;
			sw.originalParams.watchOverflow = true;
		}
	}

	// Same card spacing as every other product slider (slider-peek.js skips this section on purpose,
	// because of the slide count, so the gap is set here, before sync() reads it).
	function setGap(obj, seen) {
		if (!obj || seen.indexOf(obj) !== -1) {
			return;
		}
		seen.push(obj);
		obj.spaceBetween = GAP;
	}

	function firstChildWithClass(parent, className) {
		for (var i = 0; i < parent.children.length; i++) {
			if (parent.children[i].classList.contains(className)) {
				return parent.children[i];
			}
		}
		return null;
	}

	// true = card on the left, false = card on the right.
	function cardOnLeft(section, index) {
		var side = (section.getAttribute('data-cp-side') || '').toLowerCase();
		if (side === 'left') {
			return true;
		}
		if (side === 'right') {
			return false;
		}
		return index % 2 === 1; // even index (1st, 3rd ...) = right, odd (2nd, 4th ...) = left
	}

	// Give the slider an explicit direction and tell Swiper about it.
	function applyDirection(sw, swiperEl, dir) {
		var current = window.getComputedStyle(swiperEl).direction;
		swiperEl.style.direction = dir;
		swiperEl.setAttribute('dir', dir);
		if (current === dir && sw.rtl === (dir === 'rtl')) {
			return;
		}
		try {
			if (typeof sw.changeLanguageDirection === 'function') {
				sw.changeLanguageDirection(dir);
			} else {
				sw.rtl = dir === 'rtl';
				sw.rtlTranslate = sw.params.direction === 'horizontal' && sw.rtl;
			}
		} catch (e) { /* ignore */ }
	}

	// Returns true when finished (done or nothing to do), false when it should try again later.
	function build(section, index) {
		if (section.getAttribute('data-cp-fixed')) {
			return true;
		}

		var swiperEl = section.querySelector('.irantheme-woocommerce-product-swiper');
		if (!swiperEl) {
			return true;
		}
		var sw = swiperEl.swiper;
		if (!sw) {
			return false; // Swiper not created yet
		}

		var wrapper = swiperEl.querySelector('.swiper-wrapper');
		var cat = wrapper ? firstChildWithClass(wrapper, 'irantheme-woocommerce-category-products-cat') : null;
		if (!cat || window.getComputedStyle(cat).display === 'none') {
			section.setAttribute('data-cp-fixed', 'none');
			return true;
		}

		var onLeft = cardOnLeft(section, index);

		// Copy of the card without the Swiper slide classes / inline sizes.
		var fixed = cat.cloneNode(true);
		fixed.className = fixed.className
			.split(/\s+/)
			.filter(function (c) {
				return c && c.indexOf('swiper-slide') !== 0;
			})
			.concat('cp-fixed-col')
			.join(' ');
		fixed.removeAttribute('style');
		fixed.removeAttribute('data-swiper-slide-index');

		// Remove the original slide. Older Swiper builds have removeSlide(); newer ones (v9+) do not,
		// so then the slide element is detached from the DOM and Swiper re-reads its slides in update().
		var slideIndex = Array.prototype.indexOf.call(wrapper.children, cat);
		try {
			if (typeof sw.removeSlide === 'function') {
				sw.removeSlide(slideIndex);
			} else {
				wrapper.removeChild(cat);
				sw.update();
			}
		} catch (e) {
			return true; // do not break the page if the API differs
		}

		// Row: [card][slider] (card on the left) or [slider][card] (card on the right).
		// The row is direction:ltr, so the DOM order decides the side.
		var row = document.createElement('div');
		row.className = 'cp-row ' + (onLeft ? 'cp-card-left' : 'cp-card-right');
		swiperEl.parentNode.insertBefore(row, swiperEl);
		if (onLeft) {
			row.appendChild(fixed);
			row.appendChild(swiperEl);
		} else {
			row.appendChild(swiperEl);
			row.appendChild(fixed);
		}

		// The slider keeps its own direction: card on the right -> products flow right to left,
		// card on the left -> products flow left to right, so the first product is next to the card.
		applyDirection(sw, swiperEl, onLeft ? 'ltr' : 'rtl');

		// Desktop: one column less (fixed card sits beside the slider).
		// Mobile restores +1 again in setMobileSlides() when the card is re-inserted as a slide.
		forEachSpvConfig(sw, lowerByOne);
		section.setAttribute('data-cp-spv-lowered', '1');

		// Card spacing = the same as the other product sections (main params + every breakpoint).
		forEachSpvConfig(sw, setGap);
		hardenSwipe(sw);

		// Width of the card = width of one product card:  w = (rowWidth - n * gap) / (n + 1)
		// Only write CSS vars when the value actually changes — otherwise ResizeObserver
		// + sw.update() feed each other and freeze the page while the window is resized.
		var lastCpW = null;
		var lastCpGap = null;
		var syncRaf = 0;
		function sync() {
			var n = parseFloat(sw.params.slidesPerView);
			var gap = parseFloat(sw.params.spaceBetween) || 0;
			var rowW = row.getBoundingClientRect().width;
			var nextW = null;
			var nextGap = gap + 'px';
			if (!isNaN(n) && n >= 1 && rowW) {
				nextW = ((rowW - n * gap) / (n + 1)).toFixed(2) + 'px';
			}
			if (nextW !== null && nextW !== lastCpW) {
				lastCpW = nextW;
				row.style.setProperty('--cp-w', nextW);
			}
			if (nextGap !== lastCpGap) {
				lastCpGap = nextGap;
				row.style.setProperty('--cp-gap', nextGap);
			}
		}
		function scheduleSyncAndUpdate() {
			if (syncRaf) {
				return;
			}
			syncRaf = window.requestAnimationFrame(function () {
				syncRaf = 0;
				var beforeW = lastCpW;
				var beforeGap = lastCpGap;
				sync();
				if (lastCpW !== beforeW || lastCpGap !== beforeGap) {
					try {
						if (sw.update) {
							sw.update();
						}
					} catch (e) { /* ignore */ }
				}
			});
		}

		// On phones the whole product rail should swipe, with the category card at
		// both ends. Desktop keeps the original separate, fixed category card.
		var mobileQuery = window.matchMedia('(max-width: 767px)');
		function setMobileSlides() {
			var existing = wrapper.querySelectorAll('.cp-mobile-category-card');
			var isMobile = mobileQuery.matches;
			var restored = section.getAttribute('data-cp-spv-restored') === '1';

			if (isMobile && !existing.length) {
				function makeMobileCard() {
					var slide = fixed.cloneNode(true);
					slide.classList.remove('cp-fixed-col');
					slide.classList.add('swiper-slide', 'cp-mobile-category-card');
					slide.removeAttribute('style');
					slide.removeAttribute('data-swiper-slide-index');
					var link = slide.querySelector('.irantheme-woocommerce-category-products-cat-item-link');
					var title = slide.querySelector('.irantheme-woocommerce-category-products-cat-item-heading');
					if (link) {
						var icon = link.querySelector('i');
						var label = 'مشاهده همه محصولات' + (title && title.textContent.trim() ? ' ' + title.textContent.trim() : '');
						link.textContent = label;
						if (icon) link.appendChild(icon);
					}
					return slide;
				}
				wrapper.insertBefore(makeMobileCard(), wrapper.firstChild);
				wrapper.appendChild(makeMobileCard());
			} else if (!isMobile && existing.length) {
				Array.prototype.forEach.call(existing, function (slide) { slide.remove(); });
			}

			// Mobile puts the category card back INTO the swiper. Product slides use a fixed
			// CSS width (--sp-card-width), so numeric slidesPerView invents a wrong slideSize
			// and the last swipe empties the viewport. Use 'auto' and measure real widths.
			if (isMobile) {
				forEachSpvConfig(sw, function (obj, seen) {
					if (!obj || seen.indexOf(obj) !== -1) return;
					seen.push(obj);
					obj.slidesPerView = 'auto';
					obj.spaceBetween = GAP;
				});
				section.setAttribute('data-cp-spv-restored', '1');
			} else if (restored) {
				forEachSpvConfig(sw, lowerByOne);
				section.removeAttribute('data-cp-spv-restored');
			}

			hardenSwipe(sw);
			applyDirection(sw, swiperEl, isMobile ? 'rtl' : (onLeft ? 'ltr' : 'rtl'));

			try {
				sw.currentBreakpoint = undefined;
				if (typeof sw.setBreakpoint === 'function') {
					sw.setBreakpoint();
				}
			} catch (e) { /* ignore */ }

			if (isMobile) {
				sw.params.slidesPerView = 'auto';
				sw.params.spaceBetween = GAP;
				if (sw.originalParams) {
					sw.originalParams.slidesPerView = 'auto';
					sw.originalParams.spaceBetween = GAP;
				}
			}

			if (sw.update) sw.update();
			if (!sw.params.loop && sw.slideTo) sw.slideTo(0, 0);
		}

		try {
			sw.currentBreakpoint = undefined; // force Swiper to re-apply the (changed) breakpoint
			if (typeof sw.setBreakpoint === 'function') {
				sw.setBreakpoint();
			}
		} catch (e) { /* ignore */ }

		sync();
		setMobileSlides();
		try {
			if (typeof sw.updateSize === 'function') {
				sw.updateSize();
			}
			if (typeof sw.updateSlides === 'function') {
				sw.updateSlides();
			}
		} catch (e) { /* ignore */ }
		sw.update();
		// Back to the first product, with the right translate for the (possibly new) direction.
		try {
			if (!sw.params.loop) {
				sw.slideTo(0, 0);
			}
		} catch (e) { /* ignore */ }

		sw.on('breakpoint', function () {
			sync();
			setMobileSlides();
			sw.update();
		});
		if (mobileQuery.addEventListener) {
			mobileQuery.addEventListener('change', setMobileSlides);
		} else if (mobileQuery.addListener) {
			mobileQuery.addListener(setMobileSlides);
		}
		if (typeof ResizeObserver === 'function') {
			new ResizeObserver(scheduleSyncAndUpdate).observe(row);
		} else {
			window.addEventListener('resize', scheduleSyncAndUpdate, { passive: true });
		}

		section.setAttribute('data-cp-fixed', '1');
		return true;
	}

	function init() {
		Array.prototype.forEach.call(document.querySelectorAll(SECTION), function (section, index) {
			var tries = 0;
			(function attempt() {
				if (!build(section, index) && ++tries < MAX_TRIES) {
					setTimeout(attempt, 200);
				}
			})();
		});
	}

	if (document.readyState === 'complete') {
		init();
	} else {
		window.addEventListener('load', init);
	}
})();
