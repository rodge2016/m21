<?php
namespace Smartwave\com\Block;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\RequestInterface;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject\IdentityInterface;




class Getprod extends \Magento\Framework\View\Element\Template
{
    protected $request;
    protected $_productRepository;
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

    public function getProductCollection()
    {
        $year =$this->getRequest()->getParam('year');
        $make =$this->getRequest()->getParam('make');
        $model =$this->getRequest()->getParam('model');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection    ('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $pid= $connection->fetchAll(("SELECT DISTINCT product_id  FROM relation_id  WHERE comp_id IN (  SELECT  comp_id  FROM compatibility where year ='{$year}' AND make = '{$make}'  AND  model = '{$model}')"));
         var_dump($pid);
        if (count($pid) == 0)
        {
            $collection = $this->_productCollectionFactory->create();
            return $collection;
        }
          else{
                foreach($pid as $k=>$val){
                $val["product_id"];
                foreach($val as  $va ){
                    $p[] = $va;
                 }
                }
                 $collection = $this->_productCollectionFactory->create();
                 $collection->addAttributeToSelect('*');
        //       $collection->setPageSize(4);
        //       array("203-O530-110-GD", "203-O530-108-GD",  "203-O530-108-GN");
                 $collection->addFieldToFilter('entity_id',$p);
                 return $collection;
              }









        }




    }
?>