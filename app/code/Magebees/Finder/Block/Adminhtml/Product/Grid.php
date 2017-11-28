<?php
namespace Magebees\Finder\Block\Adminhtml\Product;
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
	protected $_productsFactory;
	protected $_dropdownsFactory;
	
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Backend\Helper\Data $backendHelper,
			\Magebees\Finder\Model\ProductsFactory $productsFactory,
			\Magebees\Finder\Model\DropdownsFactory $dropdownsFactory,
			array $data = array()
	) {
		$this->_productsFactory = $productsFactory;
		$this->_dropdownsFactory = $dropdownsFactory;
		parent::__construct($context, $backendHelper, $data);
	}
	
	protected function _construct()	{
		parent::_construct();
		$this->setId('productGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}
	
	protected function _prepareCollection()	{
		$collection = $this->_productsFactory->create()->getCollection()->addFieldToFilter('finder_id',$this->getRequest()->getParam('id'));
			
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
		
	protected function _prepareMassaction()	{
		$this->setMassactionIdField('finder_product_id');
		$this->getMassactionBlock()->setFormFieldName('product');
		
		$this->getMassactionBlock()->addItem(
				'delete',
				array(
						'label' => __('Delete'),
						'url' => $this->getUrl('finder/product/massdelete'),
						'confirm' => __('Are you sure?'),
						'selected'=>true
				)
		);
		
		return $this;
	}	
		
		
	protected function _prepareColumns() {
		/* $this->addColumn(
			'finder_product_id',
			[
				'header' => __('ID'),
				'type' => 'number',
				'index' => 'finder_product_id',
				'header_css_class' => 'col-id',
				'column_css_class' => 'col-id',
			]
		); */
		$this->addColumn(
			'sku',
			[
				'header' => __('SKU'),
				'index' => 'sku',
			]
		);
			
		$collection_dropdowns = $this->_dropdownsFactory->create()->getCollection()->addFieldToFilter('finder_id',$this->getRequest()->getParam('id'));
		$k=1;
		foreach($collection_dropdowns as $drpdwn){
			$this->addColumn(
				'field'.$k,
				[
					'header'    => $drpdwn->getName(),
					'index'     => 'field'.$k,
				]
			);
			$k++;
		}
		
		$this->addExportType('*/product/exportCsv', __('CSV'));
		//$this->addExportType('*/product/exportXml', __('XML'));
		//$this->addExportType('*/*/exportExcel', __('Excel'));

		return parent::_prepareColumns();
	}
	
	/**
	 * @return string
	 */
	public function getGridUrl() {
		return $this->getUrl('*/product/grid', ['_current' => true]);
	}
	
	public function getRowUrl($row) {
		return $this->getUrl(
			'*/product/edit',
			['id' => $row->getId()]
		);
	}
}