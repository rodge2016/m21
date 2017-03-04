<?php
namespace Smartwave\com\Controller\Index;

class syear extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {

        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);

    }

    public function execute()
    {
        $year = $this->getRequest()->getPostValue('year');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $result1 = $connection->fetchAll("SELECT DISTINCT make  FROM compatibility where YEAR = '{$year}'ORDER BY make");
        $jsonData = json_encode($result1);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }


}