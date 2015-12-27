<?php
namespace Dfe\CurrencyFormat;
use Df\Framework\Data\Form\Element\Fieldset;
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
	}
}