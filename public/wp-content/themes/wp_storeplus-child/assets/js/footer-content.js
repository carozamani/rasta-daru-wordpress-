/*
 * Footer content — legacy safety net.
 * Primary path: inc/footer-php-render.php. No-ops when data-sp-php-footer is set.
 * Config (fallback only): window.spFooterContent = { quick, contact, social }.
 */
(function () {
	'use strict';

	function phpFooterDone() {
		return !!document.querySelector(
			'footer[data-sp-php-footer="1"], .irantheme-footer-nav-section[data-sp-php-footer="1"], .irantheme-footer-classic-section[data-sp-php-footer="1"]'
		);
	}

	if (phpFooterDone()) {
		return;
	}

	var cfg = window.spFooterContent;
	if (!cfg) {
		return;
	}

	var PREFIXES = ['irantheme-footer-nav', 'irantheme-footer-classic'];

	function setTitle(col, p, text) {
		var title = null;
		for (var i = 0; i < col.children.length; i++) {
			if (col.children[i].classList.contains(p + '-title')) {
				title = col.children[i];
			}
		}
		if (!text) {
			if (title) {
				title.parentNode.removeChild(title);
			}
			return;
		}
		if (!title) {
			title = document.createElement('b');
			title.className = p + '-title';
			col.insertBefore(title, col.firstChild);
		}
		title.textContent = text;
	}

	function applyQuick(footer, p) {
		var q = cfg.quick;
		if (!q) {
			return;
		}
		var blocks = footer.querySelectorAll('.' + p + '-useful-links');
		var block = null;
		for (var i = 0; i < blocks.length; i++) {
			if (!blocks[i].closest('.footer-extra-col')) {
				block = blocks[i];
				break;
			}
		}
		if (!block) {
			return;
		}
		var col = block.closest('.' + p + '-col');
		if (!col) {
			return;
		}
		if (!q.links || !q.links.length) {
			col.style.display = 'none';
			return;
		}
		col.style.display = '';
		block.innerHTML = '';
		var ul = document.createElement('ul');
		ul.className = p + '-useful-links-list';
		q.links.forEach(function (item) {
			var li = document.createElement('li');
			li.className = p + '-useful-links-list-item';
			var a = document.createElement('a');
			a.className = p + '-useful-links-list-item-link';
			a.setAttribute('href', item.url || '#');
			a.textContent = item.text || '';
			li.appendChild(a);
			ul.appendChild(li);
		});
		block.appendChild(ul);
		setTitle(col, p, q.title);
	}

	function applyContact(footer, p) {
		var c = cfg.contact;
		if (!c) {
			return;
		}
		var details = footer.querySelector('.' + p + '-details');
		if (!details) {
			return;
		}
		var col = details.closest('.' + p + '-col');
		if (!col) {
			return;
		}
		var rows = c.rows || [];
		if (!c.intro && !rows.length) {
			col.style.display = 'none';
			return;
		}
		col.style.display = '';
		details.innerHTML = '';
		function line(text) {
			var el = document.createElement('p');
			el.textContent = text;
			details.appendChild(el);
		}
		if (c.intro) {
			line(c.intro);
		}
		rows.forEach(function (r) {
			var text = (r.label && r.value) ? (r.label + ' : ' + r.value) : (r.label || r.value || '');
			if (text) {
				line(text);
			}
		});
		setTitle(col, p, c.title);
	}

	function applySocial(footer) {
		var list = cfg.social;
		if (!list) {
			return;
		}
		var uls = footer.querySelectorAll('.irantheme-social-media');
		Array.prototype.forEach.call(uls, function (ul) {
			ul.innerHTML = '';
			if (!list.length) {
				ul.style.display = 'none';
				return;
			}
			ul.style.display = '';
			list.forEach(function (s) {
				var li = document.createElement('li');
				var a = document.createElement('a');
				a.setAttribute('href', s.url || '#');
				if (/^https?:/i.test(s.url || '')) {
					a.setAttribute('target', '_blank');
					a.setAttribute('rel', 'noopener');
				}
				if (s.label) {
					a.setAttribute('aria-label', s.label);
				}
				if (s.img) {
					var img = document.createElement('img');
					img.setAttribute('src', s.img);
					img.setAttribute('alt', s.label || '');
					a.appendChild(img);
				} else if (s.svg) {
					a.innerHTML = s.svg;
				} else if (s.icon) {
					var i = document.createElement('i');
					i.className = s.icon;
					a.appendChild(i);
				}
				li.appendChild(a);
				ul.appendChild(li);
			});
		});
	}

	function markReady() {
		document.documentElement.classList.add('sp-footer-ready');
		PREFIXES.forEach(function (p) {
			var footer = document.querySelector('.' + p + '-section');
			if (footer) {
				footer.setAttribute('data-footer-ready', '1');
			}
		});
	}

	function apply() {
		if (phpFooterDone()) {
			return;
		}
		PREFIXES.forEach(function (p) {
			var footer = document.querySelector('.' + p + '-section');
			if (!footer) {
				return;
			}
			applyQuick(footer, p);
			applyContact(footer, p);
			applySocial(footer);
		});
		markReady();
	}

	function boot() {
		if (phpFooterDone()) {
			return;
		}
		apply();
	}
	if (document.querySelector('.irantheme-footer-nav-section, .irantheme-footer-classic-section')) {
		boot();
	} else if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
