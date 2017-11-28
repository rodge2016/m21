<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Shopbybrand
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\Shopbybrand\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Filter\FilterManager;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData as AbstractHelper;

/**
 * Class Data
 * @package Mageplaza\Osc\Helper
 */
class Data extends AbstractHelper
{
	/**
	 * Image size default
	 */
	const IMAGE_SIZE = '135x135';

	/**
	 * General configuaration path
	 */
	const GENERAL_CONFIGUARATION = 'shopbybrand/general';

	/**
	 * Brand page configuration path
	 */
	const BRAND_CONFIGUARATION = 'shopbybrand/brandpage';

	/**
	 * Feature brand configuration path
	 */
	const FEATURE_CONFIGUARATION = 'shopbybrand/brandpage/feature';

	/**
	 * Search brand configuration path
	 */
	const SEARCH_CONFIGUARATION = 'shopbybrand/brandpage/search';

	/**
	 * Search brand configuration path
	 */
	const BRAND_DETAIL_CONFIGUARATION = 'shopbybrand/brandview';

	/**
	 * Brand media path
	 */
	const BRAND_MEDIA_PATH = 'mageplaza/brand';

	/**
	 * Default route name
	 */
	const DEFAULT_ROUTE = 'brands';

	/**
	 * @type \Magento\Framework\Filter\FilterManager
	 */
	protected $_filter;

	/**
	 * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 * @param \Magento\Framework\Filter\FilterManager $filter
	 */
	public function __construct(
		Context $context,
		StoreManagerInterface $storeManager,
		ObjectManagerInterface $objectManager,
		FilterManager $filter
	)
	{
		$this->_filter = $filter;

		parent::__construct($context, $objectManager, $storeManager);
	}

	/**
	 * Is enable module on frontend
	 *
	 * @param null $store
	 * @return bool
	 */
	public function isEnabled($store = null)
	{
		$isModuleOutputEnabled = $this->isModuleOutputEnabled();

		return $isModuleOutputEnabled && $this->getGeneralConfig('enabled', $store);
	}

	/**
	 * @return \Magento\Store\Model\StoreManagerInterface
	 */
	public function getStoreManager()
	{
		return $this->storeManager;
	}

	/**
	 * @param $position
	 * @return bool
	 */
	public function canShowBrandLink($position)
	{
		if (!$this->isEnabled()) {
			return false;
		}

		$positionConfig = explode(',', $this->getGeneralConfig('show_position'));

		return in_array($position, $positionConfig);
	}

	/**
	 * @param null $brand
	 * @return string
	 */
	public function getBrandUrl($brand = null)
	{
		$baseUrl = $this->storeManager->getStore()->getBaseUrl();
		$key     = is_null($brand) ? '' : '/' . $this->processKey($brand);

		return $baseUrl . $this->getRoute() . $key . $this->getUrlSuffix();
	}

	/**
	 * @param $brand
	 * @return string
	 */
	public function processKey($brand)
	{
		if (!$brand) {
			return '';
		}

		$str = $brand->getUrlKey() ?: $brand->getDefaultValue();

		return $this->formatUrlKey($str);
	}

	/**
	 * Format URL key from name or defined key
	 *
	 * @param string $str
	 * @return string
	 */
	public function formatUrlKey($str)
	{
		return $this->_filter->translitUrl($str);
	}

	/**
	 * @param $brand
	 * @return string
	 */
	public function getBrandImageUrl($brand)
	{
		if ($brand->getImage()) {
			$image = $brand->getImage();
		} else if ($brand->getSwatchType() == \Magento\Swatches\Model\Swatch::SWATCH_TYPE_VISUAL_IMAGE) {
			$image = \Magento\Swatches\Helper\Media::SWATCH_MEDIA_PATH . $brand->getSwatchValue();
		} else if ($this->getBrandDetailConfig('default_image')) {
			$image = self::BRAND_MEDIA_PATH . '/' . $this->getBrandDetailConfig('default_image');
		} else {
			return \Magento\Framework\App\ObjectManager::getInstance()
				->get('Magento\Catalog\Helper\Image')
				->getDefaultPlaceholderUrl('small_image');
		}

		return $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $image;
	}

	/**
	 * Get Brand Title
	 *
	 * @return string
	 */
	public function getBrandTitle()
	{
		return $this->getGeneralConfig('link_title') ?: __('Brands');
	}

	/**
	 * @param $brand
	 * @param bool|false $short
	 * @return mixed
	 */
	public function getBrandDescription($brand, $short = false)
	{
		if ($short) {
			return $brand->getShortDescription() ?: '';
		}

		return $brand->getDescription() ?: '';
	}

	/************************ General Configuration *************************
	 *
	 * @param      $code
	 * @param null $store
	 * @return mixed
	 */
	public function getGeneralConfig($code = '', $store = null)
	{
		$code = $code ? self::GENERAL_CONFIGUARATION . '/' . $code : self::GENERAL_CONFIGUARATION;

		return $this->getConfigValue($code, $store);
	}

	/**
	 * @param null $store
	 * @return mixed
	 */
	public function getAttributeCode($store = null)
	{
		return $this->getGeneralConfig('attribute', $store);
	}

	/**
	 * Get route name for brand.
	 * If empty, default 'brands' will be used
	 *
	 * @param null $store
	 * @return string
	 */
	public function getRoute($store = null)
	{
		$route = $this->getGeneralConfig('route', $store) ?: self::DEFAULT_ROUTE;

		return $this->formatUrlKey($route);
	}

	/**
	 * Retrieve category rewrite suffix for store
	 *
	 * @param int $storeId
	 * @return string
	 */
	public function getUrlSuffix($storeId = null)
	{
		return $this->scopeConfig->getValue(
			\Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator::XML_PATH_CATEGORY_URL_SUFFIX,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE,
			$storeId
		);
	}

	/************************ Brand Configuration *************************
	 *
	 * @param      $code
	 * @param null $store
	 * @return mixed
	 */
	public function getBrandConfig($code = '', $store = null)
	{
		$code = $code ? self::BRAND_CONFIGUARATION . '/' . $code : self::BRAND_CONFIGUARATION;

		return $this->getConfigValue($code, $store);
	}

	/**
	 * @param string $group
	 * @param null $store
	 * @return array
	 */
	public function getImageSize($group = '', $store = null)
	{
		$imageSize = $this->getBrandConfig($group . 'image_size') ?: self::IMAGE_SIZE;

		return explode('x', $imageSize);
	}

	/************************ Feature Brand Configuration *************************
	 *
	 * @param      $code
	 * @param null $store
	 * @return mixed
	 */
	public function getFeatureConfig($code = '', $store = null)
	{
		$code = $code ? self::FEATURE_CONFIGUARATION . '/' . $code : self::FEATURE_CONFIGUARATION;

		return $this->getConfigValue($code, $store);
	}

	/**
	 * @param null $store
	 * @return mixed
	 */
	public function enableFeature($store = null)
	{
		return $this->getSearchConfig('enable', $store);
	}

	/************************ Search Brand Configuration *************************
	 *
	 * @param      $code
	 * @param null $store
	 * @return mixed
	 */
	public function getSearchConfig($code = '', $store = null)
	{
		$code = $code ? self::SEARCH_CONFIGUARATION . '/' . $code : self::SEARCH_CONFIGUARATION;

		return $this->getConfigValue($code, $store);
	}

	/**
	 * @param null $store
	 * @return mixed
	 */
	public function enableSearch($store = null)
	{
		return $this->getSearchConfig('enable', $store);
	}

	/************************ Brand View Configuration *************************
	 *
	 * @param      $code
	 * @param null $store
	 * @return mixed
	 */
	public function getBrandDetailConfig($code = '', $store = null)
	{
		$code = $code ? self::BRAND_DETAIL_CONFIGUARATION . '/' . $code : self::BRAND_DETAIL_CONFIGUARATION;

		return $this->getConfigValue($code, $store);
	}
}
