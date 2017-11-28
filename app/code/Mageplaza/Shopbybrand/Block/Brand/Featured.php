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
namespace Mageplaza\Shopbybrand\Block\Brand;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\Shopbybrand\Helper\Data as Helper;
use Mageplaza\Shopbybrand\Model\BrandFactory;

/**
 * Class Featured
 * @package Mageplaza\Shopbybrand\Block\Brand
 */
class Featured extends Template
{
	/**
	 * Default feature template
	 *
	 * @type string
	 */
	protected $_template = 'brand/featured.phtml';

	/**
	 * @type \Mageplaza\Shopbybrand\Helper\Data
	 */
	protected $helper;

	/**
	 * @type \Mageplaza\Shopbybrand\Model\BrandFactory
	 */
	protected $_brandFactory;

	/**
	 * @type
	 */
	protected $_brandCollection;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param \Mageplaza\Shopbybrand\Helper\Data $helper
	 * @param \Mageplaza\Shopbybrand\Model\BrandFactory $brandFactory
	 */
	public function __construct(
		Context $context,
		Helper $helper,
		BrandFactory $brandFactory
	)
	{
		$this->helper        = $helper;
		$this->_brandFactory = $brandFactory;

		parent::__construct($context);
	}

	/**
	 * @return string
	 */
	function includeCssLib()
	{
		$cssFiles = ['Mageplaza_Core::css/owl.carousel.css', 'Mageplaza_Core::css/owl.theme.css'];
		$template = '<link rel="stylesheet" type="text/css" media="all" href="%s">' . "\n";
		$result   = '';
		foreach ($cssFiles as $file) {
			$asset = $this->_assetRepo->createAsset($file);
			$result .= sprintf($template, $asset->getUrl());
		}

		return $result;
	}

	/**
	 * @return \Mageplaza\Shopbybrand\Helper\Data
	 */
	public function helper()
	{
		return $this->helper;
	}

	/**
	 * @return mixed
	 */
	public function getFeatureTitle()
	{
		return $this->helper->getFeatureConfig('name');
	}

	/**
	 * @return bool
	 */
	public function showLabel()
	{
		return $this->helper->getFeatureConfig('display') == \Mageplaza\Shopbybrand\Model\Config\Source\FeatureDisplay::DISPLAY_LOGO_AND_LABEL;
	}

	/**
	 * @return bool
	 */
	public function showTitle()
	{
		$actionName = $this->getRequest()->getFullActionName();
		if ($actionName != 'mpbrand_index_index') {
			return true;
		}

		return !$this->helper->enableSearch();
	}

	/**
	 * get feature brand
	 * @return mixed
	 */
	public function getFeaturedBrand()
	{
		$featureBrands = [];
		$collection    = $this->_brandFactory->create()
			->getBrandCollection();
		foreach ($collection as $brand) {
			if ($brand->getIsFeatured()) {
				$featureBrands[] = $brand;
			}
		}

		return $featureBrands;
	}
}
