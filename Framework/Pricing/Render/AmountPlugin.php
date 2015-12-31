<?php
namespace Dfe\CurrencyFormat\Framework\Pricing\Render;
use Dfe\CurrencyFormat\Settings;
use Magento\Framework\Pricing\Render\Amount;
// 2015-12-13
// Хитрая идея, которая уже давно пришла мне в голову: наследуясь от модифицируемого класса,
// мы получаем возможность вызывать методы с областью доступа protected у переменной $subject.
class AmountPlugin extends Amount {
	/**
	 * 2016-01-01
	 * Потрясающая техника, которую я изобрёл только что.
	 * Ещё вчера я писал:
	 * «К сожалению, мы не можем унаследоваться от @see Magento\Framework\Pricing\Render\Amount
	 * и получить доступ к scope так: $scope = $subject->_storeManager->getStore();
	 * потому что у Magento не получится тогда автоматически сконструировать наш объект:
	 * «Missing required argument $amount of Magento\Framework\Pricing\Amount\Base».»
	 */
	public function __construct() {}

	/**
	 * 2015-12-26
	 * Цель плагина — предоставить администратору возможность
	 * форматировать отображение денежных денежных величин:
	 * «Mage2.PRO» → «Currency» → «Format».
	 *
	 * Помимо этого плагина для решения поставленной задачи нам нужны также плагины:
	 * @see \Dfe\CurrencyFormat\Directory\Model\CurrencyPlugin::beforeFormatTxt()
	 * @see \Dfe\CurrencyFormat\Framework\Locale\FormatPlugin::aroundGetPriceFormat()
	 * @see \Dfe\CurrencyFormat\Framework\Pricing\PriceCurrencyInterfacePlugin::beforeFormat()
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
		/**
		 * 2015-12-31
		 * Сюда мы попадаем из шаблона https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Catalog/view/base/templates/product/price/amount/default.phtml
		 * и оттуда мы узнаём валюту:
		 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Catalog/view/base/templates/product/price/amount/default.phtml#L30
		 * <meta itemprop="priceCurrency" content="<?php echo $block->getDisplayCurrencyCode()?>" />
		 */
		/** @var \Dfe\CurrencyFormat\O $settings */
		$settings = Settings::s()->get(
			$subject->getDisplayCurrencyCode(), $subject->_storeManager->getStore()
		);
		/**
		 * 2015-12-31
		 * Здесь мы настраиваем только $precision
		 * Другие параметры отображения валюты мы настраиваем в другом плагине:
		 * @see \Dfe\CurrencyFormat\Directory\Model\CurrencyPlugin::beforeFormatTxt()
		 */
		if (is_null($precision) && $settings && !$settings->showDecimals()) {
			$precision = 0;
		}
		return [$amount, $includeContainer, $precision];
	}
}