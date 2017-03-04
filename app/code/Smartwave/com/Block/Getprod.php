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

class Getprod extends \Magento\Framework\View\Element\Template
{

    protected $_defaultToolbarBlock = 'Magento\Catalog\Block\Product\ProductList\Toolbar';

    protected $_productCollection;

    protected $_catalogLayer;

    protected $_postDataHelper;

    protected $urlHelper;

    protected $categoryRepository;

    protected $request;

    protected $_productRepository;

    protected $_productCollectionFactory;

    public $_storeManager;

    public function __construct(

        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,

        RequestInterface $request,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    )
    {
        $this->_storeManager=$storeManager;
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;


        $this->request = $request;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }

    public function _getProductCollection()
    {
          $year =$this->getRequest()->getParam('year');
          $make =$this->getRequest()->getParam('make');
          $model =$this->getRequest()->getParam('model');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $sku= $connection->fetchAll(("SELECT DISTINCT sku  FROM relation_id  WHERE  c_table_id IN (  SELECT   c_table_id  FROM compatibility where year ='{$year}' AND make ='{$make}'  AND  model ='{$model}') "));
//        echo'<pre>';
//        var_dump($sku);

        if (count($sku) == 0)
        {
            $collection = $this->_productCollectionFactory->create();
            $collection->setPageSize(200);
            $p = array(0);
            $collection->addFieldToFilter('sku',$p);
//            echo "pid=0" ;
            return $collection;
        }
          else{
//                   echo "pid=1" ;
                foreach($sku as $k=>$val){
                $val["sku"];
                    foreach($val as  $va ){
                        $p[] = $va;
                     }
                }
//                           1981   MotoGuzzi    850 T4
//              201-AT706*2	 1988	kawasaki	 bayou 300
//              216-CK252*2	 2003	yamaha	     yzf r1
//              $p =  ksort($p);
   //                  var_dump($p);
//                  echo'</pre>';
//              $p = array( "216-CK252*2" );
//                $p =   ksort($p);
//                 $p = array("216-CK252*2", "203-O530-108-GD" ,"203-O530-108-GN","2038-GN","203O530-108-GN","203-O0-108-GN","203-O530-108-G","203-O53-108-GN","203-O30-108-GN","203-O530108-GN","203-O530--GN","203-O530-10-GN","203-O53008-GN","203-O530-10-GN","203-O530-108GN","203-SP039-N520-86-BU","203-N520-86-BU","299-MT023","299-MT020","299-MT001","203-SP039","298-AR002","203-SP039-N520-86-YL","203-SP039-N520-86-GD","203-O520-86-RD","203-N520-86-YL","223-CP015","203-O520-86-GD","203-SP039-N520-86-BK","203-O520-86-BU","203-O520-86-BK","203-O520-86-YL","203-O520-86-GN","299-MT002","203-N520-86-GD","203-SP039-O520-86-GD","299-MT001-002","230-OF003*3","561-AE002","203-SP039-N520-86-RD","203-N520-86-RD","203-SP039-N520-86-GN","299-MT002","216-CK252*2", "203-O530-108-GD" ,"203-O530-108-GN","2038-GN","203O530-108-GN","203-O0-108-GN","203-SP039-N520-86-BK","203-O520-86-BU","203-O520-86-BK","203-N520-86-YL","299-MT002","299-MT001-002","216-CK252*2","201-AT101_01","201-AT101-527","201-AT104","201-AT1242","201-AT1242*2-693");
//              $p = array("216-CK252*2", "203-O530-108-GD" ,"203-O530-108-GN","2038-GN","203O530-108-GN","203-O0-108-GN","203-SP039-N520-86-BK","203-O520-86-BU","203-O520-86-BK","203-N520-86-YL","299-MT002","299-MT001-002","216-CK252*2","201-AT101_01","201-AT101-527","201-AT104","201-AT1242","201-AT1242*2-693");

//                $p = array(4339,4339,4339,4338,4332,4337,4336,4432,4327);
//                echo "pid=1";
                 $collection = $this->_productCollectionFactory->create();
                 $collection->addAttributeToSelect('*');
                 $collection->setPageSize(200);
//                 $collection->addFieldToFilter('sku',$p);
                 $collection->addAttributeToFilter('sku',$p);
//                 $collection->addOrder('sku');
                 return $collection;
              }

        }


    public function getLayer()
    {
        return $this->_catalogLayer;
    }

    public function get_url(){

     return  $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->getChildBlock('toolbar')->getCurrentMode();
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getProductCollection();

        // use sortable parameters
        $orders = $this->getAvailableOrders();
        if ($orders) {
            $toolbar->setAvailableOrders($orders);
        }
        $sort = $this->getSortBy();
        if ($sort) {
            $toolbar->setDefaultOrder($sort);
        }
        $dir = $this->getDefaultDirection();
        if ($dir) {
            $toolbar->setDefaultDirection($dir);
        }
        $modes = $this->getModes();
        if ($modes) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $this->_getProductCollection()]
        );

        $this->_getProductCollection()->load();

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block
     *
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();
        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, uniqid(microtime()));
        return $block;
    }

    /**
     * Retrieve additional blocks html
     *
     * @return string
     */
    public function getAdditionalHtml()
    {
        return $this->getChildHtml('additional');
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * @param AbstractCollection $collection
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->_productCollection = $collection;
        return $this;
    }

    /**
     * @param array|string|integer|\Magento\Framework\App\Config\Element $code
     * @return $this
     */
    public function addAttribute($code)
    {
        $this->_getProductCollection()->addAttributeToSelect($code);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriceBlockTemplate()
    {
        return $this->_getData('price_block_template');
    }

    /**
     * Retrieve Catalog Config object
     *
     * @return \Magento\Catalog\Model\Config
     */
    protected function _getConfig()
    {
        return $this->_catalogConfig;
    }

    /**
     * Prepare Sort By fields from Category Data
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return \Magento\Catalog\Block\Product\ListProduct
     */
    public function prepareSortableFieldsByCategory($category)
    {
        if (!$this->getAvailableOrders()) {
            $this->setAvailableOrders($category->getAvailableSortByOptions());
        }
        $availableOrders = $this->getAvailableOrders();
        if (!$this->getSortBy()) {
            $categorySortBy = $this->getDefaultSortBy() ?: $category->getDefaultSortBy();
            if ($categorySortBy) {
                if (!$availableOrders) {
                    $availableOrders = $this->_getConfig()->getAttributeUsedForSortByArray();
                }
                if (isset($availableOrders[$categorySortBy])) {
                    $this->setSortBy($categorySortBy);
                }
            }
        }

        return $this;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->_getProductCollection() as $item) {
            $identities = array_merge($identities, $item->getIdentities());
        }
        $category = $this->getLayer()->getCurrentCategory();
        if ($category) {
            $identities[] = Product::CACHE_PRODUCT_CATEGORY_TAG . '_' . $category->getId();
        }
        return $identities;
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getProductPrice(\Magento\Catalog\Model\Product $product)
    {
        $priceRender = $this->getPriceRender();

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                [
                    'include_container' => true,
                    'display_minimal_price' => true,
                    'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
                ]
            );
        }

        return $price;
    }

    /**
     * @return \Magento\Framework\Pricing\Render
     */
    protected function getPriceRender()
    {
        return $this->getLayout()->getBlock('product.price.render.default');
    }
}

?>