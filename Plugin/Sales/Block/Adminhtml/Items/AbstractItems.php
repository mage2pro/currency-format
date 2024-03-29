<?php
namespace Dfe\CurrencyFormat\Plugin\Sales\Block\Adminhtml\Items;
use Dfe\CurrencyFormat\Settings;
use Magento\Sales\Block\Adminhtml\Items\AbstractItems as Sb;
class AbstractItems {
	/**
	 * 2016-08-03 https://mage2.pro/t/1929
	 * @see \Magento\Sales\Block\Adminhtml\Items\AbstractItems::displayPrices()
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Block/Adminhtml/Items/AbstractItems.php#L276-L288
	 * @param float|null $basePrice
	 * @param float|null $price
	 */
	function aroundDisplayPrices(
		Sb $sb, \Closure $f
		/**
		 * 2023-08-06
		 * Magento 2.4.7-beta1 can pass `null` as a money amount:
		 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormat()
		 */
		,$basePrice, $price
		,bool $strong = false, string $sep = '<br />'
	):string {
		/** @var string $r */
		Settings::ignorePrecision(true);
		try {$r = $f($basePrice, $price, $strong, $sep);}
		finally {Settings::ignorePrecision(false);}
		return $r;
	}
}