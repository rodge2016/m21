<?php

namespace Smartwave\com\Block;
use  Magento\Framework\View\Element\Template;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\RequestInterface;

class Custom extends Template{
    protected $request;

    protected $_productCollectionFactory;
    public function __construct(
        RequestInterface $request,

        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    )

    {
        $this->request = $request;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);

    }



    public function sayHello()
    {
        return __('sayHello');
        echo " sayHello() ";

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $om->get('Magento\Framework\App\RequestInterface');
        
        echo  $this->getRequest()->getParam('year');
        echo  $this->getRequest()->getParam('make');
        echo  $this->getRequest()->getParam('model');

        echo   $postData = $this->request->getPost('year');

    }
}
