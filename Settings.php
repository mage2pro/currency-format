<?php
namespace Dfe\CurrencyFormat;
use Df\Config\A;
use Magento\Framework\App\ScopeInterface as S;
/** @method static Settings s() */
final class Settings extends \Df\Config\Settings {
	/**
	 * 2015-12-30
	 * Форматирование отображения денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 * @param string|null $currencyCode [optional]
	 * @param null|string|int|S $scope [optional]
	 * @return A|O|null
	 */
	function get($currencyCode = null, $scope = null) {
		$r = $this->_a(O::class, 'items', $scope); /** @var A $r */
		return is_null($currencyCode) ? $r : $r->get($currencyCode);
	}

	/**
	 * @override
	 * @see \Df\Config\Settings::prefix()
	 * @used-by \Df\Config\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'dfe_currency/format';}

	/**
	 * 2016-08-03
	 * @used-by \Dfe\CurrencyFormat\Plugin\Sales\Block\Adminhtml\Items\AbstractItems::aroundDisplayPrices()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatPrecision()
	 * @param bool|null $value
	 * @return bool|null
	 */
	static function ignorePrecision($value = null) {
		/** @var bool|null $result */
		if (is_null($value)) {
			$result = self::$_ignorePrecision;
		}
		else {
			self::$_ignorePrecision = $value;
			$result = null;
		}
		return $result;
	}

	/**
	 * 2016-08-03
	 * @var bool
	 */
	private static $_ignorePrecision = false;
}