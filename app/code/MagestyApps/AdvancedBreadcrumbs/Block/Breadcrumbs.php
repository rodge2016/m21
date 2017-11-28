<?php
/**
 * Copyright © 2016 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Block;

use \Magento\Framework\View\Element\Template\Context;
use \MagestyApps\AdvancedBreadcrumbs\Helper\Data;

class Breadcrumbs extends \Magento\Theme\Block\Html\Breadcrumbs
{
    /**
     * Template filename
     *
     * @var string
     */
    protected $_template = 'breadcrumbs.phtml';

    /**
     * Breadcrumbs
     *
     * @var null|array
     */
    protected $_breadcrumbs = null;

    /** @var \MagestyApps\AdvancedBreadcrumbs\Model\Breadcrumbs $crumbsModel */
    protected $_crumbsModel;

    /** @var Data $helper */
    protected $_helper;

    public function __construct(
        Context $context,
        array $data = [],
        Data $helper,
        \MagestyApps\AdvancedBreadcrumbs\Model\Breadcrumbs $crumbsModel
    ) {
        parent::__construct($context, $data);

        $this->_helper = $helper;
        $this->_crumbsModel = $crumbsModel;
    }

    /**
     * Get breadcrumbs for current product or category
     *
     * @return array
     */
    protected function getBreadcrumbs()
    {
        if ($this->_breadcrumbs === null) {

            $pageType = $this->_helper->getPageType();
            $crumbs = [];
            $lastCrumbTitle = '';

            if ($pageType == Data::PAGE_TYPE_DIRECT_PRODUCT
                || ($pageType == Data::PAGE_TYPE_CATEGORY_PRODUCT
                    && !$this->_helper->showOnlyOnePath())
                || ($pageType == Data::PAGE_TYPE_CATEGORY_PRODUCT
                    && $this->_helper->isForceFullPath())
            ) {

                $product = $this->_helper->getProduct();
                $crumbs = $this->_crumbsModel->getProductBreadcrumbs($product);
                $lastCrumbTitle = $product->getName();

            } elseif ($pageType == Data::PAGE_TYPE_CATEGORY_PRODUCT) {

                $crumbs = $this->_crumbsModel->getDirectBreadcrumbs();
                $lastCrumbTitle = $this->_helper->getProduct()->getName();

            } elseif ($pageType == Data::PAGE_TYPE_CATEGORY) {

                $crumbs = $this->_crumbsModel->getDirectBreadcrumbs(true);
                $lastCrumbTitle = $this->_helper->getCategory()->getName();

            } elseif ($pageType == Data::PAGE_TYPE_SEARCH) {

                $crumbs = $this->_crumbsModel->getSearchCrumbs();
                $lastCrumbTitle = $this->_helper->getProduct()->getName();

            }

            if ($this->_helper->showOnlyOnePath()
                || $pageType == Data::PAGE_TYPE_CATEGORY
            ) {
                $crumbs = $this->getLongestPath($crumbs);
            } elseif ($this->_helper->hideDuplicates()) {
                $crumbs = $this->hideDubCategories($crumbs);
            }

            if (count($crumbs) == 1) {
                $crumbs = $this->addLastCrumb($crumbs, $lastCrumbTitle);
            }

            $this->_breadcrumbs = $crumbs;
        }

        return $this->_breadcrumbs;
    }

    /**
     * Add last non-clickable crumb
     *
     * @param array $crumbs
     * @param $lastCrumbsTitle
     * @return array
     */
    public function addLastCrumb(array $crumbs, $lastCrumbsTitle)
    {
        $crumbs[0][] = [
            'title' => $lastCrumbsTitle,
            'last' => true
        ];

        return $crumbs;
    }

    /**
     * Get only one breadcrumbs path of all available paths
     *
     * @param array $crumbs
     * @return array
     */
    public function getLongestPath(array $crumbs)
    {
        if (count($crumbs) == 1) {
            return $crumbs;
        }

        $longestPath = '';
        foreach ($crumbs as $k => $path) {
            if (count($path) > count($longestPath)) {
                $longestPath = $crumbs[$k];
            }
        }

        return [$longestPath];
    }

    /**
     * Mark duplicated categories as hidden
     *
     * @param array $crumbs
     * @return array
     */
    public function hideDubCategories(array $crumbs)
    {
        $existCat = [];
        foreach ($crumbs as $pathKey => $path) {
            foreach ($path as $crumbKey => $crumb) {
                if (in_array($crumb['category_id'], $existCat)) {
                    $crumbs[$pathKey][$crumbKey]['hidden'] = true;
                } else {
                    $existCat[] = $crumb['category_id'];
                }
            }
        }
        return $crumbs;
    }

    /**
     * Get all filtered and formatted breadcrumbs
     *
     * @return array
     */
    public function getAllBreadcrumbs()
    {
        $crumbs = $this->getBreadcrumbs();
        return $crumbs;
    }

    /**
     * Check whether the structured data markup should be added
     *
     * @return bool
     */
    public function isStructuredDataEnabled()
    {
        return $this->_helper->isStructuredDataEnabled();
    }
}