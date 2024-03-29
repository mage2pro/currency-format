<?php
namespace Dfe\CurrencyFormat\Plugin\Sales\Model;
use Dfe\CurrencyFormat\O as CFO;
use Dfe\CurrencyFormat\Settings;
use Magento\Sales\Model\Order as Sb;
class Order {
	/**
	 * 2016-08-01 https://mage2.pro/t/1916
	 * @see \Magento\Sales\Model\Order::formatPrice()
	 * https://github.com/magento/magento2/blob/2db9e0f1/app/code/Magento/Sales/Model/Order.php#L1566-L1576
	 * @param float|null $price
	 */
	function aroundFormatPrice(
		Sb $sb, \Closure $f
		/**
		 * 2023-08-06
		 * Magento 2.4.7-beta1 can pass `null` as a money amount:
		 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormat()
		 */
		,$price
		,bool $addBrackets = false
	):string {return
		/** @var CFO $s */!($s = Settings::s()->get($sb->getOrderCurrencyCode())) || $s->showDecimals()
			? $f($price, $addBrackets)
			: $sb->formatPricePrecision($price, 0, $addBrackets)
	;}
}