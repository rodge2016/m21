<?php
namespace Magebees\Finder\Controller\Adminhtml\Product;
use Magento\Backend\App\Action;
class Edit extends \Magento\Backend\App\Action
{
	protected $_coreRegistry = null;
	protected $resultPageFactory;
	protected $_productsFactory;
 
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
		\Magebees\Finder\Model\ProductsFactory $productsFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_productsFactory = $productsFactory;
        parent::__construct($context);
    }
 
   
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magebees_Menu::title');
        return $resultPage;
    }
		
	public function execute()   {
		// 1. Get ID and create model
		       
        $id = $this->getRequest()->getParam('id');
        $model  = $this->_productsFactory->create()->load($id);		  
				
        // 2. Initial checking
        if ($id) 
        {
			$model->load($id);
			
	        if (!$model->getId()) {
                $this->messageManager->addError(__('Product does not exist.'));
          
                $resultRedirect = $this->resultRedirectFactory->create();
 
                return $resultRedirect->setPath('*/*/');
            }
        }
        // 3. Set entered data if was error when we do save
		$data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
   
        $this->_coreRegistry->register('product_data', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Product') : __('Add Product'),
            $id ? __('Edit Product') : __('Add Product')
        );
		$resultPage->getConfig()->getTitle()->prepend(__('Magebees'));
		$resultPage->getConfig()->getTitle()->prepend(__('YMM Products Parts Finder'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Finders'));
		$resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getSku() : __('Add Product'));
 
        return $resultPage;
    }
	
	protected function _isAllowed(){
       return $this->_authorization->isAllowed('Magebees_Finder::finder_content');
	}
}