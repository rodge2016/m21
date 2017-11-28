<?php
/**
 * Copyright © 2016 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Observer;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer;
use \Magento\Framework\View\Context;
use \Magento\Catalog\Block\Adminhtml\Form\Renderer\Fieldset\Element;
use \MagestyApps\AdvancedBreadcrumbs\Model\Breadcrumbs;

class RenderAttrDefaultBreadcrumbs implements ObserverInterface
{
    /** @var Context  */
    protected $_context;

    /** @var Breadcrumbs  */
    protected $_crumbsModel;

    public function __construct(
        Context $context,
        Breadcrumbs $crumbsModel
    ) {
        $this->_context = $context;
        $this->_crumbsModel = $crumbsModel;
    }

    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();

        if ($this->_context->getFullActionName() != 'catalog_product_edit'
            || !($block instanceof Element)
            || !$block->getAttribute()
            || $block->getAttributeCode() != 'default_breadcrumbs'
        ) {
            return $this;
        }

        $availablePaths = [
            '' => 'Detect Automatically'
        ];

        $categoryIds = $block->getDataObject()->getCategoryIds();
        foreach ($categoryIds as $categoryId) {

            $pathStr = ['Home'];

            $category = $this->_crumbsModel->getCategoryModel($categoryId);
            $path = explode(',', $category->getPathInStore());
            krsort($path);

            foreach ($path as $pathCatId) {
                if (!$pathCatId) {
                    continue;
                }

                $pathCat = $this->_crumbsModel->getCategoryModel($pathCatId);
                if ($pathCat->getLevel() < 2) {
                    continue;
                }

                $pathStr[] = $pathCat->getName();
            }

            $availablePaths[$categoryId] = implode(' / ', $pathStr);

        }

        $block->getElement()->setData('values', $availablePaths);

        return $this;
    }
}