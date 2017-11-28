<?php
/**
 * Copyright © 2016 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Model\Source;

use \Magento\Catalog\Model\ResourceModel\Category\Collection;

class Category
{
    /** @var \Magento\Catalog\Model\Category  */
    protected $_categoryInstance;

    public function __construct(
        \Magento\Catalog\Model\Category $catInstance
    ) {
        $this->_categoryInstance = $catInstance;
    }

    /**
     * Get categories tree as an option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        /** @var Collection $categories */
        $categories = $this->_categoryInstance->getCollection();

        $categories->addAttributeToSelect('name')
            ->addOrderField('path');

        $options = [];

        $options[] = [
            'label' => __('--- no category ---'),
            'value' => ''
        ];

        foreach ($categories as $category) {
            /** @var \Magento\Catalog\Model\Category $category */
            if (!$category->getName()) {
                continue;
            }

            $padding = $this->_getLabelPadding($category->getLevel());
            $options[] = [
                'label' => $padding . ' ' . $category->getName(),
                'value' => $category->getId()
            ];
        }

        return $options;
    }

    /**
     * Get padding symbols for specific category $level
     *
     * @param integer $level
     * @return string
     */
    protected function _getLabelPadding($level)
    {
        $padding = '';
        for ($i = 0; $i <= ($level - 2); $i++) {
            $padding .= '...';
        }

        return $padding;
    }
}