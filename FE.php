<?php
namespace Dfe\CurrencyFormat;
use Df\Framework\Form\Element\Fieldset;
/**
 * 2015-12-27
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
 * This class is not a singleton:
 * @see \Magento\Framework\Data\Form\AbstractForm::addField():
 * 		$element = $this->_factoryElement->create($type, ['data' => $config]);
 * https://github.com/magento/magento2/blob/2.2.0-RC1.8/lib/internal/Magento/Framework/Data/Form/AbstractForm.php#L137-L159
 */
class FE extends Fieldset {
	/**
	 * 2015-12-27
	 * @override
	 * @see \Df\Framework\Form\Element\Fieldset::onFormInitialized()
	 * @used-by \Df\Framework\Plugin\Data\Form\Element\AbstractElement::afterSetForm()
	 */
	final function onFormInitialized():void {
		parent::onFormInitialized();
		$this->addClass('df-currency-format'); # 2016-07-30 This CSS class will be applied to the <fieldset> DOM node.
		$currencies = df_currencies_options();
		$currenciesCount = df_assert_gt0(count($currencies)); /** @var int $currenciesCount */
		if (1 < $currenciesCount) {
			$this->select(O::code, null, $currencies);
		}
		elseif (1 === $currenciesCount) {
			$currency = df_first($currencies); /** @var array(string => string) $currency */
			$this->hidden(O::code, $currency['value'], $currency['label']);
		}
		$this->checkbox(O::showDecimals, 'Show the Decimals?', true, 'If you hide the decimals then a currency will be shown as <code>512</code> instead of <code>512.00</code>.<br/>The fractional part is rounded: <code>512.79 => 513</code>, <code>512.39 => 512</code>.');
		$this->select(O::decimalSeparator, 'Decimal Separator', ['.', ','], '<code>512.79</code> or <code>512,79</code>?');
		$this->select(O::symbolPosition, 'Currency Sumbol Position', ['before', 'after'], '<code>$512.79</code> or <code>512,79 €</code>?');
		$this->checkbox(O::delimitSymbolFromAmount, 'Delimit Currency Symbol from Amount?', 'If enabled, a currency symbol will be delimited from an amount with the <b><a href="https://en.wikipedia.org/wiki/Thin_space">thin space</a></b>.');
		$this->select(O::thousandsSeparator, 'Thousands Separator'
			,[self::TS__NONE, self::TS__THIN_SPACE, ',', '.']
			, '<code>5120</code> or <code>5&thinsp;120</code> or <code>5,120</code> or <code>5.120</code>?'
		);
		df_fe_init($this, __CLASS__);
	}

	const TS__NONE = 'none';
	const TS__THIN_SPACE = 'thin space';
}