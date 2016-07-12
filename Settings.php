<?php
namespace Dfe\CurrencyFormat;
use Magento\Framework\App\ScopeInterface as S;
/** @method static Settings s() */
class Settings extends \Df\Core\Settings {
	/**
	 * 2015-12-30
	 * Форматирование отображения денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 * @param string|null $currencyCode [optional]
	 * @param null|string|int|S $scope [optional]
	 * @return \Df\Config\A|O|null
	 */
	public function get($currencyCode = null, $scope = null) {
		/** @var \Df\Config\A|O|null $result */
		if (!$this->enable($scope)) {
			$result = null;
		}
		else {
			/** @var \Df\Config\A $items */
			$items = $this->_a('items', O::class, $scope);
			$result = is_null($currencyCode) ? $items : $items->get($currencyCode);
		}
		return $result;
	}

	/**
	 * 2015-12-26
	 * «Mage2.PRO» → «Currency» → «Format» → «Enable?»
	 * @param null|string|int|S $scope [optional]
	 * @return bool
	 */
	public function enable($scope = null) {return $this->b(__FUNCTION__, $scope);}

	/**
	 * @override
	 * @used-by \Df\Core\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'dfe_currency/format/';}
}