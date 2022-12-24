<?php
namespace Dfe\CurrencyFormat\Plugin\Framework\Pricing;
use Dfe\CurrencyFormat\O as CFO;
use Dfe\CurrencyFormat\Settings;
use Magento\Directory\Model\Currency;
use Magento\Framework\Pricing\PriceCurrencyInterface as Sb;
# 2015-12-26
final class PriceCurrencyInterface {
	/**
	 * 2015-12-26
	 * 1) Цель плагина — предоставить администратору возможность форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::beforeFormatTxt()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 *
	 * @see \Magento\Framework\Pricing\PriceCurrencyInterface::format()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/PriceCurrencyInterface.php#L42-L58
	 *
	 * @see \Magento\Directory\Model\PriceCurrency::format()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/PriceCurrency.php#L70-L82
	 *
	 * @param Sb $sb
	 * @param float $a
	 * @param bool $includeContainer [optional]
	 * @param int|null $precision [optional]
	 * @param null|string|bool|int|\Magento\Framework\App\ScopeInterface $scope [optional]
	 * @param \Magento\Framework\Model\AbstractModel|string|null $currency [optional]
	 * @return array
	 */
	function beforeFormat(Sb $sb, $a, $includeContainer = true, $precision = null, $scope = null, $currency = null) {
		# 2015-12-31 https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/PriceCurrency.php#L80
		$currencyModel = $sb->getCurrency($scope, $currency); /** @var Currency $currencyModel */
		$s = Settings::s()->get($currencyModel->getCode(), $scope); /** @var CFO $s */
		/**
		 * 2015-12-31
		 * Здесь мы настраиваем только $precision.
		 * Другие параметры отображения валюты мы настраиваем в другом плагине:
		 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::beforeFormatTxt()
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