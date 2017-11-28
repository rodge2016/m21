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

/**
 * Class BrandList
 * @package Mageplaza\Shopbybrand\Block\Brand
 */
class BrandList extends \Mageplaza\Shopbybrand\Block\Brand
{
	/**
	 * @type string
	 */
	protected $_char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	/**
	 * Get alphabet list array
	 *
	 * @return array
	 */
	public function getAlphabet()
	{
		$alphaBet    = [];
		$activeChars = [];

		foreach ($this->getCollection() as $brand) {
			$name = $brand->getValue();
			if (is_string($name) && strlen($name) > 0) {
				$firstChar = is_numeric($name[0]) ? '0-9' : strtoupper($name[0]);
				if (!in_array($firstChar, $activeChars)) {
					$activeChars[] = $firstChar;
				}
			}
		}

		for ($i = 0; $i < strlen($this->_char); $i++) {
			$alphaBet[] = [
				'char'   => $this->_char[$i],
				'active' => in_array($this->_char[$i], $activeChars)
			];
		}
		$alphaBet[] = [
			'char'   => 'num',
			'label'  => '0-9',
			'active' => in_array('0-9', $activeChars)
		];

		return $alphaBet;
	}

	/**
	 * Get class for mixitup filter
	 *
	 * @param $brand
	 * @return string
	 */
	public function getFilterClass($brand)
	{
		$firstChar = $brand->getValue()[0];

		return is_numeric($firstChar) ? 'num' : strtoupper($firstChar);
	}

	/**
	 * Is show description below Brand name
	 *
	 * @return mixed
	 */
	public function showDescription()
	{
		return $this->helper->getBrandConfig('show_description');
	}

	/**
	 * Is show Label
	 *
	 * @return bool
	 */
	public function showLabel()
	{
		return $this->helper->getBrandConfig('display') == \Mageplaza\Shopbybrand\Model\Config\Source\FeatureDisplay::DISPLAY_LOGO_AND_LABEL;
	}
}
