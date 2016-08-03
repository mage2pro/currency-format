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
	 * @param \Closure $proceed
	 * @param float $basePrice
	 * @param float $price
	 * @param bool $strong [optional]
	 * @param string $separator [optional]
	 * @return string
	 */
	public function aroundDisplayPrices(
		Sb $sb, \Closure $proceed, $basePrice, $price, $strong = false, $separator = '<br />'
	) {
		/** @var string $result */
		Settings::ignorePrecision(true);
		try {
			$result = $proceed($basePrice, $price, $strong, $separator);
		}
		finally {
			Settings::ignorePrecision(false);
		}
		return $result;
	}
}
