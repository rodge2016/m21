<?php
namespace Magebees\Finder\Block;
use Magento\Store\Model\ScopeInterface;
class Finder extends \Magento\Framework\View\Element\Template {
	
	protected $_dropdownsFactory;
	protected $_finderFactory;
	protected $_productsFactory;
	protected $categoryFactory;
	protected $coreRegistry;
			
	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
		\Magebees\Finder\Model\DropdownsFactory $dropdownsFactory,
		\Magebees\Finder\Model\FinderFactory $finderFactory,
		\Magebees\Finder\Model\ProductsFactory $productsFactory,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Framework\Registry $coreRegistry
	) {
        parent::__construct($context);
		$this->_dropdownsFactory = $dropdownsFactory;
		$this->_finderFactory = $finderFactory;
		$this->_productsFactory = $productsFactory;
		$this->categoryFactory = $categoryFactory;
		$this->coreRegistry = $coreRegistry;
		    						
		//Set Configuration values
		$this->setEnabled($this->_scopeConfig->getValue('finder/general/enabled',ScopeInterface::SCOPE_STORE));
		$this->setAutosearch($this->_scopeConfig->getValue('finder/general/autosearch',ScopeInterface::SCOPE_STORE));
		$this->setCategoryPageEnabled($this->_scopeConfig->getValue('finder/general/categorypage_enabled',ScopeInterface::SCOPE_STORE));
		$this->setFindBtnText($this->_scopeConfig->getValue('finder/general/find_btn_text',ScopeInterface::SCOPE_STORE));
		$this->setResetBtnText($this->_scopeConfig->getValue('finder/general/reset_btn_text',ScopeInterface::SCOPE_STORE));
	}
	
	public function getDropdownsCollectionByFinderId($finderId){
		return $this->_dropdownsFactory->create()->getCollection()
						->addFieldToFilter('finder_id',$finderId)
		;
	}
	
	public function getFinderById($finderId){
		return $this->_finderFactory->create()->load($finderId);
	}
	
	//get dropdowns value by finder id and also do sorting of data specified in admin
	public function getProductsByFinderId($finderId,$sort,$field,$finderStr = null){
		$productsCollection = $this->_productsFactory->create()->getCollection()
									->addFieldToFilter('finder_id',$finderId);
		if($field>1){
			if($finderStr){
				$str = explode("-",$finderStr);
				for($i=0;$i<($field-1);$i++){
					if(array_key_exists($i,$str)){
						$productsCollection->addFieldToFilter("field".($i+1),$str[$i]);
					}else{
						return null;
					}
				}
				return $productsCollection->distinct(true)->setOrder("field".$field,$sort);	
			}
		}else{
			return $productsCollection->distinct(true)->setOrder("field".$field,$sort);	
		}
		
	}
	
	//get finder ids by category ids
	public function getFinderIdsByCategoryId(){
		$category = $this->getCurrentCategory();
		//get current category
		$collection = $this->_finderFactory->create()->getCollection();
		$collection->addFieldToFilter('category_ids',array(array('finset' => $category->getId())));
		$finderIds = array_map(array($this,"getFinderIdssArr"), $collection->getData());
		return $finderIds;
	}
	
	public function getFinderIdssArr($element){return $element['finder_id'];}	
	
	public function getProductsByFinderIdForCategory($finderId,$sort,$field,$skus,$finderStr = null){
		$productsCollection = $this->_productsFactory->create()->getCollection()
									->addFieldToFilter('finder_id',$finderId)
									->addFieldToFilter('sku',array('in'=>$skus))
									;
		if($field>1){
			if($finderStr){
				$str = explode("-",$finderStr);
				for($i=0;$i<($field-1);$i++){
					if(array_key_exists($i,$str)){
						$productsCollection->addFieldToFilter("field".($i+1),$str[$i]);
					}else{
						return null;
					}
				}
				return $productsCollection->distinct(true)->setOrder("field".$field,$sort);	
			}
		}else{
			return $productsCollection->distinct(true)->setOrder("field".$field,$sort);	
		}
	}
	
	public function getCurrentCategory(){
		$category = $this->coreRegistry->registry('current_category');
		return $category;
	}
	
	public function getProductsByCategoryId()	{
		$categoryId = $this->getCurrentCategory()->getId();
		$category = $this->categoryFactory->create()->load($categoryId);
		$skus = $category->getProductCollection()->addAttributeToSelect('sku')
										 ->getColumnValues('sku')
										;
		return $skus;
	}
	
	public function displayFinder($finderId,$skus){
		return $this->_productsFactory->create()->getCollection()
									->addFieldToFilter('finder_id',$finderId)
									->addFieldToFilter('sku',array('in'=>$skus))
									->getSize();
									;
	}
}