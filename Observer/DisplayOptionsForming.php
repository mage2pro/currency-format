<?php
namespace Dfe\CurrencyFormat\Observer;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\DataObject;
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
	 * @param O $o
	 */
	function execute(O $o) {
		/** @var DataObject $options */
		$options = $o['currency_options'];
		/** @var \Dfe\CurrencyFormat\O $s */
		$s = Settings::s()->get($o['base_code']);
		if ($s) {
			$options->setData($s->options() + $options->getData());
		}
	}
}

