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
namespace Mageplaza\Shopbybrand\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class CheckRewritePath
 * @package Mageplaza\Shopbybrand\Observer
 */
class CheckRewritePath implements ObserverInterface
{
	/**
	 * @type \Mageplaza\Shopbybrand\Helper\Data
	 */
	protected $_helper;

	/**
	 * @param \Mageplaza\Shopbybrand\Helper\Data $helper
	 */
	public function __construct(\Mageplaza\Shopbybrand\Helper\Data $helper)
	{
		$this->_helper = $helper;
	}

	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 * @return void
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$object = $observer->getEvent()->getObject();

		$pathInfo  = $object->getData('pathInfo');
		$routePath = explode('/', $pathInfo);

		if ((sizeof($routePath) == 2) && (array_shift($routePath) == $this->_helper->getRoute())) {
			$object->setData('rewrite', true);
		}
	}
}
