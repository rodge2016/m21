<?php
namespace Magebees\Finder\Block\Adminhtml\Product\Edit;
class Tabs extends \Magento\Backend\Block\Widget\Tabs{
    protected function _construct(){
        parent::_construct();
        $this->setId('product_tabs');
        $this->setDestElementId('edit_form');
		$this->setTitle(__('Product Information'));
    }
	
	protected function _prepareLayout(){
		
		$this->addTab(
            'general_section',
            [
                'label' => __('Product Information'),
                'title' => __('Product Information'),
                'content' => $this->getLayout()->createBlock(
                    'Magebees\Finder\Block\Adminhtml\Product\Edit\Tab\Form'
                )->toHtml(),
                'active' => true
            ]
        );
		
		return parent::_prepareLayout();
	}
}