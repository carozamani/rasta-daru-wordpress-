/*
 * Smart sticky header: hides the menu row (.irantheme-header-wrapper-menu) while
 * scrolling down and shows it again while scrolling up. Also toggles .sp-scrolled.
 * Styles live in assets/css/parts/sticky-header.css (built into site-critical.css).
 *
 * Do NOT:
 *  - reparent #irantheme-header-main on breakpoint changes
 *  - set body padding from measured header height + observe the header with
 *    ResizeObserver (that loop froze the page when resizing across ~992/1024px)
 *  - force overflow:clip on ancestors (breaks layout during resize)
 *  - measure .wdc-top-banner into --sp-banner-h while also driving body padding
 *    from it (same feedback loop as --sp-mobile-header-h)
 */
(function () {
	'use strict';

	var HIDE_CLASS = 'sp-menu-hidden';
	var SCROLLED_CLASS = 'sp-scrolled';
	var UP_CLASS = 'sp-scroll-up';
	var DELTA = 6;
	var DESKTOP_MIN = '(min-width: 1025px)';

	function init() {
		var header = document.getElementById('irantheme-header-main');
		if (!header) {
			return;
		}
		var menu = header.querySelector('.irantheme-header-wrapper-menu');
		if (!menu) {
			return;
		}

		/* Mobile toggle next to the logo (RTL: first child = right side). */
		var trigger = header.querySelector('.irantheme-navbar-trigger-holder');
		var logo = header.querySelector('.irantheme-logo-brand');
		var originalTriggerParent = trigger && trigger.parentNode;
		var originalTriggerNext = trigger && trigger.nextSibling;
		var mobileHeaderQuery = window.matchMedia('(max-width: 991px)');

		function placeMobileMenuToggle() {
			if (!trigger || !logo || !originalTriggerParent) {
				return;
			}
			var logoColumn = logo.closest('.irantheme-header-col');
			if (mobileHeaderQuery.matches && logoColumn) {
				if (trigger.parentNode !== logoColumn || trigger !== logoColumn.firstChild) {
					logoColumn.insertBefore(trigger, logoColumn.firstChild);
				}
				header.classList.add('sp-mobile-menu-next-logo');
			} else {
				if (trigger.parentNode !== originalTriggerParent) {
					originalTriggerParent.insertBefore(
						trigger,
						originalTriggerNext && originalTriggerNext.parentNode === originalTriggerParent
							? originalTriggerNext
							: null
					);
				}
				header.classList.remove('sp-mobile-menu-next-logo');
			}
		}
		placeMobileMenuToggle();
		if (mobileHeaderQuery.addEventListener) {
			mobileHeaderQuery.addEventListener('change', placeMobileMenuToggle);
		} else if (mobileHeaderQuery.addListener) {
			mobileHeaderQuery.addListener(placeMobileMenuToggle);
		}

		var desktopQuery = window.matchMedia(DESKTOP_MIN);
		var lastMenuH = null;
		var measureRaf = 0;

		function measureMenuHeight() {
			measureRaf = 0;
			if (!desktopQuery.matches) {
				if (lastMenuH !== null) {
					lastMenuH = null;
					header.style.removeProperty('--sp-menu-h');
				}
				header.classList.remove(HIDE_CLASS, UP_CLASS);
				return;
			}
			var menuH = menu.offsetHeight;
			if (menuH !== lastMenuH) {
				lastMenuH = menuH;
				header.style.setProperty('--sp-menu-h', menuH + 'px');
			}
		}

		function scheduleMeasure() {
			if (measureRaf) {
				return;
			}
			measureRaf = window.requestAnimationFrame(measureMenuHeight);
		}

		measureMenuHeight();
		window.addEventListener('resize', scheduleMeasure, { passive: true });
		if (desktopQuery.addEventListener) {
			desktopQuery.addEventListener('change', measureMenuHeight);
		} else if (desktopQuery.addListener) {
			desktopQuery.addListener(measureMenuHeight);
		}
		/* Observe only the menu row, and only write --sp-menu-h (used by clip-path,
		   not by layout size), so this cannot feedback into a resize loop. */
		if (window.ResizeObserver) {
			new ResizeObserver(scheduleMeasure).observe(menu);
		}

		var lastY = window.pageYOffset || 0;
		var ticking = false;

		function overlayOpen() {
			var b = document.body;
			if (b.classList.contains('sp-mega-open') || b.classList.contains('sp-search-open')) {
				return true;
			}
			try {
				return header.matches(
					':has(li.irantheme-mega-menu-item:hover), :has(.irantheme-search-form:focus-within), :focus-within'
				);
			} catch (e) {
				return header.matches(':focus-within');
			}
		}

		function update() {
			ticking = false;
			var y = window.pageYOffset || 0;
			var diff = y - lastY;

			header.classList.toggle(SCROLLED_CLASS, y > 50);

			if (!desktopQuery.matches || menu.offsetHeight === 0) {
				header.classList.remove(HIDE_CLASS, UP_CLASS);
				lastY = y;
				return;
			}

			/* Keep the menu row open while mega / search is in use — collapsing
			   under the cursor ends hover and re-opens, which looks like a jump. */
			if (overlayOpen()) {
				header.classList.add(UP_CLASS);
				header.classList.remove(HIDE_CLASS);
				lastY = y;
				return;
			}

			if (y <= header.offsetHeight) {
				header.classList.remove(UP_CLASS, HIDE_CLASS);
			} else if (Math.abs(diff) >= DELTA) {
				if (diff > 0) {
					header.classList.remove(UP_CLASS);
					if (!header.matches(':focus-within')) {
						header.classList.add(HIDE_CLASS);
					}
				} else {
					header.classList.add(UP_CLASS);
					header.classList.remove(HIDE_CLASS);
				}
			} else {
				return;
			}
			lastY = y;
		}

		window.addEventListener(
			'scroll',
			function () {
				if (!ticking) {
					ticking = true;
					window.requestAnimationFrame(update);
				}
			},
			{ passive: true }
		);

		update();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
