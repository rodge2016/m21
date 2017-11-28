<?php
namespace Magebees\Finder\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class FinderProduct extends \Magento\Catalog\Block\Product\ListProduct {

	/**
     * Product collection model
     *
     * @var Magento\Catalog\Model\Resource\Product\Collection
     */
    protected $_productCollection;
    public  $Ymm;
    /**
     * Initialize
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param array $data
     */
    public function __construct(
    \Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Data\Helper\PostHelper $postDataHelper, \Magento\Catalog\Model\Layer\Resolver $layerResolver, CategoryRepositoryInterface $categoryRepository, \Magento\Framework\Url\Helper\Data $urlHelper, \Magento\Catalog\Model\CategoryFactory $categoryFactory,
	
		array $data = []
    ) {
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
        $this->pageConfig->getTitle()->set(__($this->getPageTitle()));//set Page title
	}

    
	/**
     * Get product collection
     */
	protected function _getProductCollection()
    {
		if ($this->_productCollection === null) {
            $layer = $this->getLayer();
            /* @var $layer \Magento\Catalog\Model\Layer */
        	$this->_productCollection = $layer->getProductCollection();
		}
		return $this->_productCollection;
    }
	
	/**
     * Retrieve loaded category collection
     *
     * @return AbstractCollection
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /* Get the configured title of section */
    public function getPageTitle() {
		$params = trim($this->getRequest()->getRequestString(),'/');
		$finderStr = explode('/',$params);
		
		if(array_key_exists(2,$finderStr)){
			$finderStrArr=explode('?',$finderStr[2]);
				$finderStr[2]=current($finderStrArr);
				
			$finderArr = explode('-',$finderStr[2]);
			$result = implode(' ',$finderArr);
			$configval = $this->_scopeConfig->getValue('finder/general/finderpage_title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $reg = "/[[:punct:]]/i";
            $result = preg_replace($reg, ' ', $result);
            $titlestr = $configval.' '. urldecode ($result).' '.'parts';
            $this->Ymm = $result;
            return $titlestr;
		}else{
			return "Search Result Page";
		}
		
	} 

	//public function getProdcutIdsArr($element){return $element['entity_id'];}
	
	public function getCurrentStore(){
		return $this->_storeManager->getStore(); // give the information about current store
	}
	  public function  getYmm() {
             echo  $Ymm =$this->Ymm;
    }
}
