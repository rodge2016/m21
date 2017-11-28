<?php
namespace Magebees\Finder\Model\ResourceModel\Fulltext;

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Search\Adapter\Mysql\Adapter;
use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;
use Magento\Framework\Search\Response\Aggregation\Value;
use Magento\Framework\Search\Response\QueryResponse;

/**
 * Fulltext Collection
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
{
    /** @var  QueryResponse */
    protected $queryResponse;

    /**
     * Catalog search data
     *
     * @var \Magento\Search\Model\QueryFactory
     */
    protected $queryFactory = null;

    /**
     * @var \Magento\Framework\Search\Request\Builder
     */
    private $requestBuilder;

    /**
     * @var \Magento\Search\Model\SearchEngine
     */
    private $searchEngine;

    /** @var string */
    private $queryText;

    /** @var string|null */
    private $order = null;

    /** @var string */
    private $searchRequestName;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory
     */
    private $temporaryStorageFactory;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Search\Model\QueryFactory $catalogSearchData,
        \Magento\Framework\Search\Request\Builder $requestBuilder,
        \Magento\Search\Model\SearchEngine $searchEngine,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
		\Magento\Framework\App\Request\Http $urlInterfaceObj,
		\Magebees\Finder\Model\Products $finderProduct,
		\Magebees\Finder\Model\UniversalProduct $universalProduct,
		\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        $searchRequestName = 'catalog_view_container'
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $moduleManager,
            $catalogProductFlatState,
            $scopeConfig,
            $productOptionFactory,
            $catalogUrl,
            $localeDate,
            $customerSession,
            $dateTime,
            $groupManagement,
            $catalogSearchData,
			$requestBuilder,
			$searchEngine,
			$temporaryStorageFactory,
			$connection
        );
		$this->requestBuilder = $requestBuilder;
        $this->urlInterfaceObj = $urlInterfaceObj;
		$this->finderProduct = $finderProduct;
		$this->universalProduct = $universalProduct;
		$this->scopeConfig = $scopeConfig;
	}

    public function getFinderProductsSkus($finderStr,$finderId){
		$product_skus = array();
		$finderCollection = $this->finderProduct->getCollection()
									->addFieldToFilter('finder_id',$finderId);
		
		$finderArr = array();
		$finderStr = urldecode($finderStr);
		$finderArr = explode("-",$finderStr);
				
		for($i=0;$i<(count($finderArr));$i++)	{
			$finderCollection->addFieldToFilter("field".($i+1),$finderArr[$i]);
		}
		//print_R($finderCollection->getSelect()->__toString());exit;
		
		$product_skus = array_map(array($this,"getProdcutSkusArr"), $finderCollection->getData());
		
		return $product_skus;
	}
		
	public function getProdcutSkusArr($element){return $element['sku'];}
	
	/**
     * @inheritdoc
     */
    protected function _renderFiltersBefore()
    {
		//Filter for finder 
		//product_list_order
		
		$path = $this->urlInterfaceObj->getRequestString();
		$search = strpos($path, 'finder');
		$skus = array();
		if($search){
			$path = trim($path, '/');
			$finderparams = array();
			$finderparams = explode('/',$path);
			if(empty($finderparams[2])){
				$skus = array('0'=>'');		
			}else{
				//$finderStr = end($finderparams);
				$finderStr = $finderparams[2];
				$finderId = $finderparams[1];
				$skus = $this->getFinderProductsSkus($finderStr,$finderId);

				//set sort orders
				$enable_universal = $this->scopeConfig->getValue('finder/general/universal_enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
				if($enable_universal){
					$universal_collection = $this->universalProduct->getCollection()->addFieldToFilter('finder_id',$finderId);
					$universal_products_skus = array_map(array($this,"getProdcutSkusArr"), $universal_collection->getData());
					if(!empty($universal_products_skus)){
						$skus = array_unique(array_merge($skus,$universal_products_skus));
						$search_skus = array_diff($skus,$universal_products_skus);
						if(!$this->urlInterfaceObj->getParam('product_list_order')){
						//Setting Sort order which sort based on the array elements order
							$sort_order = $this->scopeConfig->getValue('finder/general/sort_order', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
							if($sort_order == 2){
								$this->getSelect()->order("find_in_set(e.sku,'".implode(',',$universal_products_skus)."')");//display universal products last
							}elseif($sort_order == 3){
								$this->getSelect()->order("find_in_set(e.sku,'".implode(',',$search_skus)."')");//display search products last
							}
						}
					}
				}
			}
			$this->requestBuilder->bind('sku', $skus);
		}
	    return parent::_renderFiltersBefore();
    }
	
	public function setOrder($attribute, $dir = Select::SQL_DESC)
    {
        $this->order = ['field' => $attribute, 'dir' => $dir];
        if ($attribute != 'relevance') {
            parent::setOrder($attribute, $dir);
        }
        return $this;
    }
	
}
