<?php
namespace Dfe\CurrencyFormat\Plugin\Framework;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\Currency as Sb;
class Currency extends Sb {
	/** 2016-08-01 */
	function __construct() {}

	/**
	 * 2016-08-01
	 * @see \Magento\Framework\Currency::toCurrency()
	 * @param Sb $sb
	 * @param string $result
	 * @return string
	 */
	function afterToCurrency(Sb $sb, $result) {
		/** @var \Dfe\CurrencyFormat\O $s */
		$s = Settings::s()->get($sb->_options['currency']);
		return !$s ? $result : $s->postProcess($result, $sb->_options['locale']);
	}
}


