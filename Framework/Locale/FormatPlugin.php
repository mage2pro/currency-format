<?php
namespace Dfe\CurrencyFormat\Framework\Locale;
use Dfe\CurrencyFormat\Settings\Format as Settings;
use Magento\Framework\Locale\Format;
class FormatPlugin {
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
	 * @param array(string => mixed) $result
	 * @return array(string => mixed)
	 */
	public function afterGetPriceFormat(Format $subject, array $result) {
		return Settings::s()->showDecimals() ? $result : [
			'precision' => 0, 'requiredPrecision' => 0
		] + $result;
	}
}


