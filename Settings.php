<?php
namespace Dfe\CurrencyFormat;
use Df\Config\A;
use Magento\Framework\App\ScopeInterface as S;
/** @method static Settings s() */
class Settings extends \Df\Core\Settings {
	/**
	 * 2015-12-30
	 * Форматирование отображения денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 * @param string|null $currencyCode [optional]
	 * @param null|string|int|S $scope [optional]
	 * @return A|O|null
	 */
	public function get($currencyCode = null, $scope = null) {
		/** @var A $result */
		$result = $this->_a(O::class, 'items', $scope);
		return is_null($currencyCode) ? $result : $result->get($currencyCode);
	}

	/**
	 * @override
	 * @see \Df\Core\Settings::prefix()
	 * @used-by \Df\Core\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'dfe_currency/format/';}

	/**
	 * 2016-08-03
	 * @used-by \Dfe\CurrencyFormat\Plugin\Sales\Block\Adminhtml\Items\AbstractItems::aroundDisplayPrices()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatPrecision()
	 * @param bool|null $value
	 * @return bool|null
	 */
	public static function ignorePrecision($value = null) {
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