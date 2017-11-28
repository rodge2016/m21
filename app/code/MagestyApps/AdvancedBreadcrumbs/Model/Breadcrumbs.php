<?php
/**
 * Copyright © 2016 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model;

use \Magento\Framework\Model\AbstractModel;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Store\Model\Store;
use \Magento\Catalog\Api\CategoryRepositoryInterface;
use \Magento\Catalog\Helper\Category;
use \Magento\Framework\Model\ResourceModel\AbstractResource;
use \Magento\Framework\Data\Collection\AbstractDb;
use \Magento\Catalog\Model\Product\Interceptor;
use \MagestyApps\AdvancedBreadcrumbs\Helper\Data;

class Breadcrumbs extends AbstractModel
{
    /** @var array */
    protected $_catModels =[];

    /** @var \MagestyApps\AdvancedBreadcrumbs\Helper\Data */
    protected $_helper;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;

    /** @var \Magento\Catalog\Api\CategoryRepositoryInterface */
    protected $_categoryRepository;

    /** @var \Magento\Catalog\Helper\Category */
    protected $_categoryHelper;

    /** @var \Magento\Store\Model\Store $store */
    protected $_store;

    public function __construct(
        Context $context,
        Registry $registry,
        Data $helper,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository,
        Category $categoryHelper,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        $this->_categoryRepository = $categoryRepository;
        $this->_categoryHelper = $categoryHelper;
        $this->_store = $storeManager->getStore();

        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Get product breadcrumbs
     *
     * @param Interceptor $product
     * @return array
     */
    public function getProductBreadcrumbs(Interceptor $product)
    {
        $allPaths = [];
        $priorPaths = [];
        $defaultCategory = $this->_helper->getDefaultCategory();

        foreach ($product->getCategoryIds() as $catId) {
            $category = $this->getCategoryModel($catId);

            if (!$this->_categoryHelper->canShow($category)) {
                continue;
            }

            if ($this->_helper->showOnlyOnePath()
                && $product->getData('default_breadcrumbs')
                && $catId != $product->getData('default_breadcrumbs')
            ) {
                continue;
            }

            $key = $category->getParentId() . '_' . $category->getPosition();

            if ($defaultCategory
                && in_array($defaultCategory, $category->getPathIds())
            ) {
                $priorPaths[$key] = $category->getPathInStore();
            } else {
                $allPaths[$key] = $category->getPathInStore();
            }
        }

        if (count($priorPaths)) {
            $allPaths = $priorPaths;
        }

        ksort($allPaths, SORT_NATURAL);

        $allPaths = $this->_removeDuplicates($allPaths);

        return $this->preparePaths($allPaths);
    }

    /**
     * Get breadcrumbs if user came from a search result page
     *
     * @return array
     */
    public function getSearchCrumbs()
    {
        $searchQuery = $this->_helper->getSearchQuery();
        $searchUrl = $this->_helper->getSearchUrl();

        $crumbs = [];
        $crumbs[] = [
            [
                'category_id' => 0,
                'title' => __('Home'),
                'link' => $this->_store->getBaseUrl(),
                'last' => false
            ],
            [
                'category_id' => -1,
                'title' =>  __("Search results for: '%1'", $searchQuery),
                'link' => $searchUrl,
                'last' => false
            ],

        ];

        return $crumbs;
    }

    /**
     * Get breadcrumbs for a category or product opened through category
     *
     * @param bool $isCategory
     * @return array
     */
    public function getDirectBreadcrumbs($isCategory = false)
    {
        $allPaths = [];
        $category = $this->_helper->getCategory();
        if (!$category || !$category->getId()) {
            return $allPaths;
        }

        $path = $category->getPathInStore();
        if ($isCategory) {
            $path = explode(',', $path);
            unset ($path[0]);
            $path = implode(',', $path);
        }

        $allPaths[] = $path;

        return $this->preparePaths($allPaths);
    }

    /**
     * Format breadcrumb paths
     *
     * @param array $pathArr
     * @return array
     */
    public function preparePaths(array $pathArr)
    {
        $result = [];

        foreach ($pathArr as $path) {
            $catIdsArr = explode(',', $path);
            krsort($catIdsArr);

            $newPath = [];

            $newPath[] = [
                'category_id' => 0,
                'title' => __('Home'),
                'link' => $this->_store->getBaseUrl(),
                'last' => false
            ];

            foreach ($catIdsArr as $catId) {
                $category = $this->getCategoryModel($catId);

                if (!$category || !$this->_categoryHelper->canShow($category)) {
                    continue;
                }

                $newPath[] = [
                    'category_id' => $category->getId(),
                    'title' => $category->getName(),
                    'link' => $category->getUrl(),
                    'last' => false
                ];
            }
            $result[] = $newPath;
        }

        if (!count($result)) {
            $result[] = [[
                'category_id' => 0,
                'title' => __('Home'),
                'link' => $this->_store->getBaseUrl(),
                'last' => false
            ]];
        }

        return $result;
    }

    /**
     * Remove duplicated paths
     *
     * @param array $pathArr
     * @return mixed
     */
    protected function _removeDuplicates(array $pathArr)
    {
        foreach ($pathArr as $k => $path) {
            foreach ($pathArr as $pathCompare) {
                if ((bool) strpos($pathCompare, $path) !== false) {
                    unset ($pathArr[$k]);
                }
            }
        }
        return $pathArr;
    }

    /**
     * Get category model
     *
     * @param $categoryId
     * @param null $storeId
     * @return mixed
     */
    public function getCategoryModel($categoryId, $storeId = null)
    {
        if (!$categoryId) {
            return false;
        }

        if ($storeId === null) {
            $storeId = $this->_storeManager->getStore()->getId();
        }

        if (!isset($this->_catModels[$categoryId.'_'.$storeId])) {
            $category = $this->_categoryRepository->get($categoryId, $storeId);
            $this->_catModels[$categoryId] = $category;
        }

        return $this->_catModels[$categoryId];
    }
}