<?php
namespace Dfe\CurrencyFormat;
# 2015-12-31
final class O extends \Df\Config\ArrayItem {
	/**
	 * 2015-12-31
	 * @used-by self::postProcess()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 */
	function decimalSeparator():string {return $this->v('.');}

	/**
	 * 2015-12-31
	 * @override
	 * @see \Df\Config\ArrayItem::id()
	 * @used-by \Df\Config\A::get()
	 */
	function id():string {return $this->code();}

	/**
	 * 2016-08-01
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatTxt()
	 * @used-by \Dfe\CurrencyFormat\Observer\DisplayOptionsForming::execute()
	 * @return array(string => string|int|null)
	 */
	function options():array {return dfc($this, function():array {/** @var array(mixed => mixed) $r */
		$r = [];
		if (!$this->showDecimals()) {
			$r['precision'] = 0;
		}
		$delimiter = !$this->delimitSymbolFromAmount() ? '' : DF_THIN_SPACE; /** @var string $delimiter */
		# 2015-12-31 http://framework.zend.com/manual/1.12/en/zend.locale.parsing.html
		$formatA = ["#,##0.00", $delimiter, '¤']; /** @var string[] $formatA */
		if ('before' === $this->symbolPosition()) {
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
			$r['position'] = \Zend_Currency::LEFT;
		}
		# 2015-12-31 https://github.com/zendframework/zf1/blob/release-1.12.16/library/Zend/Currency.php#L182
		return $r + ['format' => implode($formatA)];
	});}

	/**
	 * 2015-12-31 It replaces standard decimals and thousands separators woth custom ones.
	 * 2016-08-01
	 * It should not be called twice on the same string: decimals and thousands separators are vanished in this case.
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Currency::afterToCurrency()
	 * @return string
	 */
	function postProcess(string $priceS, string $l):string {
		$symbols = \Zend_Locale_Data::getList(df_locale($l), 'symbols'); /** @var array(string => string) $symbols */
		/** @var array(string => string) $m */
		$m = ['decimal' => $this->decimalSeparator(), 'group' => $this->thousandsSeparator()];
		$k = array_keys($m); /** @var string[] $k */
		return strtr(strtr($priceS, array_combine(dfa($symbols, $k) + $m, $k)), $m);
	}

	/**
	 * 2015-12-26
	 * «Mage2.PRO» → «Currency» → «Format» → «Number of Decimals»
	 * Zend Framework автоматически округляет цены до заданного количества десятичных знаков:
	 *	if (is_numeric($options['precision'])) {
	 *		$value = Zend_Locale_Math::round($value, $options['precision']);
	 *	}
	 * https://github.com/zendframework/zf1/blob/release-1.12.16/library/Zend/Locale/Format.php#L329
	 * @used-by self::options()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormat()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatPrecision()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render\Amount::beforeFormatCurrency()
	 * @used-by \Dfe\CurrencyFormat\Plugin\Sales\Model\Order::aroundFormatPrice()
	 */
	function showDecimals():bool {return $this->b(true);}

	/**
	 * 2015-12-31
	 */
	function thousandsSeparator():string {
		$r = $this->v(FE::TS__NONE); /** @var string $r */
		return dfa([FE::TS__NONE => '', FE::TS__THIN_SPACE => DF_THIN_SPACE], $r, $r);
	}

	/**
	 * 2015-12-31
	 * @used-by self::id()
	 */
	private function code() {return $this->v();}

	/**
	 * 2015-12-31
	 * @used-by self::options()
	 */
	private function delimitSymbolFromAmount():bool {return $this->b();}

	/** @used-by self::options() */
	private function symbolPosition():string {return $this->v('before');}

	const code = 'code';
	const decimalSeparator = 'decimalSeparator';
	const delimitSymbolFromAmount = 'delimitSymbolFromAmount';
	const showDecimals = 'showDecimals';
	const symbolPosition = 'symbolPosition';
	const thousandsSeparator = 'thousandsSeparator';
}