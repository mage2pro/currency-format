<?php
namespace Dfe\Currency\Framework\Pricing;
use Dfe\Currency\Settings\Format as Settings;
use Magento\Framework\Pricing\PriceCurrencyInterface;
class PriceCurrencyInterfacePlugin {
	/**
	 * 2015-12-26
	 * Цель плагина — предоставить администратору возможность
	 * задавать количество отображаемых десятичных знаков для денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format» → «Number of Decimals».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\Currency\Framework\Locale\FormatPlugin::afterGetPriceFormat()
	 * @see \Dfe\Currency\Framework\Pricing\Render\AmountPlugin::beforeFormatCurrency()
	 *
	 * @see \Magento\Framework\Pricing\PriceCurrencyInterface::format()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/PriceCurrencyInterface.php#L42-L58
	 *
	 * @see \Magento\Directory\Model\PriceCurrency::format()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/PriceCurrency.php#L70-L82
	 *
	 * @param PriceCurrencyInterface $subject
	 * @param float $amount
	 * @param bool $includeContainer [optional]
	 * @param int|null $precision [optional]
	 * @param null|string|bool|int|\Magento\Framework\App\ScopeInterface $scope [optional]
	 * @param \Magento\Framework\Model\AbstractModel|string|null $currency [optional]
	 * @return array()
	 */
	public function beforeFormat(
		PriceCurrencyInterface $subject
		, $amount
		, $includeContainer = true
		, $precision = null
		, $scope = null
		, $currency = null
	) {
		if (is_null($precision) && Settings::s()->enable($scope)) {
			$precision = Settings::s()->numberOfDecimals($scope);
		}
		return [$amount, $includeContainer, $precision, $scope, $currency];
	}
}