<?php
namespace Dfe\CurrencyFormat\Plugin\Directory\Model;
use Dfe\CurrencyFormat\O;
use Dfe\CurrencyFormat\Settings;
use Magento\Directory\Model\Currency as Sb;
use Magento\Framework\Pricing\PriceCurrencyInterface as IPriceCurrency;
class Currency {
	/**
	 * 2016-08-03 https://mage2.pro/t/1929
	 * @see \Magento\Directory\Model\Currency::format()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/Currency.php#L253-L265
	 * @param float|null $price
	 * @param array(string => string|int) $opt [optional]
	 */
	function aroundFormat(
		Sb $sb, \Closure $f
		/**
		 * 2023-07-19
		 *  1) «TypeError: Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormat():
		 *  Argument #3 ($price) must be of type float, null given»: https://github.com/mage2pro/currency-format/issues/11
		 *  2) Magento 2.4.7-beta1 can pass `null` as $price:
		 *  The call stack:
		 *        2.1) @see \Magento\Backend\Block\Dashboard\Totals::_prepareLayout():
		 *            $this->addTotal(__('Revenue'), $totals->getRevenue());
		 *        $totals->getRevenue() can be `null` there.
		 *        https://github.com/magento/magento2/blob/2.4.7-beta1/app/code/Magento/Backend/Block/Dashboard/Totals.php#L108-L108
		 *        2.2) @see \Magento\Backend\Block\Dashboard\Bar::addTotal():
		 *            $value = $this->format($value);
		 *        https://github.com/magento/magento2/blob/2.4.7-beta1/app/code/Magento/Backend/Block/Dashboard/Bar.php#L54-L54
		 *        2.3) @see \Magento\Backend\Block\Dashboard\Bar::format():
		 *            return $this->getCurrency()->format($price);
		 *        https://github.com/magento/magento2/blob/2.4.7-beta1/app/code/Magento/Backend/Block/Dashboard/Bar.php#L70-L70
		 */
		,$price
		,array $opt = [], bool $container = true, bool $brackets = false
	):string {
		$s = Settings::s()->get($sb->getCode());  /** @var O $s */
		return
			!$s || $s->showDecimals()
			? $f($price, $opt, $container, $brackets)
			: $sb->formatPrecision($price, 0, $opt, $container, $brackets)
		;
	}

	/**
	 * 2016-08-03
	 * https://mage2.pro/t/1929
	 * @see \Magento\Directory\Model\Currency::formatPrecision()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/Currency.php#L267-L294
	 * @param int|null $precision
	 * @param float|null $price
	 * @param array(string => string|int) $options [optional]
	 */
	function aroundFormatPrecision(
		Sb $sb, \Closure $f
		,$price /** 2023-07-19 Magento 2.4.7-beta1 can pass `null` as $price: @see self::aroundFormat() */
		/**
		 * 2023-08-06
		 * 1) "Declare optional argument values for intercepted methods": https://github.com/mage2pro/core/issues/325
		 * 2) "Magento does not pass the values of missed optional arguments of intercepted methods to plugins":
		 * https://mage2.pro/t/6378
		 * 3) «Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatPrecision():
		 *  Argument #4 ($precision) must be of type int, null given»: https://github.com/mage2pro/currency-format/issues/14
		 */
		,int $precision = IPriceCurrency::DEFAULT_PRECISION
		,array $options = [], bool $container = true, bool $brackets = false
	):string {
		if (Settings::ignorePrecision()) {
			$s = Settings::s()->get($sb->getCode()); /** @var O $s */
			if ($s && !$s->showDecimals()) {
				$precision = 0;
			}
		}
		return $f($price, $precision, $options, $container, $brackets);
	}

	/**
	 * 2015-12-31
	 * 1) Цель плагина — предоставить администратору возможность форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 * 2) Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 * 3) @see \Magento\Directory\Model\Currency::formatTxt()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/Currency.php#L301-L314
	 * @param float|null $price
	 * @param array(string => string|int) $options [optional]
	 */
	function aroundFormatTxt(
		Sb $sb, \Closure $f
		,$price /** 2023-07-19 Magento 2.4.7-beta1 can pass `null` as $price: @see self::aroundFormat() */
		,array $options = []
	):string {
		$s = Settings::s()->get($sb->getCode());  /** @var O $s */
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
		 * 2016-08-03
		 * Мы намеренно ставим $options впереди наших опций,
		 * чтобы можно было нестандартно отформатировать цену явным указанием $options.
		 */
		return $f($price, $options + (!$s ? [] : $s->options()));
	}
}