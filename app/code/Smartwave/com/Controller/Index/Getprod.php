<?php
namespace Smartwave\com\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Getprod extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    /**
     * [__construct]
     * @param Context                          $context
     * @param PageFactory                      $resultPageFactory
     */
    protected $_coreRegistry;

    public function __construct(

        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {

        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct(
            $context
        );
    }

/**
 * @return \Magento\Framework\View\Result\Page
 */
public function execute()
   {

       $this->_view->loadLayout();
       $this->_view->renderLayout();
//
//    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//    $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection    ('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
//    $product_id= $connection->fetchAll(("SELECT DISTINCT product_id  FROM relation_id  WHERE comp_id IN (  SELECT  comp_id  FROM compatibility where year ='{$year}' AND make = '{$make}'  AND  model = '{$model}')")
//    );

//    $resultPage = $this->_resultPageFactory->create();
//    $resultPage->addHandle('latest_product');
//      return $resultPage;

 // $resultPage->addHandle('module_custom_customlayout');
 //loads the layout of module_custom_customlayout.xml file with its name

  }
    public function get_url()
    {

        return  $this->_storeManager->getStore()->getBaseUrl();
    }

}