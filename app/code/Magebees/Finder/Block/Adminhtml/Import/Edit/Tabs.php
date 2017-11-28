<?php
namespace Magebees\Finder\Block\Adminhtml\Import\Edit;
class Tabs extends \Magento\Backend\Block\Widget\Tabs{
    protected function _construct(){
        parent::_construct();
        $this->setId('import_tabs');
        $this->setDestElementId('edit_form');
		$this->setTitle(__('Import Information'));
    }
	
	protected function _prepareLayout(){
		
		$this->addTab(
            'import_section',
            [
                'label' => __('Import Information'),
                'title' => __('Import Information'),
                'content' => $this->getLayout()->createBlock(
                    'Magebees\Finder\Block\Adminhtml\Import\Edit\Tab\Import'
                )->toHtml(),
                'active' => true
            ]
        );
		
		return parent::_prepareLayout();
	}
}