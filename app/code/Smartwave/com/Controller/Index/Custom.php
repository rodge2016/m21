<?php
namespace Smartwave\com\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;

 class  Custom  extends \Magento\Framework\App\Action\Action {

     protected $_resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(

        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {

        echo "__CLASS___";
        echo "6666666666";
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);

    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        echo  $this->getRequest()->getParam('year');
        echo  $this->getRequest()->getParam('make');
        echo  $this->getRequest()->getParam('model');
        
        $year  =$this->getRequest()->getParam('year');
        $make  =$this->getRequest()->getParam('make');
        $model =$this->getRequest()->getParam('model');

//        $this->registry->register('year', $year);
//        $this->registry->register('make', $make);

//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection    ('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
//        $product_id= $connection->fetchAll(  ("SELECT DISTINCT product_id  FROM relation_id  WHERE comp_id IN (  SELECT  comp_id  FROM compatibility where year ='{$year}' AND make = '{$make}'  AND  model = '{$model}')"  )
//        );
//        var_dump($product_id);

//        $resultPage = $this->_resultPageFactory->create();
//        $resultPage->addHandle('module_custom_customlayout2');
//        return $this->_resultPageFactory->create();

         $this->_view->loadLayout();
        $this->_view->renderLayout();

    }
     /**
      * Setting custom variable in registry to be used
      *
      */

     public function setCustomVariable()
     {

         $this->registry->register('custom_var', 'Added Value');
     }

     /**
      * Retrieving custom variable from registry
      * @return string
      */
     public function getCustomVariable()
     {
         return $this->registry->registry('custom_var');
     }
 }

