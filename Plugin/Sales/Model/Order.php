<?php
namespace Dfe\CurrencyFormat\Plugin\Sales\Model;
use Dfe\CurrencyFormat\Settings;
use Magento\Sales\Model\Order as Sb;
class Order {
	/**
	 * 2016-08-01
	 * https://mage2.pro/t/1916
	 * @see \Magento\Sales\Model\Order::formatPrice()
	 * https://github.com/magento/magento2/blob/2db9e0f1/app/code/Magento/Sales/Model/Order.php#L1566-L1576
	 * @param Sb $sb
	 * @param \Closure $f
	 * @param float $price
	 * @param bool $addBrackets [optional]
	 * @return string
	 */
	function aroundFormatPrice(Sb $sb, \Closure $f, $price, $addBrackets = false) {
		/** @var \Dfe\CurrencyFormat\O $s */
		$s = Settings::s()->get($sb->getOrderCurrencyCode());
		return
			!$s || $s->showDecimals()
			? $f($price, $addBrackets)
			: $sb->formatPricePrecision($price, 0, $addBrackets)
		;
	}
}
