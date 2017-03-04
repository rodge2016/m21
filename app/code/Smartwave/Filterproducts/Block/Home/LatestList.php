<?php

namespace Smartwave\Filterproducts\Block\Home;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class LatestList extends \Magento\Catalog\Block\Product\ListProduct {

    /**
     * Product collection model
     *
     * @var Magento\Catalog\Model\Resource\Product\Collection
     */
    protected $_collection;

    /**
     * Product collection model
     *
     * @var Magento\Catalog\Model\Resource\Product\Collection
     */
    protected $_productCollection;

    /**
     * System configuration values
     *
     * @var array
     */
    protected $_config;

    /**
     * Image helper
     *
     * @var Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;
    
    /**
     * Catalog Layer
     *
     * @var Magento\Catalog\Model\Layer\Resolver
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;
    
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    
    /**
     * Initialize
     *
     * @param Magento\Catalog\Block\Product\Context $context
     * @param Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository,
     * @param Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param Magento\Framework\Url\Helper\Data $urlHelper
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
    \Magento\Catalog\Block\Product\Context $context, 
            \Magento\Framework\Data\Helper\PostHelper $postDataHelper, 
            \Magento\Catalog\Model\Layer\Resolver $layerResolver, 
            CategoryRepositoryInterface $categoryRepository,
            \Magento\Framework\Url\Helper\Data $urlHelper, 
            \Magento\Catalog\Model\ResourceModel\Product\Collection $collection, 
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, 
            \Magento\Catalog\Helper\Image $imageHelper, 
            array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->_collection = $collection;
        $this->_config = $scopeConfig->getValue('filterproducts/latest');
        $this->_imageHelper = $imageHelper;

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    /**
     * Get product collection
     */                                              
    public function getProducts() {
        $limit = $this->getProductLimit();                       
        $category_id = $this->getData("category_id");
        $this->_collection->clear()->getSelect()->reset(\Magento\Framework\DB\Select::WHERE)->reset(\Magento\Framework\DB\Select::ORDER)->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET)->reset(\Magento\Framework\DB\Select::GROUP);
        if(!$category_id) {
            $category_id = $this->_storeManager->getStore()->getRootCategoryId();
        }
        $category = $this->categoryRepository->get($category_id);
        if(isset($category) && $category) {
            $collection = $this->_collection
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                ->addUrlRewrite()
                ->addAttributeToFilter('is_saleable', 1, 'left')
                ->addCategoryFilter($category)
                ->addAttributeToSort('created_at','desc');
        } else {
            $collection = $this->_collection
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                ->addUrlRewrite()
                ->addAttributeToFilter('is_saleable', 1, 'left')
                ->addAttributeToSort('created_at','desc');
        }
        
        $collection->getSelect()
                ->order('created_at','desc')
                ->limit($limit);
                
        $this->_productCollection = $collection;
        return $this->_productCollection;
    }

    /**
     * load and return product collection
     */
    public function getLoadedProductCollection() {
        return $this->getProducts();
    }

    /**
     * Get product toolbar
     */
    public function getToolbarHtml() {
        return '';
    }

    /**
     * Get grid mode
     */
    public function getMode() {
        return 'grid';
    }

    /**
     * Get image helper
     */
    public function getImageHelper() {
        return $this->_imageHelper;
    }

    /**
     * Get module configuration
     */                                
    public function getConfig() {                
        return $this->_config;
    }

    /**
     * Check that module is enabled or not
     * @return int
     */
    public function getSectionStatus() {
        return $this->_config["enable"];
    }

    /**
     * Get the configured limit of products
     * @return int
     */
    public function getProductLimit() {
        $limit = $this->getData("product_count");
        if(!$limit)
            $limit = $this->_config["limit"];
        return $limit;
    }

    /**
     * Get the configured title of section
     * @return int
     */
    public function getPageTitle() {
        return $this->_config["title"];
    }

}
