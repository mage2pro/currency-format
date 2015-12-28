define(['jquery', 'domReady!'], function($) {return (
	/**
	 * 2015-12-27
	 * @param {Object} config
	 * @param {String} config.id
	 */
	function(config) {
		/** @type {jQuery} HTMLFieldSetElement */
		var $element = $(document.getElementById(config.id));
		var updateDecimalSeparator = function(checkbox) {
			$(checkbox).each(function() {
				/** @type {jQuery} HTMLInputElement */
				var $c = $(this);
				$('.df-field.df-name-decimalSeparator', $c.closest('fieldset.df-currency-format'))
					.toggle($c.is(':checked'))
				;
			});
		};
		/** @type {jQuery} HTMLInputElement[] */
		var $showDecimals = $('input.df-name-showDecimals', $element);
		$showDecimals.change(function() {updateDecimalSeparator(this);});
		updateDecimalSeparator($showDecimals);
	}
);});