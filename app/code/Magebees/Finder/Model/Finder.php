<?php
namespace Magebees\Finder\Model;

class Finder extends \Magento\Framework\Model\AbstractModel {
	/**
     * Initialization
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Magebees\Finder\Model\ResourceModel\Finder');
    }
	
}
