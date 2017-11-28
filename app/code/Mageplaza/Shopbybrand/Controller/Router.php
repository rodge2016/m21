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
namespace Mageplaza\Shopbybrand\Controller;

/**
 * Class Router
 * @package Mageplaza\Shopbybrand\Controller
 */
class Router implements \Magento\Framework\App\RouterInterface
{
	/** @var \Magento\Framework\App\ActionFactory */
	protected $actionFactory;

	/** @var \Mageplaza\Shopbybrand\Helper\Data */
	protected $_helper;

	/**
	 * @param \Magento\Framework\App\ActionFactory $actionFactory
	 * @param \Mageplaza\Shopbybrand\Helper\Data $helper
	 */
	public function __construct(
		\Magento\Framework\App\ActionFactory $actionFactory,
		\Mageplaza\Shopbybrand\Helper\Data $helper
	)
	{
		$this->actionFactory = $actionFactory;
		$this->_helper       = $helper;
	}

	/**
	 * Validate and Match Brand Page and modify request
	 *
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return \Magento\Framework\App\ActionInterface|null
	 */
	public function match(\Magento\Framework\App\RequestInterface $request)
	{
		$identifier = trim($request->getPathInfo(), '/');

		$urlSuffix = $this->_helper->getUrlSuffix();
		if ($urlSuffix) {
			$pos = strpos($identifier, $urlSuffix);
			if ($pos) {
				$identifier = substr($identifier, 0, $pos);
			} else {
				return null;
			}
		}

		$routePath = explode('/', $identifier);
		$routeSize = sizeof($routePath);
		if (!$this->_helper->isEnabled() ||
			!in_array($routeSize, [1, 2]) ||
			!$this->isRouteBrand($routePath[0])
		) {
			return null;
		}

		$request->setModuleName('mpbrand')
			->setControllerName('index')
			->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, trim($request->getPathInfo(), '/'));

		if ($routeSize == 1) {
			$request->setActionName('index')
				->setPathInfo('/mpbrand/index/index');
		} else {
			$request->setActionName('view')
				->setParam('brand_key', $routePath[1])
				->setPathInfo('/mpbrand/index/view');
		}

		return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
	}

	/**
	 * @param $route
	 * @return bool
	 */
	public function isRouteBrand($route)
	{
		$brandRoute = $this->_helper->getRoute();

		return ($route == $brandRoute);
	}
}
