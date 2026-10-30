(function () {
	'use strict';
	var root = document.querySelector('[data-sp-float-support]');
	if (!root) return;
	var fab = root.querySelector('.sp-float-support__fab');
	var panel = root.querySelector('.sp-float-support__panel');
	if (!fab || !panel) return;

	function setOpen(open) {
		root.classList.toggle('is-open', open);
		fab.setAttribute('aria-expanded', open ? 'true' : 'false');
		fab.setAttribute('aria-label', open ? 'بستن پشتیبانی' : 'باز کردن پشتیبانی');
		if (open) {
			panel.removeAttribute('hidden');
		} else {
			panel.setAttribute('hidden', '');
		}
	}

	fab.addEventListener('click', function () {
		setOpen(!root.classList.contains('is-open'));
	});

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && root.classList.contains('is-open')) {
			setOpen(false);
			fab.focus();
		}
	});

	document.addEventListener('click', function (e) {
		if (!root.classList.contains('is-open')) return;
		if (!root.contains(e.target)) setOpen(false);
	});
})();
