jQuery(function ($) {
	"use strict";
	if ($().select2) {
		$(document.body).on('update_checkout', function () {
			var $select = $('#cdekfw-pvz-code');
			if ($select.data('select2')) {
				$select.select2('destroy');
			}
		})

		$(document.body).on('updated_checkout', function () {
			var $select = $('#cdekfw-pvz-code');
			if (!$select.data('select2')) {
				$select.select2({
					language: {
						noResults: function () {
							return $select.data('noresults');
						}
					}
				});
			}
		});
	}

	if (typeof cdekfwYandexMap === "undefined") {
		$('#cdekfw-map-trigger').hide();
	}

	$('#cdekfw-yandex-map-backdrop').click(function () {
		$('#cdekfw-yandex-map-wrapper').css('display', 'none')
	})

	if (typeof ymaps !== "undefined") {
		ymaps.ready(initcdekfwYandexMap);
	}

	window.cdekfwYandexMap = window.cdekfwYandexObjectManager = window.cdekfwMapWrapper = null;

	window.cdekfwMapWrapper = $('#cdekfw-yandex-map-wrapper');

	$('body').on('click', '#cdekfw-map-trigger', function () {
		cdekfwMapWrapper.css('display', 'flex');
		cdekfwYandexMap.setCenter($('#cdekfw-map-trigger').data('map-center'));
		cdekfwYandexObjectManager.removeAll();
		cdekfwYandexObjectManager.add(cdekfwYandexMapData);
		return false;
	});

	window.cdekfwSetPvzFromBaloon = function(id) {
		$('#cdekfw-pvz-code').val(id).trigger('change');
		cdekfwMapWrapper.css('display', 'none');
	}

	function initcdekfwYandexMap() {
		$('#cdekfw-ekom-map-trigger').show();
		cdekfwYandexMap = new ymaps.Map("cdekfw-yandex-map", {
			center: [55.76, 37.64],
			zoom: 12,
			behaviors: ['default', 'scrollZoom'],
			controls: []
		});

		cdekfwYandexObjectManager = new ymaps.ObjectManager({
			clusterize: true
		});

		cdekfwYandexMap.geoObjects.add(cdekfwYandexObjectManager);
	}
});
