<?php
namespace Dfe\CurrencyFormat\Observer;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\DataObject as _DO;
use Magento\Framework\Event\Observer as O;
use Magento\Framework\Event\ObserverInterface;
/**
 * 2016-08-01
 * Событие: currency_display_options_forming
 * How is the «currency_display_options_forming» event triggered and handled?
 * https://mage2.pro/t/1920
 * @see \Magento\Framework\Locale\Currency::getCurrency()
 */
final class DisplayOptionsForming implements ObserverInterface {
	/**
	 * 2016-08-01
	 * @override
	 * @see ObserverInterface::execute()
	 * @used-by \Magento\Framework\Event\Invoker\InvokerDefault::_callObserverMethod()
	 */
	function execute(O $o):void {
		# 2018-10-11
		# "A conflict with Webkul Marketplace:
		# «Invalid method: Df\Config\A::options» on the backend customer screen":
		# https://github.com/mage2pro/currency-format/issues/4
		# Webkul Marketplace works incorrectly: `$o['base_code']` is null.
		if (($c = $o['base_code']) && ($s = Settings::s()->get($c))) { /** @var \Dfe\CurrencyFormat\O $s */
			$op = $o['currency_options']; /** @var _DO $op */
			$op->setData($s->options() + $op->getData());
		}
	}
}