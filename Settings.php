<?php
namespace Dfe\CurrencyFormat;
use Magento\Framework\App\ScopeInterface;
class Settings extends \Df\Core\Settings {
	/**
	 * 2015-12-30
	 * «Mage2.PRO» → «Currency» → «Format» → «Currencis»
	 * @param string|null $currencyCode [optional]
	 * @param null|string|int|ScopeInterface $scope [optional]
	 * @return \Df\Config\A|O|null
	 */
	public function get($currencyCode = null, $scope = null) {
		/** @var \Df\Config\A|O|null $result */
		if (!$this->enable($scope)) {
			$result = null;
		}
		else {
			/** @var \Df\Config\A $items */
			$items = $this->_a(__FUNCTION__, O::class, $scope);
			$result = is_null($currencyCode) ? $items : $items->get($currencyCode);
		}
		return $result;
	}

	/**
	 * 2015-12-26
	 * «Mage2.PRO» → «Currency» → «Format» → «Enable?»
	 * @param null|string|int|ScopeInterface $scope [optional]
	 * @return bool
	 */
	public function enable($scope = null) {return $this->b(__FUNCTION__, $scope);}

	/**
	 * @override
	 * @used-by \Df\Core\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'dfe_currency/format/';}

	/** @return $this */
	public static function s() {static $r; return $r ? $r : $r = df_o(__CLASS__);}
}