jQuery(function ($) {
	"use strict";
	if ($().select2) {
		$(document.body).on('update_checkout', function () {
			var $select = $('#cdekfw-pvz-code');
			if ($select.select2()) {
				$select.select2('destroy');
			}
		})

		$(document.body).on('updated_checkout', function () {
			var $select = $('#cdekfw-pvz-code');
			if (!$select.select2()) {
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
});