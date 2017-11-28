<?php
namespace Magebees\Finder\Block\Adminhtml\Finder;
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
	protected $_finderFactory;
	
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Backend\Helper\Data $backendHelper,
			\Magebees\Finder\Model\FinderFactory $finderFactory,
			array $data = array()
	) {
		$this->_finderFactory = $finderFactory;
		parent::__construct($context, $backendHelper, $data);
	}
	
	protected function _construct()	{
		parent::_construct();
		$this->setId('finderGrid');
		$this->setDefaultSort('finder_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}
	
	protected function _prepareCollection()	{
		$collection = $this->_finderFactory->create()->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
		
	protected function _prepareMassaction()	{
		$this->setMassactionIdField('finder_id');
		$this->getMassactionBlock()->setFormFieldName('finder');
		
		$this->getMassactionBlock()->addItem(
				'display',
				array(
						'label' => __('Delete'),
						'url' => $this->getUrl('finder/*/massdelete'),
						'confirm' => __('Are you sure?'),
						'selected'=>true
				)
		);
		
		$status = [
            ['value' => 1, 'label'=>__('Enabled')],
            ['value' => 0, 'label'=>__('Disabled')],
        ];

        array_unshift($status, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem(
           'status',
            array(
                'label' => __('Change status'),
                'url' => $this->getUrl('finder/*/massStatus', array('_current' => true)),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $status
                    )
                )
            )
        );
		return $this;
	}
		
	protected function _prepareColumns() {
		$this->addColumn(
			'finder_id',
			[
				'header' => __('Finder ID'),
				'type' => 'number',
				'index' => 'finder_id',
				'header_css_class' => 'col-id',
				'column_css_class' => 'col-id',
			]
		);
		$this->addColumn(
			'title',
			[
				'header' => __('Title'),
				'index' => 'title',
			]
		);
		$this->addColumn(
			'number_of_dropdowns',
			[
				'header' => __('Number of Dropdowns'),
				'index' => 'number_of_dropdowns',
			]
		);
		
		
		$this->addColumn(
			'status',
			[
				'header' => __('Status'),
				'index' => 'status',
				'frame_callback' => array($this, 'decorateStatus'),
				'type' => 'options',
				'options' => [ '0' => 'Disabled', '1' => 'Enabled'],
			]
		);
		
		$this->addColumn(
            'edit',
            array(
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => __('Edit'),
                        'url' => array(
                            'base' => '*/*/edit',
                            'params' => array('store' => $this->getRequest()->getParam('store'))
                        ),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            )
        );
		
		//$this->addExportType('*/*/exportCsv', __('CSV'));
		//$this->addExportType('*/*/exportXml', __('XML'));
		//$this->addExportType('*/*/exportExcel', __('Excel'));

		return parent::_prepareColumns();
	}
	
	/**
	 * @return string
	 */
	public function getGridUrl() {
		return $this->getUrl('*/*/grid', ['_current' => true]);
	}
	
	public function getRowUrl($row) {
		return $this->getUrl(
			'*/*/edit',
			['id' => $row->getId()]
		);
	}

	public function decorateStatus($value, $row, $column, $isExport) {
		if ($value=="Enabled") {
			$cell = '<span class="grid-severity-notice"><span>Enabled</span></span>';
		} else {
			$cell = '<span class="grid-severity-minor"><span>Disabled</span></span>';
		}
		return $cell;
    }
}