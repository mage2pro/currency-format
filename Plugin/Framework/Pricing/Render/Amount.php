<?php
namespace Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render;
use Dfe\CurrencyFormat\O as CFO;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\Pricing\Render\Amount as Sb;
# 2015-12-13
# Хитрая идея, которая уже давно пришла мне в голову: наследуясь от модифицируемого класса,
# мы получаем возможность вызывать методы с областью доступа protected у переменной $sb.
class Amount extends Sb {
	/**
	 * 2016-01-01
	 * The empty constructor allows us to skip the parent's one.
	 * Magento (at least on 2016-01-01) is unable to properly inject arguments into a plugin's constructor,
	 * and it leads to the error like: «Missing required argument $amount of Magento\Framework\Pricing\Amount\Base».
	 */
	function __construct() {}

	/**
	 * 2015-12-26
	 * Цель плагина — предоставить администратору возможность
	 * форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::beforeFormatTxt()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 *
	 * @see \Magento\Framework\Pricing\Render\Amount::formatCurrency()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/Render/Amount.php#L214-L228
	 *
	 * Обратите внимание:
	 *
	 * 1) Мы подключаем плагин именно к классу @see \Magento\Framework\Pricing\Render\Amount,
	 * а не к интерфейcу @see \Magento\Framework\Pricing\Render\AmountRenderInterface,
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
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * мы утрачиваем информацию, передавал ли программист
	 * явное значение для параметра $precision или нет:
	 * ведь для опущенного параметра $precision
	 * метод @see \Magento\Framework\Pricing\Render\Amount::formatCurrency()
	 * подставляет значение по умолчанию,
	 * и мы не знаем: опустил ли программист параметр или нет.
	 *
	 * @param Sb $sb
	 * @param float $a
	 * @param bool $includeContainer [optional]
	 * @param int|null $precision [optional]
	 * @return array
	 */
	function beforeFormatCurrency(Sb $sb, $a, $includeContainer = true, $precision = null) {
		/**
		 * 2015-12-31
		 * Сюда мы попадаем из шаблона https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Catalog/view/base/templates/product/price/amount/default.phtml
		 * и оттуда мы узнаём валюту:
		 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Catalog/view/base/templates/product/price/amount/default.phtml#L30
		 * <meta itemprop="priceCurrency" content="<?= $block->getDisplayCurrencyCode()?>" />
		 */
		/** @var CFO $s */
		$s = Settings::s()->get($sb->getDisplayCurrencyCode(), $sb->_storeManager->getStore());
		/**
		 * 2015-12-31
		 * Здесь мы настраиваем только $precision
		 * Другие параметры отображения валюты мы настраиваем в другом плагине:
		 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::beforeFormatTxt()
		 */
		/**
		 * 2016-02-17
		 * Раньше тут стояло ещё is_null($precision)
		 * Убрал, потому что в @see \Magento\Checkout\Helper\Data::formatPrice()
		 * почему-то явно заданы 2 знака, а не null.
		 * https://github.com/magento/magento2/blob/2ea8cdd7/app/code/Magento/Checkout/Helper/Data.php#L108-L116
		 */
		if ($s && !$s->showDecimals()) {
			$precision = 0;
		}
		return [$a, $includeContainer, $precision];
	}
}