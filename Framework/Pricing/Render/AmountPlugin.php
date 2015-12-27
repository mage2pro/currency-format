<?php
namespace Dfe\CurrencyFormat\Framework\Pricing\Render;
use Dfe\CurrencyFormat\Settings\Format as Settings;
use Magento\Framework\Pricing\Render\Amount;
class AmountPlugin {
	/**
	 * 2015-12-26
	 * Цель плагина — предоставить администратору возможность
	 * задавать количество отображаемых десятичных знаков для денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format» → «Number of Decimals».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Framework\Locale\FormatPlugin::afterGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Framework\Pricing\PriceCurrencyInterfacePlugin::beforeFormat()
	 *
	 * @see \Magento\Framework\Pricing\Render\Amount::formatCurrency()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/Render/Amount.php#L214-L228
	 *
	 * Обратите внимание:
	 *
	 * 1) Мы подключаем плагин именно к классу @see \Magento\Framework\Pricing\Render\Amount,
	 * а не к интерфейму @see \Magento\Framework\Pricing\Render\AmountRenderInterface,
	 * потому что в интерфейсе метод formatCurrency отсутствует:
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/Render/AmountRenderInterface.php
	 *
	 * 2) Модифицируемый метод @see \Magento\Framework\Pricing\Render\Amount::formatCurrency()
	 * делегирует выполнение работы методу
	 * @see \Magento\Framework\Pricing\PriceCurrencyInterface::format()
	 * return $this->priceCurrency->format($amount, $includeContainer, $precision);
	 * Казалось бы, нам досточно модицировать только метод
	 * @see \Magento\Framework\Pricing\PriceCurrencyInterface::format()
	 * Однако в этом случае в методе
	 * @see \Dfe\CurrencyFormat\Framework\Pricing\PriceCurrencyInterfacePlugin::beforeFormat()
	 * мы утрачиваем информацию, передавал ли программист
	 * явное значение для параметра $precision или нет:
	 * ведь для опущенного параметра $precision
	 * метод @see \Magento\Framework\Pricing\Render\Amount::formatCurrency()
	 * подставляет значение по умолчанию,
	 * и мы не знаем: опустил ли программист параметр или нет.
	 *
	 * @param Amount $subject
	 * @param float $amount
	 * @param bool $includeContainer [optional]
	 * @param int|null $precision [optional]
	 * @return array()
	 */
	public function beforeFormatCurrency(
		Amount $subject, $amount, $includeContainer = true, $precision = null
	) {
		if (is_null($precision) && !Settings::s()->showDecimals()) {
			$precision = 0;
		}
		return [$amount, $includeContainer, $precision];
	}
}