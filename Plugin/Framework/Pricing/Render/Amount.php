<?php
namespace Dfe\CurrencyFormat\Plugin\Framework\Pricing\Render;
use Dfe\CurrencyFormat\O as CFO;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\Pricing\PriceCurrencyInterface as IPriceCurrency;
use Magento\Framework\Pricing\Render\Amount as Sb;
# 2015-12-13
# Хитрая идея, которая уже давно пришла мне в голову: наследуясь от модифицируемого класса,
# мы получаем возможность вызывать методы с областью доступа protected у переменной $sb.
class Amount extends Sb {
	/**
	 * 2016-01-01
	 * An empty constructor allows us to skip the parent's one.
	 * Magento (at least at 2016-01-01) is unable to properly inject arguments into a plugin's constructor,
	 * and it leads to the error like: «Missing required argument $amount of Magento\Framework\Pricing\Amount\Base».
	 */
	function __construct() {}

	/**
	 * 2015-12-26
	 * 1) Цель плагина — предоставить администратору возможность форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 * 2) Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatTxt()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Locale\Format::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Plugin\Framework\Pricing\PriceCurrencyInterface::beforeFormat()
	 * 3) @see \Magento\Framework\Pricing\Render\Amount::formatCurrency()
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/Render/Amount.php#L214-L228
	 * 4) Обратите внимание:
	 * 4.1) Мы подключаем плагин именно к классу @see \Magento\Framework\Pricing\Render\Amount,
	 * а не к интерфейcу @see \Magento\Framework\Pricing\Render\AmountRenderInterface,
	 * потому что в интерфейсе метод formatCurrency отсутствует:
	 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Pricing/Render/AmountRenderInterface.php
	 * 4.2) Модифицируемый метод @see \Magento\Framework\Pricing\Render\Amount::formatCurrency()
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
	 * @param int|null $precision [optional]
	 */
	function beforeFormatCurrency(
		Sb $sb, float $a, bool $includeContainer = true
		# 2023-08-06
		# 1) "Declare optional argument values for intercepted methods": https://github.com/mage2pro/core/issues/325
		# 2) "Magento does not pass the values of missed optional arguments of intercepted methods to plugins":
		# https://mage2.pro/t/6378
		,$precision = IPriceCurrency::DEFAULT_PRECISION
	):array {
		/**
		 # 2015-12-31
		 # Сюда мы попадаем из шаблона https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Catalog/view/base/templates/product/price/amount/default.phtml
		 # и оттуда мы узнаём валюту:
		 # https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Catalog/view/base/templates/product/price/amount/default.phtml#L30
		 # 		<meta itemprop="priceCurrency" content="<?= $block->getDisplayCurrencyCode()?>" />
		 */
		/** @var CFO $s */
		$s = Settings::s()->get($sb->getDisplayCurrencyCode(), $sb->_storeManager->getStore());
		/**
		 * 2015-12-31
		 * Здесь мы настраиваем только $precision
		 * Другие параметры отображения валюты мы настраиваем в другом плагине:
		 * @see \Dfe\CurrencyFormat\Plugin\Directory\Model\Currency::aroundFormatTxt()
		 */
		/**
		 * 2016-02-17
		 * Раньше тут стояло ещё is_null($precision)
		 * Убрал, потому что в @see \Magento\Checkout\Helper\Data::formatPrice() почему-то явно заданы 2 знака, а не null.
		 * https://github.com/magento/magento2/blob/2ea8cdd7/app/code/Magento/Checkout/Helper/Data.php#L108-L116
		 */
		if ($s && !$s->showDecimals()) {
			$precision = 0;
		}
		return [$a, $includeContainer, $precision];
	}
}