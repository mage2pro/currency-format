<?php
namespace Dfe\CurrencyFormat\Plugin\Directory\Model;
use Dfe\CurrencyFormat\Settings;
use Magento\Directory\Model\Currency as Sb;
class Currency {
	/**
	 * 2015-12-31
	 * Цель плагина — предоставить администратору возможность
	 * форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 *
	 * @see \Magento\Directory\Model\Currency::formatTxt()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/Currency.php#L301-L314
	 *
	 * @param Sb $sb
	 * @param \Closure $proceed
	 * @param float $price
	 * @param array(string => string|int) $options [optional]
	 * @return string
	 */
	public function aroundFormatTxt(Sb $sb, \Closure $proceed, $price, $options = []) {
		/** @var \Dfe\CurrencyFormat\O $s */
		$s = Settings::s()->get($sb->getCode());
		/**
		 * 2016-08-01
		 * Раньше здесь в конце вызывался метод @see \Dfe\CurrencyFormat\O::postProcess()
		 * однако отныне вызывать его не надо,
		 * потому что метод @see \Magento\Directory\Model\Currency::formatTxt()
		 * приводит к вызову другого нашего плагина
		 * @see \Dfe\CurrencyFormat\Plugin\Framework\Currency::afterToCurrency()
		 * а тот, в свою очередь, уже вызывает @see \Dfe\CurrencyFormat\O::postProcess()
		 * Повторный же вызов @see \Dfe\CurrencyFormat\O::postProcess() не только неэффективен,
		 * но и вреден: после повторного вызова разделители вообще утрачиваются.
		 */
		return $proceed($price, $options + (!$s ? [] : $s->options()));
	}
}
