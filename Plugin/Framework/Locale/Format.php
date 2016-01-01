<?php
namespace Dfe\CurrencyFormat\Plugin\Framework\Locale;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\Locale\Format as _Format;
// 2015-12-13
// Хитрая идея, которая уже давно пришла мне в голову: наследуясь от модифицируемого класса,
// мы получаем возможность вызывать методы с областью доступа protected у переменной $subject.
class Format extends _Format {
	/**
	 * 2016-01-01
	 * Потрясающая техника, которую я изобрёл только что.
	 */
	public function __construct() {}

	/**
	 * 2015-12-26
	 * Цель плагина — предоставить администратору возможность
	 * форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::beforeFormatTxt()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 *
	 * @see \Magento\Framework\Locale\Format::getPriceFormat()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L89-L152
	 *
	 * @param _Format $subject
	 * @param \Closure $proceed
	 * @param string $localeCode
	 * @param string $currencyCode
	 * @return array(string => mixed)
	 */
	public function aroundGetPriceFormat(
		_Format $subject, \Closure $proceed, $localeCode = null, $currencyCode = null
	) {
		/** @var array(string => mixed) $result */
		$result = $proceed();
		/**
		 * 2015-12-31
		 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L101-L105
		 */
		/** @var \Magento\Store\Model\Store $scope */
		$scope = $subject->_scopeResolver->getScope();
		if (!$currencyCode) {
			$currencyCode = $scope->getCurrentCurrency()->getCode();
		}
		/** @var \Dfe\CurrencyFormat\O $s */
		$s = Settings::s()->get($currencyCode, $scope);
		if ($s) {
			// 2015-12-31
			// https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L143-L144
			if (!$s->showDecimals()) {
				$result = ['precision' => 0, 'requiredPrecision' => 0] + $result;
			}
			// 2015-12-31
			// https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L145-L146
			$result = [
				  'decimalSymbol' => $s->decimalSeparator()
				  , 'groupSymbol' => $s->thousandsSeparator()
			] + $result;
		}
		return $result;
	}
}


