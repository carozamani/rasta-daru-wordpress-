/**
 * Custom Admin UI | admin.js
 * 1. Light/dark toggle (remembered per browser).
 * 2. Quick-search palette (Ctrl/Cmd + /) built from the admin menu.
 * No dependencies; reads only the DOM that WordPress already renders.
 */
(function () {
	'use strict';

	var cfg = window.CAU || {};
	var t = cfg.i18n || {};
	var root = document.documentElement;
	var isMac = /Mac|iPhone|iPad/.test(navigator.platform || navigator.userAgent || '');

	/* ---------------------------------------------------------------- Theme */

	function currentTheme() {
		return root.getAttribute('data-cau-theme') === 'dark' ? 'dark' : 'light';
	}

	function syncToggle() {
		var a = document.querySelector('#wp-admin-bar-cau-theme > a');
		if (a) {
			a.setAttribute('aria-pressed', currentTheme() === 'dark' ? 'true' : 'false');
		}
	}

	function setTheme(mode) {
		root.setAttribute('data-cau-theme', mode);
		try {
			localStorage.setItem('cau-mode', mode);
		} catch (e) {}
		syncToggle();
	}

	/* -------------------------------------------------------------- Palette */

	var palette, input, list, empty;
	var items = [];
	var results = [];
	var active = 0;
	var lastFocus = null;

	function el(tag, cls) {
		var n = document.createElement(tag);
		if (cls) {
			n.className = cls;
		}
		return n;
	}

	function clean(node) {
		var c = node.cloneNode(true);
		c.querySelectorAll('.update-plugins,.awaiting-mod,.pending-count,.plugin-count,.count').forEach(function (n) {
			n.remove();
		});
		return c.textContent.replace(/\s+/g, ' ').trim();
	}

	function collect() {
		var out = [];
		var seen = {};

		function push(label, group, a) {
			var raw = a.getAttribute('href');
			if (!label || !raw || raw.charAt(0) === '#' || /^javascript:/i.test(raw)) {
				return;
			}
			var key = a.href + '|' + label;
			if (seen[key]) {
				return;
			}
			seen[key] = true;
			out.push({ label: label, group: group, href: a.href, hay: (label + ' ' + group).toLowerCase() });
		}

		document.querySelectorAll('#adminmenu > li.menu-top').forEach(function (li) {
			var top = li.querySelector(':scope > a.menu-top');
			if (!top) {
				return;
			}
			var name = clean(top.querySelector('.wp-menu-name') || top);
			var subs = li.querySelectorAll('.wp-submenu > li:not(.wp-submenu-head) > a');
			if (!subs.length) {
				push(name, '', top);
				return;
			}
			subs.forEach(function (a) {
				push(clean(a), name, a);
			});
		});

		return out;
	}

	function setActive(i, scroll) {
		var nodes = list.children;
		if (nodes[active]) {
			nodes[active].classList.remove('is-active');
			nodes[active].setAttribute('aria-selected', 'false');
		}
		active = i;
		var n = nodes[i];
		if (n) {
			n.classList.add('is-active');
			n.setAttribute('aria-selected', 'true');
			input.setAttribute('aria-activedescendant', n.id);
			if (scroll) {
				n.scrollIntoView({ block: 'nearest' });
			}
		} else {
			input.removeAttribute('aria-activedescendant');
		}
	}

	function go(i) {
		if (results[i]) {
			window.location.href = results[i].href;
		}
	}

	function render() {
		var q = input.value.trim().toLowerCase();

		results = items.filter(function (it) {
			return !q || it.hay.indexOf(q) !== -1;
		});

		if (q) {
			results.sort(function (a, b) {
				var as = a.label.toLowerCase().indexOf(q) === 0 ? 0 : 1;
				var bs = b.label.toLowerCase().indexOf(q) === 0 ? 0 : 1;
				return as - bs;
			});
		}
		results = results.slice(0, 40);

		list.textContent = '';
		empty.hidden = results.length > 0;

		results.forEach(function (it, i) {
			var li = el('li', 'cau-pal-item');
			li.id = 'cau-opt-' + i;
			li.setAttribute('role', 'option');
			li.setAttribute('aria-selected', 'false');

			var label = el('span', 'cau-pal-label');
			label.textContent = it.label;
			li.appendChild(label);

			if (it.group) {
				var group = el('span', 'cau-pal-group');
				group.textContent = it.group;
				li.appendChild(group);
			}

			li.addEventListener('mousemove', function () {
				if (active !== i) {
					setActive(i, false);
				}
			});
			li.addEventListener('click', function () {
				go(i);
			});
			list.appendChild(li);
		});

		active = 0;
		setActive(0, false);
	}

	function build() {
		palette = el('div', 'cau-pal');
		palette.hidden = true;

		var back = el('div', 'cau-pal-backdrop');
		var box = el('div', 'cau-pal-box');
		box.setAttribute('role', 'dialog');
		box.setAttribute('aria-modal', 'true');
		box.setAttribute('aria-label', t.title || 'Quick search');

		input = el('input', 'cau-pal-input');
		input.type = 'text';
		input.autocomplete = 'off';
		input.spellcheck = false;
		input.placeholder = t.placeholder || '';
		input.setAttribute('role', 'combobox');
		input.setAttribute('aria-expanded', 'true');
		input.setAttribute('aria-controls', 'cau-pal-list');
		input.setAttribute('aria-autocomplete', 'list');
		input.setAttribute('aria-label', t.title || 'Quick search');

		list = el('ul', 'cau-pal-list');
		list.id = 'cau-pal-list';
		list.setAttribute('role', 'listbox');

		empty = el('p', 'cau-pal-empty');
		empty.textContent = t.empty || '';
		empty.hidden = true;

		var foot = el('div', 'cau-pal-foot');
		foot.textContent = t.hint || '';

		box.appendChild(input);
		box.appendChild(list);
		box.appendChild(empty);
		box.appendChild(foot);
		back.appendChild(box);
		palette.appendChild(back);
		document.body.appendChild(palette);

		back.addEventListener('mousedown', function (e) {
			if (e.target === back) {
				close();
			}
		});

		input.addEventListener('input', render);
		input.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowDown') {
				e.preventDefault();
				if (results.length) {
					setActive((active + 1) % results.length, true);
				}
			} else if (e.key === 'ArrowUp') {
				e.preventDefault();
				if (results.length) {
					setActive((active - 1 + results.length) % results.length, true);
				}
			} else if (e.key === 'Enter') {
				e.preventDefault();
				go(active);
			} else if (e.key === 'Tab') {
				e.preventDefault();
			}
		});
	}

	function isOpen() {
		return palette && !palette.hidden;
	}

	function open() {
		if (!palette) {
			build();
		}
		items = collect();
		lastFocus = document.activeElement;
		palette.hidden = false;
		document.body.classList.add('cau-pal-open');
		input.value = '';
		render();
		input.focus();
	}

	function close() {
		if (!isOpen()) {
			return;
		}
		palette.hidden = true;
		document.body.classList.remove('cau-pal-open');
		if (lastFocus && typeof lastFocus.focus === 'function') {
			lastFocus.focus();
		}
	}

	/* --------------------------------------------------------------- Events */

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && isOpen()) {
			e.preventDefault();
			close();
			return;
		}
		if ((e.ctrlKey || e.metaKey) && !e.altKey && !e.shiftKey && e.key === '/') {
			// Ctrl+/ avoids WordPress core's own Ctrl+K command palette.
			if (document.body.classList.contains('block-editor-page')) {
				return;
			}
			e.preventDefault();
			if (isOpen()) {
				close();
			} else {
				open();
			}
		}
	});

	document.addEventListener('click', function (e) {
		if (!e.target.closest) {
			return;
		}
		var themeBtn = e.target.closest('#wp-admin-bar-cau-theme > a');
		if (themeBtn) {
			e.preventDefault();
			setTheme(currentTheme() === 'dark' ? 'light' : 'dark');
			return;
		}
		var searchBtn = e.target.closest('#wp-admin-bar-cau-search > a');
		if (searchBtn) {
			e.preventDefault();
			open();
		}
	});

	function init() {
		['#wp-admin-bar-cau-theme > a', '#wp-admin-bar-cau-search > a'].forEach(function (sel) {
			var a = document.querySelector(sel);
			if (a) {
				a.setAttribute('role', 'button');
			}
		});
		if (isMac) {
			var kbd = document.querySelector('#wp-admin-bar-cau-search .cau-kbd');
			if (kbd) {
				kbd.textContent = '\u2318/';
			}
		}
		syncToggle();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();

/* Sidebar toggle button on the sidebar border: opens / closes (folds) the admin menu. */
(function () {
	'use strict';

	function init() {
		var wrap = document.getElementById('adminmenuwrap');
		var native = document.getElementById('collapse-button');
		if (!wrap || !native || document.getElementById('cau-sidebar-toggle')) { return; }

		var fa = /^fa/i.test(document.documentElement.lang || '');
		var btn = document.createElement('button');
		btn.type = 'button';
		btn.id = 'cau-sidebar-toggle';
		btn.className = 'cau-sidebar-toggle';

		function sync() {
			var folded = document.body.classList.contains('folded');
			btn.setAttribute('aria-expanded', folded ? 'false' : 'true');
			var label = folded ? (fa ? '\u0628\u0627\u0632 \u06a9\u0631\u062f\u0646 \u0645\u0646\u0648' : 'Open menu') : (fa ? '\u0628\u0633\u062a\u0646 \u0645\u0646\u0648' : 'Close menu');
			btn.setAttribute('aria-label', label);
			btn.title = label;
		}

		btn.addEventListener('click', function () {
			/* WordPress's own handler toggles body.folded and saves the choice for this user. */
			native.click();
		});

		document.body.appendChild(btn);
		sync();
		new MutationObserver(sync).observe(document.body, { attributes: true, attributeFilter: ['class'] });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
