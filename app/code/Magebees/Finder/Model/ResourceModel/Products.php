<?php
namespace Magebees\Finder\Model\ResourceModel;
/**
 * Products resource model
 */
class Products extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	/**
     * Define main table. Define other tables name
     *
     * @return void
     */
	protected function _construct(){
        $this->_init('magebees_finder_products', 'finder_product_id');
	}
	
}
