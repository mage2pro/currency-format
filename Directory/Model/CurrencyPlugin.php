<?php
namespace Dfe\CurrencyFormat\Directory\Model;
use Dfe\CurrencyFormat\O;
use Dfe\CurrencyFormat\Settings;
use Magento\Directory\Model\Currency;
class CurrencyPlugin {
	/**
	 * 2015-12-31
	 * Цель плагина — предоставить администратору возможность
	 * форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Framework\Locale\FormatPlugin::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Framework\Pricing\PriceCurrencyInterfacePlugin::beforeFormat()
	 * @see \Dfe\CurrencyFormat\Framework\Pricing\Render\AmountPlugin::beforeFormatCurrency()
	 *
	 * @see \Magento\Directory\Model\Currency::formatTxt()
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Directory/Model/Currency.php#L301-L314
	 *
	 * @param Currency $subject
	 * @param \Closure $proceed
	 * @param float $price
	 * @param array(string => string|int) $options [optional]
	 * @return string
	 */
	public function aroundFormatTxt(Currency $subject, \Closure $proceed, $price, $options = []) {
		/** @var string $result */
		$result = $proceed($price, $options + $this->defaults($subject));
		/** @var array(string => string) $symbols */
		$symbols = \Zend_Locale_Data::getList(df_a($options, 'locale', df_locale()), 'symbols');
		$result = strtr($result, [
			df_a($symbols, 'decimal') => 'decimal'
			, df_a($symbols, 'group') => 'group'
		]);
		/** @var \Dfe\CurrencyFormat\O $s */
		$s = Settings::s()->get($subject->getCode());
		$result = strtr($result, [
			'decimal' => $s->decimalSeparator()
			, 'group' => $s->thousandsSeparator()
		]);
		return $result;
	}

	/**
	 * 2015-12-31
	 * @param Currency $currency
	 * @return array(string => string|int)
	 */
	private function defaults(Currency $currency) {
		/** @var array(string => string|int) $result */
		$result = [];
		/** @var \Dfe\CurrencyFormat\O $s */
		$s = Settings::s()->get($currency->getCode());
		if ($s) {
			if (!$s->showDecimals()) {
				$result['precision'] = 0;
			}
			/** @var string $ds */
			$ds = $s->decimalSeparator();
			/** @var string $ts */
			$ts = $s->thousandsSeparator();
			/** @var string $delimiter */
			$delimiter = !$s->delimitSymbolFromAmount() ? '' : DF_THIN_SPACE;
			/**
			 * 2015-12-31
			 * http://framework.zend.com/manual/1.12/en/zend.locale.parsing.html
			 */
			/** @var string[] $formatA */
			$formatA = ["#,##0.00", $delimiter, '¤'];
			if ('before' === $s->symbolPosition()) {
				$formatA = array_reverse($formatA);
				/**
				 * 2015-12-31
				 * Когда символ валюты надо отобразить слева от суммы,
				 * то по какой-то неведомой причине
				 * недостаточно указать позицию символа валюты в шаблоне (символом ¤),
				 * но также нужно явно указать @uses \Zend_Currency::LEFT значением параметра
				 * «position»,иначе символ валюты вообще не будет отображён.
				 * https://github.com/zendframework/zf1/blob/release-1.12.16/library/Zend/Currency.php#L196-L209
				 */
				$result['position'] = \Zend_Currency::LEFT;
			}
			// 2015-12-31
			// https://github.com/zendframework/zf1/blob/release-1.12.16/library/Zend/Currency.php#L182
			$result['format'] = implode($formatA);
		}
		return $result;
	}
}
