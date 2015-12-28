<?php
namespace Dfe\CurrencyFormat;
use Df\Config\Source\BeforeAfter;
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
		$this->checkbox('showDecimals', 'Show the Decimals?')->setNote(
			'If you hide the decimals then a currency will be shown as <code>512</code> instead of <code>512.00</code>.<br/>The fractional part is rounded: <code>512.79 => 513</code>, <code>512.39 => 512</code>.'
		);
		$this->select('symbolPosition', 'Currency Sumbol Position', BeforeAfter::s());
		df_form_element_init($this, 'main', [], [
			'Dfe_CurrencyFormat::formElement/main.css'
		], 'before');
	}
}