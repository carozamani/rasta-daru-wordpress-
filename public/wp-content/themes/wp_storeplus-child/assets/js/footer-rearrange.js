/*
 * Footer rearrange — legacy safety net.
 * Primary path: inc/footer-php-render.php (server-side). No-ops when data-sp-php-footer is set.
 * Styles: assets/css/footer-rearrange.css
 */
(function () {
	'use strict';

	function phpFooterDone() {
		return !!document.querySelector(
			'footer[data-sp-php-footer="1"], .irantheme-footer-nav-section[data-sp-php-footer="1"], .irantheme-footer-classic-section[data-sp-php-footer="1"]'
		);
	}

	/* Always drop Samandehi (نماد رضا); keep Enamad (نماد اعتماد) only. */
	function stripSamandehiBadges() {
		var holders = document.querySelectorAll('.irantheme-holder-badges');
		holders.forEach(function (holder) {
			var tiles = holder.querySelectorAll(':scope > .irantheme-object');
			var hasEnamad = false;
			tiles.forEach(function (tile) {
				var blob = ((tile.innerHTML || '') + ' ' + (tile.id || '')).toLowerCase();
				if (blob.indexOf('enamad') !== -1 || blob.indexOf('e-namad') !== -1) {
					hasEnamad = true;
				}
			});
			tiles.forEach(function (tile) {
				var blob = ((tile.innerHTML || '') + ' ' + (tile.id || '')).toLowerCase();
				var isSamandehi =
					blob.indexOf('samandehi') !== -1 ||
					blob.indexOf('rezi') !== -1 ||
					blob.indexOf('namadreza') !== -1;
				var isEnamad = blob.indexOf('enamad') !== -1 || blob.indexOf('e-namad') !== -1;
				if (isSamandehi || (hasEnamad && !isEnamad)) {
					tile.parentNode && tile.parentNode.removeChild(tile);
				}
			});
		});
	}

	stripSamandehiBadges();
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', stripSamandehiBadges);
	}

	if (phpFooterDone()) {
		return;
	}

	var PREFIXES = ['irantheme-footer-nav', 'irantheme-footer-classic'];

	// Default headings (used only for columns that have no heading of their own).
	var FOOTER_TITLES = {
		details: '',
		links: ['', '']
	};

	// Contact details column <-> useful links column (footer nav only).
	// The theme prints "links" first and "details" right after it; the details column is moved before the links column.
	function swapDetailsAndLinks(footer, p) {
		if (footer.getAttribute('data-footer-cols-swapped')) {
			return;
		}
		var links = footer.querySelector('.' + p + '-useful-links');
		var details = footer.querySelector('.' + p + '-details');
		if (!links || !details) {
			return;
		}
		var linksCol = links.closest('.' + p + '-col');
		var detailsCol = details.closest('.' + p + '-col');
		if (!linksCol || !detailsCol || linksCol === detailsCol || linksCol.parentNode !== detailsCol.parentNode) {
			return;
		}
		footer.setAttribute('data-footer-cols-swapped', '1');
		linksCol.parentNode.insertBefore(detailsCol, linksCol);
	}

	// Extra link column(s) right after the useful links column (on an RTL page: to its left).
	function addExtraColumns(footer, p) {
		if (footer.getAttribute('data-footer-extra-cols')) {
			return;
		}
		var cfg = window.spFooterCols;
		if (!cfg || !cfg.length) {
			return;
		}
		var links = footer.querySelector('.' + p + '-useful-links');
		var linksCol = links ? links.closest('.' + p + '-col') : null;
		if (!linksCol || !linksCol.parentNode) {
			return;
		}
		footer.setAttribute('data-footer-extra-cols', '1');

		var ref = linksCol;
		cfg.forEach(function (c) {
			if (!c || !c.links || !c.links.length) {
				return;
			}
			// Same markup as the existing links column, so it gets exactly the same styling.
			var col = linksCol.cloneNode(true);
			col.classList.add('footer-extra-col');

			var wrap = col.querySelector('.' + p + '-useful-links');
			if (!wrap) {
				return;
			}
			wrap.innerHTML = '';
			var ul = document.createElement('ul');
			ul.className = p + '-useful-links-list';
			c.links.forEach(function (item) {
				var li = document.createElement('li');
				li.className = p + '-useful-links-list-item';
				var a = document.createElement('a');
				a.className = p + '-useful-links-list-item-link';
				a.setAttribute('href', item.url || '#');
				a.textContent = item.text || '';
				li.appendChild(a);
				ul.appendChild(li);
			});
			wrap.appendChild(ul);

			if (c.title) {
				var b = document.createElement('b');
				b.className = p + '-title';
				b.textContent = c.title;
				wrap.parentNode.insertBefore(b, wrap);
			}

			ref.parentNode.insertBefore(col, ref.nextSibling);
			ref = col;
		});
	}

	// Small heading on top of each column that has none (contact details, links, extra links).
	function addColumnTitles(footer, p) {
		if (footer.getAttribute('data-footer-titles')) {
			return;
		}
		var details = footer.querySelector('.' + p + '-details');
		var linkBlocks = footer.querySelectorAll('.' + p + '-useful-links');
		if (!details && !linkBlocks.length) {
			return;
		}
		footer.setAttribute('data-footer-titles', '1');

		function put(col, text) {
			if (!col || !text || col.querySelector('.' + p + '-title')) {
				return;
			}
			var b = document.createElement('b');
			b.className = p + '-title';
			b.textContent = text;
			col.insertBefore(b, col.firstChild);
		}

		if (details) {
			put(details.closest('.' + p + '-col'), FOOTER_TITLES.details);
		}
		for (var i = 0; i < linkBlocks.length; i++) {
			put(linkBlocks[i].closest('.' + p + '-col'), FOOTER_TITLES.links[i]);
		}
	}

	function rearrangeOne(p) {
		var footer = document.querySelector('.' + p + '-section');
		if (!footer) {
			return;
		}

		swapDetailsAndLinks(footer, p);
		addExtraColumns(footer, p);
		addColumnTitles(footer, p);

		if (footer.getAttribute('data-footer-rearranged')) {
			return;
		}

		var social = footer.querySelector('.irantheme-social-media');
		var badge = footer.querySelector('.' + p + '-badge');
		var copyright = footer.querySelector('.' + p + '-copyright');
		if (!social || !copyright) {
			return;
		}
		footer.setAttribute('data-footer-rearranged', '1');

		var socialCol = social.closest('.' + p + '-col');

		// The title "ما را در شبکه های اجتماعی دنبال کنید" sits right before the icons: remove it.
		var title = social.previousElementSibling;
		if (title && title.classList.contains(p + '-title')) {
			title.parentNode.removeChild(title);
		}

		// 1) Social icons -> bottom bar (after the copyright text).
		var host = copyright.querySelector('.irantheme-container-inner') || copyright;
		host.appendChild(social);

		// 2) Badges -> contact column (under «اطلاعات تماس»), not a wrapping 5th grid cell.
		var details = footer.querySelector('.' + p + '-details');
		var contactCol = details && details.closest('.' + p + '-col');
		if (badge && contactCol) {
			contactCol.classList.add('footer-badges-col');
			contactCol.appendChild(badge);
		} else if (socialCol) {
			socialCol.classList.add('footer-badges-col');
			if (badge) {
				socialCol.appendChild(badge);
			}
		}
	}

	function rearrange() {
		PREFIXES.forEach(rearrangeOne);
	}

	function boot() {
		if (phpFooterDone()) {
			return;
		}
		rearrange();
	}
	if (document.querySelector('.irantheme-footer-nav-section, .irantheme-footer-classic-section')) {
		boot();
	} else if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
