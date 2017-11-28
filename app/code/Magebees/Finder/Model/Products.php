<?php
namespace Magebees\Finder\Model;

class Products extends \Magento\Framework\Model\AbstractModel {
	/**
     * Initialization
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Magebees\Finder\Model\ResourceModel\Products');
    }
	
	public function validateFilter($filter)
    {
        $filter = explode('-', $filter);
        if (count($filter) != 2) {
            return false;
        }
        foreach ($filter as $v) {
            if ($v !== '' && $v !== '0' && (double)$v <= 0 || is_infinite((double)$v)) {
                return false;
            }
        }

        return $filter;
    }
}
