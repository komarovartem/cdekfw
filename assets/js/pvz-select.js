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
					},
					matcher: function(params, data) {
						if ($.trim(params.term) === '') {
							return data;
						}

						if ($(data.element).data('searchTerm').toString().indexOf(params.term.toLowerCase()) > -1) {
							return data;
						}

						return null;
					}
				});
			}
		});
	}

	$('body').on('change', '#cdekfw-pvz-code', function () {
		var $shippingCountry = $('#shipping_country').val();
		var $billingCountry = $('#billing_country').val();

		if ($shippingCountry && $shippingCountry != 'RU' || $billingCountry && $billingCountry != 'RU') {
			$('body').trigger('update_checkout');
		}
	});

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
		let selectedPointId = jQuery('#cdekfw-pvz-code').val() ? jQuery('#cdekfw-pvz-code').val().split('|')[0] : null;
		let selectedPoint = selectedPointId ? cdekfwYandexMapData.features.filter(point => point.id === selectedPointId)[0] : null;

		cdekfwMapWrapper.css('display', 'flex');
		cdekfwYandexMap.setCenter(selectedPoint.geometry.coordinates);
		cdekfwYandexObjectManager.removeAll();
		cdekfwYandexObjectManager.add(cdekfwYandexMapData);
		selectedPointId && cdekfwYandexObjectManager.objects.balloon.open(selectedPointId);

		return false;
	});

	window.cdekfwSetPvzFromBaloon = function (id) {
		console.log(id)
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
