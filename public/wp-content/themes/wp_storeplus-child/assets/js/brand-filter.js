/*
 * Shop brand chips: follow selected categories + horizontal scroll nav.
 * Server side: inc/brand-filter.php
 */
(function () {
	'use strict';

	var cfg = window.spBrandFilter || {};
	var lastSig = null;

	function selectedCats() {
		var cats = [];
		document.querySelectorAll('input[name="product_category_item[]"]:checked').forEach(function (i) {
			if (cats.indexOf(i.value) === -1) {
				cats.push(i.value);
			}
		});
		if (!cats.length && cfg.cat) {
			cats.push(cfg.cat);
		}
		return cats.sort();
	}

	function apply(cats) {
		document.querySelectorAll('.sp-brand-filter').forEach(function (box) {
			var shown = 0;
			box.querySelectorAll('label[data-sp-cats]').forEach(function (label) {
				var input = label.querySelector('input[type=checkbox]');
				var raw = label.getAttribute('data-sp-cats') || '';
				var brandCats = raw ? raw.split(',') : [];
				var keep = !cats.length || !brandCats.length || (input && input.checked) ||
					cats.some(function (c) { return brandCats.indexOf(c) !== -1; });
				label.style.display = keep ? '' : 'none';
				if (keep) {
					shown++;
				}
			});
			box.style.display = shown ? '' : 'none';
			syncNav(box);
		});
	}

	function syncNav(box) {
		var track = box.querySelector('.sp-brand-chips__track');
		var nav = box.querySelector('.sp-brand-chips__nav--next');
		if (!track || !nav) {
			return;
		}
		var overflow = track.scrollWidth > track.clientWidth + 4;
		var atEnd = track.scrollLeft <= 4; /* RTL: start is near 0 or negative depending on browser */
		/* In RTL, scrollLeft can be 0 at start or negative; check remaining scroll. */
		var maxScroll = track.scrollWidth - track.clientWidth;
		var scrolled = Math.abs(track.scrollLeft);
		var remaining = maxScroll - scrolled;
		nav.hidden = !overflow || remaining <= 6;
	}

	function tick() {
		if (document.hidden) {
			return;
		}
		var cats = selectedCats();
		var sig = cats.join('|') + '#' + document.querySelectorAll('.sp-brand-filter').length;
		if (sig === lastSig) {
			document.querySelectorAll('.sp-brand-filter').forEach(syncNav);
			return;
		}
		lastSig = sig;
		apply(cats);
	}

	function bindScroll() {
		document.querySelectorAll('.sp-brand-chips').forEach(function (box) {
			if (box.getAttribute('data-sp-bound')) {
				return;
			}
			box.setAttribute('data-sp-bound', '1');
			var track = box.querySelector('.sp-brand-chips__track');
			var nav = box.querySelector('.sp-brand-chips__nav--next');
			if (!track) {
				return;
			}
			track.addEventListener('scroll', function () {
				syncNav(box);
			}, { passive: true });
			if (nav) {
				nav.addEventListener('click', function () {
					var step = Math.max(160, Math.floor(track.clientWidth * 0.7));
					/* RTL: next brands are to the left → negative delta in most browsers. */
					track.scrollBy({ left: -step, behavior: 'smooth' });
				});
			}
			if (window.ResizeObserver) {
				new ResizeObserver(function () { syncNav(box); }).observe(track);
			}
			syncNav(box);
		});
	}

	function init() {
		bindScroll();
		tick();

		var filterRoot = document.querySelector('.irantheme-wc-archive-main-filter') || document.getElementById('irantheme-main');
		if (filterRoot) {
			filterRoot.addEventListener('change', function () {
				window.setTimeout(tick, 50);
			});
			filterRoot.addEventListener('click', function (e) {
				if (e.target && (e.target.closest('label') || e.target.matches('input'))) {
					window.setTimeout(tick, 120);
				}
			});
		}

		var archive = document.querySelector('.irantheme-wc-archive');
		if (window.MutationObserver && (filterRoot || archive)) {
			var queued = false;
			new MutationObserver(function () {
				if (queued) {
					return;
				}
				queued = true;
				window.requestAnimationFrame(function () {
					queued = false;
					lastSig = null;
					bindScroll();
					tick();
				});
			}).observe(filterRoot || archive, { childList: true, subtree: true });
		}

		document.addEventListener('visibilitychange', function () {
			if (!document.hidden) {
				tick();
			}
		});
		window.addEventListener('resize', function () {
			document.querySelectorAll('.sp-brand-filter').forEach(syncNav);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
