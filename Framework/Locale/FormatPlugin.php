<?php
namespace Dfe\CurrencyFormat\Framework\Locale;
use Dfe\CurrencyFormat\Settings as Settings;
use Magento\Framework\Locale\Format;
class FormatPlugin extends Format {
	/**
	 * 2015-12-26
	 * Цель плагина — предоставить администратору возможность
	 * задавать количество отображаемых десятичных знаков для денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format» → «Number of Decimals».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Framework\Pricing\PriceCurrencyInterfacePlugin::beforeFormat()
	 * @see \Dfe\CurrencyFormat\Framework\Pricing\Render\AmountPlugin::beforeFormatCurrency()
	 *
	 * @see \Magento\Framework\Locale\Format::getPriceFormat()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L89-L152
	 *
	 * @param Format $subject
	 * @param \Closure $proceed
	 * @param string $localeCode
	 * @param string $currencyCode
	 * @return array(string => mixed)
	 */
	public function aroundGetPriceFormat(
		Format $subject, \Closure $proceed, $localeCode = null, $currencyCode = null
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
		/** @var \Dfe\CurrencyFormat\O $settings */
		$settings = Settings::s()->get($currencyCode, $scope);
		return !$settings || $settings->showDecimals() ? $result : [
			'precision' => 0, 'requiredPrecision' => 0
		] + $result;
	}
}


