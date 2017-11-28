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

namespace Mageplaza\Shopbybrand\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageplaza\Shopbybrand\Helper\Data as Helper;
use Mageplaza\Shopbybrand\Model\BrandFactory;

/**
 * Class Brand
 * @package Mageplaza\Shopbybrand\Block
 */
class Brand extends Template
{
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
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	protected function _prepareLayout()
	{
		if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbsBlock->addCrumb(
				'home',
				[
					'label' => __('Home'),
					'title' => __('Go to Home Page'),
					'link'  => $this->_storeManager->getStore()->getBaseUrl()
				]
			);

			$this->additionCrumb($breadcrumbsBlock);
		}

		$this->pageConfig->getTitle()->set($this->getMetaTitle());

		return parent::_prepareLayout();
	}

	protected function additionCrumb($block)
	{
		$title = $this->getPageTitle();
		$block->addCrumb('brand', ['label' => __($title)]);

		return $this;
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
	public function getPageTitle()
	{
		return $this->helper->getBrandConfig('name') ?: __('Brands');
	}

	/**
	 * @return mixed
	 */
	public function getMetaTitle()
	{
		return $this->getPageTitle();
	}

	/**
	 * Retrieve HTML title value separator (with space)
	 *
	 * @param null|string|bool|int|Store $store
	 * @return string
	 */
	public function getTitleSeparator($store = null)
	{
		$separator = (string)$this->_scopeConfig->getValue('catalog/seo/title_separator', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);

		return ' ' . $separator . ' ';
	}

	/**
	 * get brand collection
	 *
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function getCollection()
	{
		if (!$this->_brandCollection) {
			$this->_brandCollection = $this->_brandFactory->create()
				->getBrandCollection();
		}

		return $this->_brandCollection;
	}
}
