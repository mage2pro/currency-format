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
	/** 2016-01-01 Потрясающая техника, которую я изобрёл только что. */
	function __construct() {}

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
	 * @param Sb $sb
	 * @param \Closure $f
	 * @param string $localeCode
	 * @param string $currencyCode
	 * @return array(string => mixed)
	 */
	function aroundGetPriceFormat(Sb $sb, \Closure $f, $localeCode = null, $currencyCode = null) {
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