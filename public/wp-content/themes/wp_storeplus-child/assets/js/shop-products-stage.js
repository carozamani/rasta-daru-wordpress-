/**
 * Keep shop preloader + product grid inside .sp-shop-products-stage so brand chips stay visible while loading.
 */
(function () {
	'use strict';

	var SELECTORS = [
		'.irantheme-preloader-abs',
		'.irantheme-wc-archive-ajax-error',
		'.irantheme-wc-archive-products'
	];

	function directChild(col, selector) {
		return col.querySelector(':scope > ' + selector);
	}

	function ensureStage() {
		var col = document.querySelector('.irantheme-wc-archive-product-col');
		if (!col) {
			return;
		}

		var stage = col.querySelector(':scope > [data-sp-products-stage="1"]');
		if (!stage) {
			stage = document.createElement('div');
			stage.className = 'sp-shop-products-stage';
			stage.setAttribute('data-sp-products-stage', '1');

			var anchor = null;
			SELECTORS.some(function (sel) {
				anchor = directChild(col, sel);
				return !!anchor;
			});
			if (!anchor) {
				return;
			}
			col.insertBefore(stage, anchor);
		}

		SELECTORS.forEach(function (sel) {
			var el = directChild(col, sel);
			if (el && el.parentNode !== stage) {
				stage.appendChild(el);
			}
		});
	}

	function init() {
		ensureStage();

		var col = document.querySelector('.irantheme-wc-archive-product-col');
		if (!col || !window.MutationObserver) {
			return;
		}

		var queued = false;
		new MutationObserver(function () {
			if (queued) {
				return;
			}
			queued = true;
			window.requestAnimationFrame(function () {
				queued = false;
				ensureStage();
			});
		}).observe(col, { childList: true });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
