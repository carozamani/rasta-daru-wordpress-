/**
 * Blog section ("مطالب وبلاگ", post-simple):
 *  - turns the Swiper carousel into a static row (CSS shows only the 3 latest posts, centered)
 *  - centers the section title (marks the heading block with .sp-blog-head, styled in blog-latest-three.css)
 */
(function () {
	'use strict';

	var SECTION = '.irantheme-post-simple-section';

	function headingBlock(wrapper) {
		var h = wrapper.querySelector('h1, h2, h3');
		if (!h) return null;
		var node = h;
		while (node.parentElement && node.parentElement !== wrapper) node = node.parentElement;
		return node.parentElement === wrapper ? node : h;
	}

	function centerHeading(section) {
		var wrapper = section.querySelector('.irantheme-post-simple-post-wrapper');
		if (!wrapper) return;
		var block = headingBlock(wrapper);
		if (block) block.classList.add('sp-blog-head');
	}

	/* returns true when there is nothing left to wait for */
	function stopSlider(section) {
		var el = section.querySelector('.irantheme-blog-post-carousel-swiper');
		if (!el) return true;
		if (el.classList.contains('sp-blog-static')) return true;
		if (!el.swiper) return false; // Swiper not initialized yet
		try {
			el.swiper.destroy(true, true);
		} catch (e) { /* CSS already forces a static row */ }
		el.classList.add('sp-blog-static');
		return true;
	}

	function init() {
		var attempts = 0;
		var timer = window.setInterval(function () {
			var pending = false;
			document.querySelectorAll(SECTION).forEach(function (section) {
				centerHeading(section);
				if (!stopSlider(section)) pending = true;
			});
			attempts += 1;
			if (!pending || attempts >= 40) window.clearInterval(timer);
		}, 250);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
