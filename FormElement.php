<?php
namespace Dfe\CurrencyFormat;
use Df\Framework\Data\Form\Element\Fieldset;
use Magento\Config\Model\Config\Source\Locale\Currency as Currencies;
/**
 * 2015-12-27
 * Этот класс не является одиночкой:
 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Data/Form/AbstractForm.php#L155
 */
class FormElement extends Fieldset {
	/**
	 * 2015-12-27
	 * @override
	 * @see \Df\Framework\Data\Form\Element\Fieldset::onFormInitialized()
	 * @used-by \Df\Framework\Data\Form\Element\AbstractElementPlugin::afterSetForm()
	 * @return void
	 */
	public function onFormInitialized() {
		parent::onFormInitialized();
		$this->addClass('df-currency-format');
		$currencies = df_currencies_options();
		/** @var int $currenciesCount */
		$currenciesCount = count($currencies);
		if (1 < $currenciesCount) {
			$this->select('currency', null, $currencies);
		}
		else if (1 === $currenciesCount) {
			/** @var array(string => string) $currency */
			$currency = df_first($currencies);
			$this->hidden('currency', $currency['value'], $currency['label']);
		}
		$this->checkbox('showDecimals', 'Show the Decimals?', true)->setNote(
			'If you hide the decimals then a currency will be shown as <code>512</code> instead of <code>512.00</code>.<br/>The fractional part is rounded: <code>512.79 => 513</code>, <code>512.39 => 512</code>.'
		);
		$this->select('decimalSeparator', 'Decimal Separator', ['.', ','])->setNote(
			'<code>512.79</code> or <code>512,79</code>?'
		);
		$this->select('symbolPosition', 'Currency Sumbol Position', ['before', 'after'])->setNote(
			'<code>$512.79</code> or <code>512,79 €</code>?'
		);
		$this->checkbox('delimitSymbolFromAmount', 'Delimit Currency Symbol from Amount?')
			->setNote('If enabled, a currency symbol will be delimited from an amount with the <b><a href="https://en.wikipedia.org/wiki/Thin_space">thin space</a></b>.'
		);
		$this->select('thousandsSeparator', 'Thousands Separator',
			['none', 'thin space', ',', '.']
		)->setNote('<code>5120</code> or <code>5&thinsp;120</code> or <code>5,120</code> or <code>5.120</code>?');
		df_form_element_init($this, __CLASS__);
	}
}