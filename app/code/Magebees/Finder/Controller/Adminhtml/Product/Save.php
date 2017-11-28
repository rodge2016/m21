<?php
namespace Magebees\Finder\Controller\Adminhtml\Product;
class Save extends \Magento\Backend\App\Action{
	
	public function execute(){
		$data = $this->getRequest()->getPost()->toArray();
		if ($data) {
			$finder_id = $data['finder_id'];
			$model = $this->_objectManager->create('Magebees\Finder\Model\Products');
          	$id = $this->getRequest()->getParam('id');
	        if ($id) {
                $model->load($id);
			}
		
			try {
				$model->setData($data);
                $model->save();
											
				$this->messageManager->addSuccess(__('Product details was successfully saved'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/finder/edit', array('id' => $model->getFinderId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/finder/edit', array('id' => $model->getFinderId(), '_current' => true));
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the label.'));
                //$this->messageManager->addError($e->getMessage());
            }

            $this->_getSession()->setFormData($data);
			$this->_redirect('*/finder/edit', array('id' => $finder_id, '_current' => true));
			return;
        }
        $this->_redirect('*/*/');
    }
	
	protected function _isAllowed(){
        return $this->_authorization->isAllowed('Magebees_Finder::finder_content');
    }
}
