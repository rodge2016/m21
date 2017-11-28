<?php
namespace Magebees\Finder\Block\Adminhtml\ImportUniversal\Edit\Tab;
class Import extends \Magento\Backend\Block\Widget\Form\Generic
{
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = array()
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
	}
	
 
    protected function _prepareForm(){
		$id = $this->getRequest()->getParam('fid');
        //$model = $this->_coreRegistry->registry('finder_data');
		$form = $this->_formFactory->create();
		
		$msg_fieldset = $form->addFieldset('finder_form_msg', array('legend'=>__('**Important Notes.')));
			 
		$msg_fieldset->addField(
			'note',
			'label',
			[
				'label'     => 'Note : ',
				'after_element_html' => '<h4 style="color:#df280a">1. Please make sure that your CSV file should contains all field values along with SKUs<br/>2. If you select yes in "Delete Existing Data" dropdown then your all existing data are deleted. So please select yes if you want to delete all records... </h4>',
			]
		);
		
		$fieldset = $form->addFieldset('import_form', array('legend'=>__('Import CSV file only')));

		$fieldset->addField(
			'existing_data',
			'select', 
			[
			  'label'     => __('Do You Want To Delete Existing Data?'),
			  'name'      => 'existing_data',
			  'values'    => [
						[
							'value'     => 0,
							'label'     => __('No'),
						],

						[
							'value'     => 1,
							'label'     => __('Yes'),
						],
					],
			]
		);
						
		$fieldset->addField(
			'filename',
			'file', 
			[
				'label'     => __('Upload CSV'),
				'name'      => 'filename',
				'required'	=> true,
			]
		);

		$fieldset->addField(
			'finder_id',
			'hidden',
			[
				'name'      => 'finder_id',
				'value' 	=> $id 
			]
		);
		
		$this->setForm($form);
		
		return parent::_prepareForm();   
    }

	/**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
