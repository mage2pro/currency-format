<?php
namespace Dfe\CurrencyFormat\Plugin\Catalog\Controller\Adminhtml\Product\Initialization\Helper;
use Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper\AttributeFilter as Sb;
use Magento\Catalog\Model\Product as P;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as A;
# 2018-08-30
final class AttributeFilter {
	/**
	 * 2018-08-30
	 * «If a comma is used as a thousands separator,
	 * then a product's price is incorrectly saved on the product's save»:
	 * https://github.com/mage2pro/currency-format/issues/2
	 * @see \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper\AttributeFilter::prepareProductAttributes()
	 * @param array(string => mixed) $data
	 * @param array(string => mixed) $useDefaults
	 */
	function beforePrepareProductAttributes(Sb $sb, P $p, array $data, array $useDefaults):array {
		$params =
			['locale' => new \Zend_Locale(df_locale())]
			+ df_locale_f()->getPriceFormat(df_locale(), $p->getStore()->getBaseCurrencyCode())
		; /** @var array (string => mixed) $params */
		foreach ($p->getAttributes() as $k => $a) { /** @var A $a */
			# 2018-08-31
			# "«Special Price» and «Сost» are set to `0` on a product save":
			# https://github.com/mage2pro/currency-format/issues/3
            if (
				'price' === $a->getFrontendInput()
				/**
				 * 2023-08-02
				 * 1) «Dfe\CurrencyFormat\Plugin\Catalog\Controller\Adminhtml\Product\Initialization\Helper\AttributeFilter::parse():
				 * Argument #1 ($r) must be of type string, null given, called in vendor/mage2pro/currency-format/Plugin/Catalog/Controller/Adminhtml/Product/Initialization/Helper/AttributeFilter.php on line 30»: https://github.com/mage2pro/currency-format/issues/12
				 * 2) «special_price» can be `null` in Magento 2.4.7-beta1.
				 */
				&& !df_nes($v = dfa($data, $k))
			) {  /** @var string $v */
            	# 2018-08-30
				# «Number normalization: getNumber($input, Array $options)»
				# https://framework.zend.com/manual/1.12/en/zend.locale.parsing.html#zend.locale.number.normalize
				$data[$k] = self::parse($v, $params);
			}
        }
		return [$p, $data, $useDefaults];
	}

	/**
	 * 2018-08-30
	 * I have implemented int by analogy with @see \Zend_Locale_Format::getNumber()
	 * https://github.com/zendframework/zf1/blob/release-1.12.20/library/Zend/Locale/Format.php#L230-L286
	 * It is not possible to use @see \Zend_Locale_Format::getNumber() directly
	 * because its validator @uses \Zend_Locale_Format::_checkOptions() does not allow to pass
	 * some of formatting options we use.
	 * @used-by self::beforePrepareProductAttributes()
	 * @param array(string => mixed $o)
	 */
	private static function parse(string $r, array $o):float {
		$r = str_replace($o['groupSymbol'], '', $r);
		$dec = $o['decimalSymbol']; /** @var string $dec */
		if (df_contains($r, $dec)) {
			$r = str_replace($dec, '.', $r);
			$fraction = substr($r, strpos($r, '.') + 1);  /** @var string $fraction */
			$prec = dfa($o, 'precision'); /** @var int|null  $prec */
			$fractionL = strlen($fraction); /** @var int  $fractionL */
			if (null === $prec) {
				$prec = $fractionL;
			}
			if ($prec <= strlen($fraction)) {
				$r = substr($r, 0, strlen($r) - $fractionL + $prec);
			}
			if (!$prec && ('.' === $r[strlen($r) - 1])) {
				$r = substr($r, 0, -1);
			}
		}
		return floatval($r);
	}
}