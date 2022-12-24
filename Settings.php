<?php
namespace Dfe\CurrencyFormat;
use Df\Config\A;
use Magento\Framework\App\ScopeInterface as S;
/** @method static Settings s() */
final class Settings extends \Df\Config\Settings {
	/**
	 * 2015-12-30 Форматирование отображения денежных денежных величин: «Mage2.PRO» → «Currency» → «Format».
	 * @used-by \Dfe\CurrencyFormat\Observer\DisplayOptionsForming::execute()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormat()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatPrecision()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatTxt()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Currency::afterToCurrency()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Sales\Model\Order::aroundFormatPrice()
	 * @param null|string|int|S $scope [optional]
	 * @return A|O|null
	 */
	function get(string $currencyC, $scope = null) {return $this->_a(O::class, 'items', $scope)->get($currencyC);}

	/**
	 * @override
	 * @see \Df\Config\Settings::prefix()
	 * @used-by \Df\Config\Settings::v()
	 */
	protected function prefix():string {return 'dfe_currency/format';}

	/**
	 * 2016-08-03
	 * @used-by \Dfe\CurrencyFormat\Plugin\Sales\Block\Adminhtml\Items\AbstractItems::aroundDisplayPrices()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatPrecision()
	 * @param bool|null $v
	 * @return bool|null
	 */
	static function ignorePrecision($v = null) {/** @var bool|null $r */
		if (is_null($v)) {
			$r = self::$_ignorePrecision;
		}
		else {
			self::$_ignorePrecision = $v;
			$r = null;
		}
		return $r;
	}

	/**
	 * 2016-08-03
	 * @used-by self::ignorePrecision()
	 * @var bool
	 */
	private static $_ignorePrecision = false;
}