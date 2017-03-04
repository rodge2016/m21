<?php
//namespace  Smartwave\com\Controller\Index;
namespace  Smartwave\com\Block;
class  Display extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {

        parent::__construct($context);
        echo  "<h1>display blockkkkkkkk";
    }

    public function sayHello()
    {
        return __('<h1>Hello World');
    }
}