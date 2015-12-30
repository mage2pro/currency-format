<?php
namespace Dfe\CurrencyFormat;
class O extends \Df\Config\O {
	/** @return string */
	public function code() {return $this->ct(__FUNCTION__);}
	/** @return string */
	public function decimalSeparator() {return $this->ct(__FUNCTION__, '.');}
	/** @return bool */
	public function delimitSymbolFromAmount() {return $this->b(__FUNCTION__, false);}
	/** @return bool */
	public function showDecimals() {return $this->b(__FUNCTION__, true);}
	/** @return string */
	public function symbolPosition() {return $this->ct(__FUNCTION__, 'before');}
	/** @return string */
	public function thousandsSeparator() {return $this->ct(__FUNCTION__, 'none');}

	const code = 'code';
	const decimalSeparator = 'decimalSeparator';
	const delimitSymbolFromAmount = 'delimitSymbolFromAmount';
	const showDecimals = 'showDecimals';
	const symbolPosition = 'symbolPosition';
	const thousandsSeparator = 'thousandsSeparator';
}