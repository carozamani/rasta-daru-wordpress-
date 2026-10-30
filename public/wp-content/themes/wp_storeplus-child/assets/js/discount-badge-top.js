/*
 * Product cards: moves the discount percentage badge onto the image tile.
 * Observes #irantheme-main only (not document.body) to avoid main-thread noise.
 */
(function () {
	'use strict';

	var BADGE = '.irantheme-woocommerce-products-discount';
	var ITEM = '.irantheme-woocommerce-products-item, .irantheme-woocommerce-mini-products-item';
	var IMAGE = '.irantheme-woocommerce-products-item-image, .irantheme-woocommerce-mini-products-item-image';
	var ROOT = '#irantheme-main';

	function move(badge) {
		if (badge.getAttribute('data-sp-badge-top')) {
			return;
		}
		var item = badge.closest(ITEM);
		var image = item ? item.querySelector(IMAGE) : null;
		if (!image) {
			return;
		}
		image.appendChild(badge);
		badge.setAttribute('data-sp-badge-top', '1');
	}

	function run(root) {
		var list = (root || document).querySelectorAll(BADGE);
		for (var i = 0; i < list.length; i++) {
			move(list[i]);
		}
	}

	var queued = false;
	function schedule() {
		if (queued) {
			return;
		}
		queued = true;
		window.requestAnimationFrame(function () {
			queued = false;
			run(document.querySelector(ROOT) || document);
		});
	}

	function start() {
		var root = document.querySelector(ROOT) || document.body;
		run(root);
		if (!window.MutationObserver || !root) {
			return;
		}
		new MutationObserver(function (records) {
			for (var i = 0; i < records.length; i++) {
				var nodes = records[i].addedNodes;
				for (var j = 0; j < nodes.length; j++) {
					var n = nodes[j];
					if (n.nodeType !== 1) {
						continue;
					}
					if (n.matches && n.matches(BADGE)) {
						schedule();
						return;
					}
					if (n.querySelector && n.querySelector(BADGE)) {
						schedule();
						return;
					}
				}
			}
		}).observe(root, { childList: true, subtree: true });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
})();
