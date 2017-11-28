<?php
namespace Magebees\Finder\Controller\Adminhtml\Product;

class Delete extends \Magento\Backend\App\Action
{
	protected $_productsFactory;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magebees\Finder\Model\ProductsFactory $_productsFactory
	) {
		parent::__construct($context);
		$this->_productsFactory = $_productsFactory;
	}

    public function execute() {
        $product_finder_Id = $this->getRequest()->getParam('id');
        try {
		    $product = $this->_productsFactory->create()->load($product_finder_Id);
			$finder_id = $product->getFinderId();
			$product->delete();
			$this->messageManager->addSuccess(
                __('Product Deleted successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
		$this->_redirect('*/finder/edit', array('id' => $finder_id));
    }
	
	protected function _isAllowed(){
        return $this->_authorization->isAllowed('Magebees_Finder::finder_content');
    }
}
