define(['jquery', 'domReady!'], function($) {return (
	/**
	 * 2015-12-27
	 * @param {Object} config
	 * @param {String} config.id
	 */
	function(config) {
		var prepare = function($item) {
			/** @type {jQuery} HTMLInputElement[] */
			var $showDecimals = $('input.df-name-showDecimals', $item);
			var updateDecimalSeparator = function(checkbox) {
				$(checkbox).each(function() {
					/** @type {jQuery} HTMLInputElement */
					var $c = $(this);
					$('.df-field.df-name-decimalSeparator', $c.closest('fieldset.df-currency-format'))
						.toggle($c.is(':checked'))
					;
				});
			};
			$showDecimals.change(function() {updateDecimalSeparator(this);});
			updateDecimalSeparator($showDecimals);
		};
		/** @type {jQuery} HTMLFieldSetElement */
		var $element = $(document.getElementById(config.id));
		$element.hasClass('df-name-template')
			// https://github.com/mage2pro/core/blob/1.0.0/Framework/view/adminhtml/web/formElement/array/main.js#L116
			? $(window).bind('df.config.array.add', function(event, $item) {prepare($item);})
			: prepare($element)
		;
	}
);});