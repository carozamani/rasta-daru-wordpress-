/*
 * Popular brands: endless, automatic scrolling strip.
 *  - waits until brands-swipe.js has filled the slider with the real brands (class .sp-brands-ready)
 *  - switches the Swiper slider off (it would fight the CSS animation)
 *  - clones the logos so the row never runs out, then starts the CSS animation
 *    (see assets/css/brands-marquee.css)
 */
(function () {
	var SPEED = 45; // pixels per second; lower = slower
	var timer;

	function build(section) {
		// Wait until brands-swipe.js has injected the real brands (or skip if section is empty/hidden).
		if (section.hidden || !section.classList.contains('sp-brands-ready')) {
			return;
		}

		var swiperEl = section.querySelector('.irantheme-woocommerce-box-brands-post-swiper') || section.querySelector('.swiper');
		if (!swiperEl) {
			return;
		}
		var wrapper = swiperEl.querySelector('.swiper-wrapper');
		if (!wrapper) {
			return;
		}

		// Switch the slider off and remove its inline sizes / transforms.
		if (swiperEl.swiper && typeof swiperEl.swiper.destroy === 'function') {
			try {
				swiperEl.swiper.destroy(true, true);
			} catch (e) { /* ignore */ }
		}

		// Remove clones from a previous run and Swiper's own loop copies.
		Array.prototype.slice.call(wrapper.querySelectorAll('.brands-clone, .swiper-slide-duplicate')).forEach(function (n) {
			n.parentNode.removeChild(n);
		});

		var originals = Array.prototype.slice.call(wrapper.children);
		if (!originals.length) {
			return;
		}

		var slideW = originals[0].getBoundingClientRect().width;
		var setW = slideW * originals.length;
		var viewW = swiperEl.clientWidth;
		if (!slideW || !setW || !viewW) {
			return;
		}

		var copies = Math.ceil(viewW / setW) + 1;
		for (var c = 0; c < copies; c++) {
			originals.forEach(function (node) {
				var clone = node.cloneNode(true);
				clone.classList.add('brands-clone');
				clone.setAttribute('aria-hidden', 'true');
				Array.prototype.slice.call(clone.attributes).forEach(function (attr) {
					if (attr.name.indexOf('data-aos') === 0) {
						clone.removeAttribute(attr.name);
					}
				});
				wrapper.appendChild(clone);
			});
		}

		var rtl = window.getComputedStyle(swiperEl).direction === 'rtl';
		section.style.setProperty('--bm-dir', rtl ? '1' : '-1');
		section.style.setProperty('--bm-distance', setW + 'px');
		section.style.setProperty('--bm-duration', (setW / SPEED).toFixed(2) + 's');
		section.classList.add('is-marquee');
	}

	function run() {
		var sections = document.querySelectorAll('.irantheme-woocommerce-box-brands-section');
		Array.prototype.forEach.call(sections, build);
	}

	function start() {
		// After the theme has initialised its sliders, then once more in case it was late.
		setTimeout(run, 300);
		setTimeout(run, 1500);
	}

	if (document.readyState === 'complete') {
		start();
	} else {
		window.addEventListener('load', start);
	}

	// brands-swipe.js fires this when the real brands have been put into the slider.
	document.addEventListener('sp-brands-ready', function () {
		setTimeout(run, 50);
	});

	window.addEventListener('resize', function () {
		clearTimeout(timer);
		timer = setTimeout(run, 250);
	});
})();
