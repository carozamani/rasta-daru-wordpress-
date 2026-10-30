/**
 * Checkout map: replace Lat/Lng bar with reverse-geocoded address.
 * Parent theme writes coords into #irantheme_lat_display / hidden inputs;
 * we watch those and fill the address via WP AJAX (cURL → Nominatim).
 */
(function ($, window, document) {
	'use strict';

	var cfg = window.spCheckoutMap || {};
	var AJAX = cfg.ajax || '/wp-admin/admin-ajax.php';
	var timer = null;
	var lastKey = '';
	var reqSeq = 0;
	var bound = false;

	function ensureUi() {
		var $box = $('.irantheme-map-coords').first();
		if (!$box.length) {
			return null;
		}

		if (!$box.find('.sp-map-address').length) {
			$box.append(
				'<span class="sp-map-address" role="status" aria-live="polite">' +
					'<i class="iconsax sp-map-address__icon" icon-name="location" aria-hidden="true"></i>' +
					'<span class="sp-map-address__label"></span>' +
					'<span class="sp-map-address__text"></span>' +
					'</span>'
			);
			$box.find('.sp-map-address__label').text((cfg.label || 'آدرس انتخاب‌شده') + ':');
			$box.find('.sp-map-address__text').text(cfg.idle || '—');
		}

		$box.addClass('sp-map-coords--address-only');
		return $box;
	}

	function setAddressText(text, state) {
		var $box = ensureUi();
		if (!$box) {
			return;
		}
		var $row = $box.find('.sp-map-address');
		$row.removeClass('is-loading is-empty is-ready is-idle');
		if (state) {
			$row.addClass(state);
		}
		$row.find('.sp-map-address__text').text(text || '—');
	}

	function readCoords() {
		var lat = parseFloat($('#irantheme_latitude').val());
		var lng = parseFloat($('#irantheme_longitude').val());
		if (!isFinite(lat) || !isFinite(lng) || !lat || !lng) {
			/* Fallback: text displays under the map */
			lat = parseFloat($('#irantheme_lat_display').text());
			lng = parseFloat($('#irantheme_lng_display').text());
		}
		if (!isFinite(lat) || !isFinite(lng) || !lat || !lng) {
			return null;
		}
		return { lat: lat, lng: lng };
	}

	function fetchAddress(lat, lng) {
		var key = lat.toFixed(5) + ',' + lng.toFixed(5);
		if (key === lastKey) {
			return;
		}
		lastKey = key;
		var seq = ++reqSeq;
		setAddressText(cfg.loading || 'در حال دریافت آدرس…', 'is-loading');

		$.ajax({
			url: AJAX,
			method: 'GET',
			dataType: 'json',
			cache: false,
			data: {
				action: 'sp_reverse_geocode',
				lat: lat,
				lng: lng
			},
			timeout: 15000
		})
			.done(function (res) {
				if (seq !== reqSeq) {
					return;
				}
				var addr =
					res && res.success && res.data && res.data.address
						? String(res.data.address)
						: '';
				if (!addr) {
					setAddressText(cfg.empty || 'آدرس یافت نشد', 'is-empty');
					return;
				}
				$('#irantheme_map_address').val(addr);
				/* Keep block-checkout address fields in sync when present */
				$('input[name="irantheme/delivery-map-address"], input[id*="delivery-map-address"]').val(addr);
				setAddressText(addr, 'is-ready');
			})
			.fail(function () {
				if (seq !== reqSeq) {
					return;
				}
				var existing = String($('#irantheme_map_address').val() || '').trim();
				if (existing) {
					setAddressText(existing, 'is-ready');
				} else {
					setAddressText(cfg.empty || 'آدرس یافت نشد', 'is-empty');
				}
			});
	}

	function schedule() {
		clearTimeout(timer);
		var c = readCoords();
		if (!c) {
			lastKey = '';
			setAddressText(cfg.idle || '—', 'is-idle');
			return;
		}
		timer = setTimeout(function () {
			fetchAddress(c.lat, c.lng);
		}, 220);
	}

	function bind() {
		if (!document.getElementById('irantheme_delivery_map') && !document.getElementById('irantheme_delivery_map_block')) {
			return;
		}
		ensureUi();

		if (!bound) {
			bound = true;

			var targets = ['irantheme_lat_display', 'irantheme_lng_display', 'irantheme_block_lat_display', 'irantheme_block_lng_display'];
			if (window.MutationObserver) {
				targets.forEach(function (id) {
					var el = document.getElementById(id);
					if (!el) {
						return;
					}
					new MutationObserver(schedule).observe(el, {
						childList: true,
						characterData: true,
						subtree: true
					});
				});
			}

			$(document).on(
				'change input',
				'#irantheme_latitude, #irantheme_longitude, #irantheme_block_latitude, #irantheme_block_longitude',
				schedule
			);

			$(document).on('click', '#irantheme_clear_map, #irantheme_block_clear_map', function () {
				lastKey = '';
				setAddressText(cfg.idle || '—', 'is-idle');
			});
		}

		schedule();
	}

	$(function () {
		bind();
		window.setTimeout(bind, 500);
		window.setTimeout(bind, 1500);
		window.setTimeout(bind, 3000);
	});
})(jQuery, window, document);
