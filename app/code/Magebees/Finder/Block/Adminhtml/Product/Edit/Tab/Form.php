<?php
namespace Magebees\Finder\Block\Adminhtml\Product\Edit\Tab;
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
	protected $_productsFactory;
	protected $_dropdownsFactory;
	
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
		\Magebees\Finder\Model\ProductsFactory $productsFactory,
		\Magebees\Finder\Model\DropdownsFactory $dropdownsFactory,
		array $data = array()
    ) {
        $this->_productsFactory = $productsFactory;
        $this->_dropdownsFactory = $dropdownsFactory;
		parent::__construct($context, $registry, $formFactory, $data);
	}
	
 
    protected function _prepareForm(){
		$id     = $this->getRequest()->getParam('id');
		$model  = $this->_productsFactory->create()->load($id);		  
        $form = $this->_formFactory->create();
		$fieldset = $form->addFieldset('product_fieldset', array('legend' => __('Product Information')));
		
		if ($model->getId()) {
            $fieldset->addField('finder_product_id', 'hidden', array('name' => 'finder_product_id'));
        }
		
		$fieldset->addField(
            'sku',
            'text',
            [
                'name' => 'sku',
                'label' => __('SKU'),
                'title' => __('SKU'),
                'required' => true,
				'value'    => $model->getSku(),
            ]
        );
		
		$fid = $this->getRequest()->getParam('fid');
		if($fid){
			$setFid = $fid;
			$model->setFinderId($setFid);
		}else{
			$setFid = $model->getFinderId();
		}
		
		$dropdownsCollection = $this->_dropdownsFactory->create()->getCollection();
		$dropdownsCollection->addFieldToFilter('finder_id',$setFid);
						
		if($dropdownsCollection->count()){
			$i = 1;
			foreach($dropdownsCollection as $dropdowns)
			{
				$value = 'getField'.$i;
				$fieldset->addField(
					'field'.$i,
					'text',
					[
						'label'     => $dropdowns->getName(),
						'class'     => 'required-entry',
						'required'  => true,
						'name'      => 'field'.$i,
						'value'      => $model->getData('field'.$i),
					]
				);
			    $i++;
			}
		} 
		
		$fieldset->addField('finder_id', 'hidden', array(
			'name' => 'finder_id',
			'value'      => $setFid,
		));
		
		//$model_data = $model->getData();
		$form->setValues($model);
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
