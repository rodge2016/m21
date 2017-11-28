<?php
namespace Magebees\Finder\Block\Adminhtml\Import;
class Edit extends \Magento\Backend\Block\Widget\Form\Container {
    protected function _construct()	{
		$this->_objectId = 'id';
        $this->_blockGroup = 'Magebees_Finder';
        $this->_controller = 'adminhtml_import';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Import Products'));
		$this->buttonList->remove('back');
		
		$this->addButton('import_back', array(
			'label' => __('Back'),
			'class' =>'back',
			'onclick' => "setLocation('{$this->getUrl('*/finder/edit', array('id' => $this->getRequest()->getParam('fid')))}')",
		));
				
		$this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('block_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'finder_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'finder_content');
                }
            }
					
			function importData(){
				alert('import');
				require([
					'jquery'
				],
				function($) {
					var form = $('#filename').val();
					if(form){
						$.ajax({
								url : '". $this->getUrl('*/*/import'). "',
								type: 'post',
								showLoader:true,
						});
					}
       			});
			}
			
		";

        
    }
}