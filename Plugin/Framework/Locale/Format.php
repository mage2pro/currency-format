<?php
namespace Dfe\CurrencyFormat\Plugin\Framework\Locale;
use Dfe\CurrencyFormat\O as CFO;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\Locale\Format as Sb;
use Magento\Store\Model\Store;
# 2015-12-13
# Хитрая идея, которая уже давно пришла мне в голову: наследуясь от модифицируемого класса,
# мы получаем возможность вызывать методы с областью доступа protected у переменной $sb.
class Format extends Sb {
	/**
	 * 2016-01-01
	 * An empty constructor allows us to skip the parent's one.
	 * Magento (at least at 2016-01-01) is unable to properly inject arguments into a plugin's constructor,
	 * and it leads to the error like: «Missing required argument $amount of Magento\Framework\Pricing\Amount\Base».
	 */
	function __construct() {}

	/**
	 * 2015-12-26
	 * 1) Цель плагина — предоставить администратору возможность
	 * форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 * 2) Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::beforeFormatTxt()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 * 3) @see \Magento\Framework\Locale\Format::getPriceFormat()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L89-L152
	 * @param string|null $currencyCode
	 * @return array(string => mixed)
	 */
	function aroundGetPriceFormat(Sb $sb, \Closure $f, string $localeCode = null, $currencyCode = null):array {
		$r = $f($localeCode, $currencyCode); /** @var array(string => mixed) $r */
		# 2015-12-31
		# https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L101-L105
		$scope = $sb->_scopeResolver->getScope(); /** @var Store $scope */
		if (!$currencyCode) {
			$currencyCode = $scope->getCurrentCurrency()->getCode();
		}
		if ($s = Settings::s()->get($currencyCode, $scope)) { /** @var CFO $s */
			# 2015-12-31
			# https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L143-L144
			if (!$s->showDecimals()) {
				$r = ['precision' => 0, 'requiredPrecision' => 0] + $r;
			}
			# 2015-12-31
			# https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Locale/Format.php#L145-L146
			$r = ['decimalSymbol' => $s->decimalSeparator(), 'groupSymbol' => $s->thousandsSeparator()] + $r;
		}
		return $r;
	}
}