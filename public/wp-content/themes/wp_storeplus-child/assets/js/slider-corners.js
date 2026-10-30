/**
 * Slider corners: clip the whole slider with the rounded corners instead of
 * rounding every slide separately, so the corners stay round while slides move.
 * The radius is read from the first slide, so it follows the page-builder setting.
 */
(function () {
  'use strict';

  var SLIDERS = '.irantheme-swiper-section-slider-simple';

  function apply() {
    var sliders = document.querySelectorAll(SLIDERS);

    for (var i = 0; i < sliders.length; i++) {
      var slider = sliders[i];
      var slide = slider.querySelector('.swiper-slide');
      if (!slide) continue;

      var radius = getComputedStyle(slide).borderTopRightRadius;
      if (!radius || radius === '0px') continue;

      slider.style.setProperty('border-radius', radius, 'important');
      slider.classList.add('sp-clipped');
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', apply);
  } else {
    apply();
  }
  window.addEventListener('load', apply);
})();
