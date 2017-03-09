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


  }
    public function get_url()
    {

        return  $this->_storeManager->getStore()->getBaseUrl();
    }

}