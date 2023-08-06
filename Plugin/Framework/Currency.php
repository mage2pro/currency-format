<?php
namespace Dfe\CurrencyFormat\Plugin\Framework;
use Dfe\CurrencyFormat\O as CFO;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\Currency as Sb;
# 2023-08-06
# "Prevent interceptors generation for the plugins extended from interceptable classes":
# https://github.com/mage2pro/core/issues/327
class Currency extends Sb implements \Magento\Framework\ObjectManager\NoninterceptableInterface {
	/** 2016-08-01 */
	function __construct() {}

	/**
	 * 2016-08-01
	 * @see \Magento\Framework\Currency::toCurrency()
	 */
	function afterToCurrency(Sb $sb, string $r):string {
		$s = Settings::s()->get($sb->_options['currency']); /** @var CFO $s */
		return !$s ? $r : $s->postProcess($r, $sb->_options['locale']);
	}
}