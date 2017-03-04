<?php
namespace Smartwave\com\Controller\Index;

class smodel extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;

    /**
     * Constructor
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);

    }
        public function execute()
      {

           $make=$this->getRequest()->getPost('make');
           $year=$this->getRequest()->getPost('year');
           $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
           $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection    ('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
           $result1= $connection->fetchAll("SELECT DISTINCT model  FROM compatibility where make = '{$make}' AND year ='{$year}'ORDER BY model");
           $jsonData = json_encode($result1);
           $this->getResponse()->setHeader('Content-type', 'application/json');
           $this->getResponse()->setBody($jsonData);

       }



  }
