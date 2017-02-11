<?php
namespace Dfe\CurrencyFormat\Plugin\Directory\Model;
use Dfe\CurrencyFormat\O;
use Dfe\CurrencyFormat\Settings;
use Magento\Directory\Model\Currency as Sb;
class Currency {
	/**
	 * 2016-08-03
	 * https://mage2.pro/t/1929
	 * @see \Magento\Directory\Model\Currency::format()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/Currency.php#L253-L265
	 * @param Sb $sb
	 * @param \Closure $proceed
	 * @param float $price
	 * @param array(string => string|int) $options [optional]
	 * @param bool $includeContainer [optional]
	 * @param bool $addBrackets [optional]
	 * @return string
	 */
	function aroundFormat(
		Sb $sb, \Closure $proceed, $price, $options = []
		, $includeContainer = true, $addBrackets = false
	) {
		/** @var O $s */
		$s = Settings::s()->get($sb->getCode());
		return
			!$s || $s->showDecimals()
			? $proceed($price, $options, $includeContainer, $addBrackets)
			: $sb->formatPrecision($price, 0, $options, $includeContainer, $addBrackets)
		;
	}

	/**
	 * 2016-08-03
	 * https://mage2.pro/t/1929
	 * @see \Magento\Directory\Model\Currency::formatPrecision()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/Currency.php#L267-L294
	 * @param Sb $sb
	 * @param \Closure $proceed
	 * @param float $price
	 * @param int $precision
	 * @param array(string => string|int) $options [optional]
	 * @param bool $includeContainer [optional]
	 * @param bool $addBrackets [optional]
	 * @return string
	 */
	function aroundFormatPrecision(
		Sb $sb, \Closure $proceed, $price, $precision, $options = []
		, $includeContainer = true, $addBrackets = false
	) {
		if (Settings::ignorePrecision()) {
			/** @var O $s */
			$s = Settings::s()->get($sb->getCode());
			if ($s && !$s->showDecimals()) {
				$precision = 0;
			}
		}
		return $proceed($price, $precision, $options, $includeContainer, $addBrackets);
	}

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
	function aroundFormatTxt(Sb $sb, \Closure $proceed, $price, $options = []) {
		/** @var O $s */
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
		 *
		 * 2016-08-03
		 * Мы намеренно ставим $options впереди наших опций,
		 * чтобы можно было нестандартно отформатировать цену явным указанием $options.
		 */
		return $proceed($price, $options + (!$s ? [] : $s->options()));
	}
}
