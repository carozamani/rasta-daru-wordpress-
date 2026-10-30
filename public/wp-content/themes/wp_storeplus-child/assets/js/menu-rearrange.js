/*
 * Menu blog dedupe — legacy safety net.
 * Primary path: inc/menu-dedupe.php (wp_nav_menu_objects).
 */
(function () {
	if (document.documentElement.getAttribute('data-sp-php-menu') === '1') {
		return;
	}

	function norm(s) {
		return (s || '').replace(/\s+/g, ' ').trim();
	}

	function findItem(root, text) {
		var items = root.querySelectorAll('li');
		for (var i = 0; i < items.length; i++) {
			if (norm(items[i].textContent).indexOf(text) !== -1) {
				return items[i];
			}
		}
		return null;
	}

	function run() {
		var primary = document.querySelector('.irantheme-primary-navbar-menu .irantheme-navbar-menu-ul');
		var secondary = document.querySelector('.irantheme-secondary-navbar-menu');
		if (!primary || !secondary) {
			return;
		}

		var blog = findItem(secondary, 'وبلاگ');
		if (!blog) {
			return;
		}

		var inPrimary = false;
		var children = primary.children;
		for (var i = 0; i < children.length; i++) {
			if (norm(children[i].textContent) === 'وبلاگ') {
				inPrimary = true;
				break;
			}
		}

		if (inPrimary) {
			blog.parentNode.removeChild(blog);
		} else {
			primary.appendChild(blog);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', run);
	} else {
		run();
	}
})();
