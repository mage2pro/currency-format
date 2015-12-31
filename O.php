<?php
namespace Dfe\CurrencyFormat;
class O extends \Df\Config\O {
	/** @return string */
	public function code() {return $this->ct(__FUNCTION__);}
	/** @return string */
	public function decimalSeparator() {return $this->ct(__FUNCTION__, '.');}
	/** @return bool */
	public function delimitSymbolFromAmount() {return $this->b(__FUNCTION__, false);}

	/**
	 * 2015-12-31
	 * @override
	 * @see \Df\Config\O::getId()
	 * @used-by \Df\Config\A::get()
	 * http://code.dmitry-fedyuk.com/m2/all/blob/dcc75ea95b8644548d8b2a5c5ffa71c891f97e60/Config/A.php#L26
	 * @return string
	 */
	public function getId() {return $this->code();}

	/**
	 * 2015-12-26
	 * «Mage2.PRO» → «Currency» → «Format» → «Number of Decimals»
	 * Zend Framework автоматически округляет цены до заданного количества десятичных знаков:
		if (is_numeric($options['precision'])) {
			$value = Zend_Locale_Math::round($value, $options['precision']);
		}
	 * https://github.com/zendframework/zf1/blob/release-1.12.16/library/Zend/Locale/Format.php#L329
	 * @return bool
	 */
	public function showDecimals() {return $this->b(__FUNCTION__, true);}
	/** @return string */
	public function symbolPosition() {return $this->ct(__FUNCTION__, 'before');}

	/**
	 * 2015-12-31
	 * @return string
	 */
	public function thousandsSeparator() {
		/** @var string $result */
		$result = $this->ct(__FUNCTION__, FormElement::TS__NONE);
		return df_a([
			FormElement::TS__NONE => '', FormElement::TS__THIN_SPACE => DF_THIN_SPACE
		], $result, $result);
	}

	const code = 'code';
	const decimalSeparator = 'decimalSeparator';
	const delimitSymbolFromAmount = 'delimitSymbolFromAmount';
	const showDecimals = 'showDecimals';
	const symbolPosition = 'symbolPosition';
	const thousandsSeparator = 'thousandsSeparator';
}