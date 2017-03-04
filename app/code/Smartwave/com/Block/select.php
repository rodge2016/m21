<?php

namespace Smartwave\com\Block;

use  Magento\Framework\View\Element\Template;



class select extends Template{

    public $_storeManager;

    public function __construct(

        \Magento\Framework\View\Element\Template\Context  $context,
        \Magento\Store\Model\StoreManagerInterface  $storeManager
    )
    {
        $this->_storeManager=$storeManager;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('Hello');
    }

    public function get_url()
    {

        return  $this->_storeManager->getStore()->getBaseUrl();
    }

}
