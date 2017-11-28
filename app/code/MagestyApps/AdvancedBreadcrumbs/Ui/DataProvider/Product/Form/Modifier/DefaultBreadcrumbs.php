<?php
/**
 * Copyright © 2016 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use MagestyApps\AdvancedBreadcrumbs\Model\Breadcrumbs;

class DefaultBreadcrumbs extends AbstractModifier
{
    public $data;

    public $meta;

    /**
     * @var ArrayManager
     */
    public $arrayManager;

    /**
     * @var LocatorInterface
     */
    public $locator;

    /**
     * @var Breadcrumbs
     */
    public $crumbsModel;

    /**
     * DefaultBreadcrumbs constructor.
     * @param ArrayManager $arrayManager
     * @param LocatorInterface $locator
     * @param Breadcrumbs $crumbsModel
     */
    public function __construct(
        ArrayManager $arrayManager,
        LocatorInterface $locator,
        Breadcrumbs $crumbsModel
    ) {
        $this->arrayManager = $arrayManager;
        $this->locator = $locator;
        $this->crumbsModel = $crumbsModel;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $this->data = $data;
        return $this->data;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $attrPath = $this->arrayManager->findPath(
            'default_breadcrumbs',
            $this->meta,
            null,
            'children'
        );

        $optionsPath = $attrPath.'/arguments/data/config/options';

        $this->meta = $this->arrayManager->set(
            $optionsPath,
            $this->meta,
            $this->getBreadcrumbsAvailable()
        );

        return $this->meta;
    }

    /**
     * @return array
     */
    public function getBreadcrumbsAvailable()
    {
        $availablePaths = [
            ['value' => '', 'label' => __('Detect Automatically')]
        ];

        $categoryIds = $this->locator->getProduct()->getCategoryIds();
        foreach ($categoryIds as $categoryId) {

            $pathStr = ['Home'];

            $category = $this->crumbsModel->getCategoryModel($categoryId);
            $path = explode(',', $category->getPathInStore());
            krsort($path);

            foreach ($path as $pathCatId) {
                if (!$pathCatId) {
                    continue;
                }

                $pathCat = $this->crumbsModel->getCategoryModel($pathCatId);
                if ($pathCat->getLevel() < 2) {
                    continue;
                }

                $pathStr[] = $pathCat->getName();
            }

            $availablePaths[] = [
                'value' => $categoryId,
                'label' => implode(' / ', $pathStr)
            ];
        }

        return $availablePaths;
    }
}