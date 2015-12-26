<?php
namespace Dfe\Currency\Settings;
use Magento\Framework\App\ScopeInterface;
class Format extends \Df\Core\Settings {
	/**
	 * 2015-12-26
	 * «Mage2.PRO» → «Currency» → «Format» → «Number of Decimals»
	 * @param null|string|int|ScopeInterface $scope [optional]
	 * @return int
	 */
	public function numberOfDecimals($scope = null) {return $this->nat0(__FUNCTION__, $scope);}

	/**
	 * @override
	 * @used-by \Df\Core\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'dfe_currency/format/';}

	/** @return $this */
	public static function s() {static $r; return $r ? $r : $r = df_o(__CLASS__);}
}