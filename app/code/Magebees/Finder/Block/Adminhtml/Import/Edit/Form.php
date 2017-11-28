<?php
namespace Magebees\Finder\Block\Adminhtml\Import\Edit;
class Form extends \Magento\Backend\Block\Widget\Form\Generic{
	
	protected function _prepareForm() {
		$form = $this->_formFactory->create(
            array(
                'data' => array(
                    'id' => 'edit_form',
                   	'action' => $this->getUrl('*/*/import'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
					'onsubmit' => 'importData()',
				)
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}