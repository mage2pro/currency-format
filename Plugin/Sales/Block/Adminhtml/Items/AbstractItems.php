<?php
namespace Dfe\CurrencyFormat\Plugin\Sales\Block\Adminhtml\Items;
use Dfe\CurrencyFormat\Settings;
use Magento\Sales\Block\Adminhtml\Items\AbstractItems as Sb;
class AbstractItems {
	/**
	 * 2016-08-03
	 * https://mage2.pro/t/1929
	 * @see \Magento\Sales\Block\Adminhtml\Items\AbstractItems::displayPrices()
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Block/Adminhtml/Items/AbstractItems.php#L276-L288
	 * @param Sb $sb
	 * @param \Closure $f
	 * @param float $basePrice
	 * @param float $price
	 * @param bool $strong [optional]
	 * @param string $sep [optional]
	 * @return string
	 */
	function aroundDisplayPrices(Sb $sb, \Closure $f, $basePrice, $price, $strong = false, $sep = '<br />') {
		/** @var string $result */
		Settings::ignorePrecision(true);
		try {
			$result = $f($basePrice, $price, $strong, $sep);
		}
		finally {
			Settings::ignorePrecision(false);
		}
		return $result;
	}
}
