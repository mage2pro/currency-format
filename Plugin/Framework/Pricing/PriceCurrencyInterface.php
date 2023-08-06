<?php
namespace Dfe\CurrencyFormat\Plugin\Framework\Pricing;
use Dfe\CurrencyFormat\O as CFO;
use Dfe\CurrencyFormat\Settings;
use Magento\Directory\Model\Currency;
use Magento\Framework\Pricing\PriceCurrencyInterface as IPriceCurrency;
use Magento\Framework\Pricing\PriceCurrencyInterface as Sb;
# 2015-12-26
final class PriceCurrencyInterface {
	/**
	 * 2015-12-26
	 * 1) Цель плагина — предоставить администратору возможность форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 * 2) Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatTxt()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 * 3) @see \Magento\Framework\Pricing\PriceCurrencyInterface::format()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/PriceCurrencyInterface.php#L42-L58
	 * 4) @see \Magento\Directory\Model\PriceCurrency::format()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/PriceCurrency.php#L70-L82
	 * @used-by \Magento\Framework\Pricing\Render\Amount::formatCurrency():
	 *		return $this->priceCurrency->format($amount, $includeContainer, $precision);
	 * https://github.com/magento/magento2/blob/2.4.7-beta1/lib/internal/Magento/Framework/Pricing/Render/Amount.php#L214-L228
	 * @param null|string|bool|int|\Magento\Framework\App\ScopeInterface $scope [optional]
	 * @param \Magento\Framework\Model\AbstractModel|string|null $currency [optional]
	 */
	function beforeFormat(
		Sb $sb
		/**
		 * 2023-08-05
		 * 1) «Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat():
		 * Argument #2 ($a) must be of type float, null given»: https://github.com/mage2pro/currency-format/issues/13
		 * 2) Magento 2.4.7-beta1 can pass `null` as a money amount:
		 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormat()
		 */
		,$a
		,bool $includeContainer = true
		# 2023-08-06
		# 1) "Declare optional argument values for intercepted methods": https://github.com/mage2pro/core/issues/325
		# 2) "Magento does not pass the values of missed optional arguments of intercepted methods to plugins":
		# https://mage2.pro/t/6378
		,int $precision = IPriceCurrency::DEFAULT_PRECISION
		,$scope = null, $currency = null
	):array {
		# 2015-12-31 https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/PriceCurrency.php#L80
		$currencyModel = $sb->getCurrency($scope, $currency); /** @var Currency $currencyModel */
		$s = Settings::s()->get($currencyModel->getCode(), $scope); /** @var CFO $s */
		/**
		 * 2015-12-31
		 * Здесь мы настраиваем только $precision.
		 * Другие параметры отображения валюты мы настраиваем в другом плагине:
		 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatTxt()
		 * 2016-02-17
		 * Раньше тут стояло ещё `is_null($precision)`.
		 * Убрал, потому что в @see \Magento\Checkout\Helper\Data::formatPrice() почему-то явно заданы 2 знака, а не null.
		 * https://github.com/magento/magento2/blob/2ea8cdd7/app/code/Magento/Checkout/Helper/Data.php#L108-L116
		 */
		if ($s && !$s->showDecimals()) {
			$precision = 0;
		}
		return [$a, $includeContainer, $precision, $scope, $currency];
	}
}