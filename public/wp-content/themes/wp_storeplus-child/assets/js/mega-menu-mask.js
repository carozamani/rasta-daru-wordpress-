/*
 * Dim the page while the header mega menu OR ajax search dropdown is open —
 * same dark overlay feel as the cart sidebar mask. CSS :has handles most cases;
 * this toggles body.sp-mega-open / body.sp-search-open as reliable fallbacks.
 */
(function () {
	'use strict';

	var MEGA_CLASS = 'sp-mega-open';
	var SEARCH_CLASS = 'sp-search-open';
	var leaveTimer = null;
	var searchLeaveTimer = null;

	function openMega() {
		clearTimeout(leaveTimer);
		document.body.classList.add(MEGA_CLASS);
	}

	function closeMega() {
		clearTimeout(leaveTimer);
		leaveTimer = setTimeout(function () {
			document.body.classList.remove(MEGA_CLASS);
		}, 60);
	}

	function openSearch() {
		clearTimeout(searchLeaveTimer);
		document.body.classList.add(SEARCH_CLASS);
	}

	function closeSearch() {
		clearTimeout(searchLeaveTimer);
		searchLeaveTimer = setTimeout(function () {
			document.body.classList.remove(SEARCH_CLASS);
		}, 120);
	}

	function bindMega() {
		var items = document.querySelectorAll(
			'#irantheme-header-main li.irantheme-mega-menu-item'
		);
		if (!items.length) {
			return;
		}
		items.forEach(function (li) {
			li.addEventListener('mouseenter', openMega);
			li.addEventListener('mouseleave', closeMega);
			li.addEventListener('focusin', openMega);
			li.addEventListener('focusout', function (e) {
				if (!li.contains(e.relatedTarget)) {
					closeMega();
				}
			});
		});
	}

	function bindSearch() {
		var forms = document.querySelectorAll(
			'#irantheme-header-main .irantheme-search-form'
		);
		if (!forms.length) {
			return;
		}
		forms.forEach(function (form) {
			form.addEventListener('focusin', openSearch);
			form.addEventListener('focusout', function (e) {
				if (!form.contains(e.relatedTarget)) {
					closeSearch();
				}
			});
			/* Keep dim while the results panel is shown (mouse use without focus stay) */
			form.addEventListener('mouseenter', function () {
				var results = form.querySelector('.irantheme-search-results');
				if (results && results.offsetParent !== null) {
					openSearch();
				}
			});
			form.addEventListener('mouseleave', function () {
				if (!form.contains(document.activeElement)) {
					closeSearch();
				}
			});
		});
	}

	function bind() {
		bindMega();
		bindSearch();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', bind);
	} else {
		bind();
	}
})();
