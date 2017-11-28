<?php
/**
 * Copyright © 2016 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Observer;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Store\Model\Store;
use \Magento\Theme\Block\Html\Breadcrumbs;
use \MagestyApps\AdvancedBreadcrumbs\Helper\Additional;
use \MagestyApps\AdvancedBreadcrumbs\Helper\Data;

class AdditionalPagesBreadcrumbs implements ObserverInterface
{
    /** @var Data $_dataHelper */
    protected $_dataHelper;

    /** @var Additional $_addPagesHelper */
    protected $_addPagesHelper;

    /** @var StoreManagerInterface $_storeManager */
    protected $_storeManager;

    /** @var Store $_store */
    protected $_store;

    public function __construct(
        Additional $additionalPagesHelper,
        Data $dataHelper,
        StoreManagerInterface $storeManager,
        Store $store
    ) {
        $this->_addPagesHelper = $additionalPagesHelper;
        $this->_dataHelper = $dataHelper;
        $this->_storeManager = $storeManager;
        $this->_store = $store;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\View\Element\AbstractBlock $block */
        $block = $observer->getEvent()->getBlock();
        $crumbs = $this->_addPagesHelper->getCrumbs($block->getNameInLayout());

        if (!$crumbs) {
            return $this;
        }

        /** @var Breadcrumbs $breadcrumbsBlock */
        $breadcrumbsBlock = $block->getLayout()->getBlock('breadcrumbs');
        if (!$breadcrumbsBlock) {
            return $this;
        }

        //Add homepage breadcrumb
        $breadcrumbsBlock->addCrumb('home', [
            'label' => __('Home'),
            'title' => __('Go to Home Page'),
            'link'  => $this->_store->getBaseUrl()
        ]);

        //Add custom breadcrumbs
        foreach ($crumbs as $crumb) {
            $breadcrumbsBlock->addCrumb($crumb['code'], [
                'label' => isset($crumb['title']) ? $crumb['title'] : '',
                'title' => isset($crumb['title']) ? $crumb['title'] : '',
                'link'  => isset($crumb['url']) ? $crumb['url'] : false,
            ]);
        }

        return $this;
    }
}