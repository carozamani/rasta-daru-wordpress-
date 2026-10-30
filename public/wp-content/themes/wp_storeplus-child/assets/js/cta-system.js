/* Normalize the theme's legacy CTA markup to one component with small variants. */
(function () {
	'use strict';

	var selectors = [
		{
			selector: '#irantheme-header-main a[href^="tel:"]',
			classes: 'sp-cta sp-cta--support sp-cta--xs sp-cta--icon-start'
		},
		{
			selector: '#irantheme-main .irantheme-woocommerce-mini-products-heading-icon',
			classes: 'sp-cta sp-cta--accent sp-cta--sm sp-cta--icon-end'
		},
		/* Offers «مشاهده همه» keeps its own look from offers-frame / landing-polish — not the orange accent CTA. */
		{
			selector: '#irantheme-main .single_add_to_cart_button',
			classes: 'sp-cta sp-cta--primary sp-cta--md'
		}
	];

	function apply(root) {
		if (!root || !root.querySelectorAll) {
			return;
		}

		selectors.forEach(function (entry) {
			var nodes = [];
			if (root.matches && root.matches(entry.selector)) {
				nodes.push(root);
			}
			Array.prototype.forEach.call(root.querySelectorAll(entry.selector), function (node) {
				nodes.push(node);
			});

			nodes.forEach(function (node) {
				entry.classes.split(' ').forEach(function (name) {
					node.classList.add(name);
				});
			});
		});
	}

	function start() {
		apply(document);

		var roots = [];
		var main = document.getElementById('irantheme-main');
		var header = document.getElementById('irantheme-header-main');
		if (main) {
			roots.push(main);
		}
		if (header) {
			roots.push(header);
		}
		if (!roots.length || !window.MutationObserver) {
			return;
		}

		var queued = false;
		function schedule(node) {
			if (queued) {
				return;
			}
			queued = true;
			window.requestAnimationFrame(function () {
				queued = false;
				apply(node && node.nodeType === 1 ? node : document);
			});
		}

		var observer = new MutationObserver(function (records) {
			for (var i = 0; i < records.length; i++) {
				var added = records[i].addedNodes;
				for (var j = 0; j < added.length; j++) {
					if (added[j].nodeType === 1) {
						schedule(added[j]);
						return;
					}
				}
			}
		});

		roots.forEach(function (root) {
			observer.observe(root, { childList: true, subtree: true });
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start, { once: true });
	} else {
		start();
	}
})();
