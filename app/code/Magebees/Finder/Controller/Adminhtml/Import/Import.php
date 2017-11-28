<?php
namespace Magebees\Finder\Controller\Adminhtml\Import;
use Magento\Framework\App\Filesystem\DirectoryList;
class Import extends \Magento\Backend\App\Action
{
	protected $_coreRegistry = null;
	protected $resultPageFactory;
 
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }
 
  		
	public function execute()   {
		$data = $this->getRequest()->getPost();       
		$finder_id = $data['finder_id'];
      	if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
			try {
				$uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'filename']);
				$allowed_ext_array = array('csv');
				$uploader->setAllowedExtensions($allowed_ext_array);
				$uploader->setAllowRenameFiles(false);
				$uploader->setFilesDispersion(true);
				$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
					->getDirectoryRead(DirectoryList::VAR_DIR);
				$result = $uploader->save($mediaDirectory->getAbsolutePath('import/'));
				$path = $mediaDirectory->getAbsolutePath('import');
			} catch (\Exception $e) {
				$this->messageManager->addError(__($e->getMessage()));
				$this->_redirect('*/import/index', array('fid' => $finder_id));
				return;	
			}
			
			
			$check_handle = fopen($path.$result['file'],"r");
			$row=0;
			
			$model_finder = $this->_objectManager->create('Magebees\Finder\Model\Finder')->load($finder_id);
				
			$number_of_dropdowns =	$model_finder->getNumberOfDropdowns();
			$check_csv = true;
			while ($line = fgetcsv($check_handle,1000,",","'"))	{ 
				// count($line) is the number of columns
				if($row == 0){
					$numcols = count($line);
					if($numcols-1 != $number_of_dropdowns){
						$check_csv = false;
					}
				}
				$row++;		
			}
			
			if(!$check_csv){
				$this->messageManager->addError(__('CSV columns mismatch. Please check CSV file and import again.'));
				$this->_redirect('*/import/index', array('fid' => $finder_id));
				return;		
			} else{
			
				/**************Delete Existing records if user selected "YES" *************************/
				if($data['existing_data']){
					$model_imported_products = $this->_objectManager->create('Magebees\Finder\Model\Products')->getCollection();
					$model_imported_products->addFieldToFilter('finder_id',$finder_id);
					$model_imported_products->walk('delete');
				}
				/***************************************************************/
		
				//loop through the csv file for create array.
				$collection_of_dropdowns = $this->_objectManager->create('Magebees\Finder\Model\Dropdowns')->getCollection();
				$collection_of_dropdowns->addFieldToFilter('finder_id',$finder_id);
				$insertedRecords="";
				$requiredRecord="";
				$success_cnt = 0;
				$cnt=0;
				$dropdownIDs=$collection_of_dropdowns->getData();
				$model_product = $this->_objectManager->create('Magebees\Finder\Model\Products');
				$handle = fopen($path.$result['file'],"r");
				while (($csvdata = fgetcsv($handle, 1000, ",")) !== FALSE) {
					if($cnt == 0){ 
						$cnt++; continue; 
					}	
					if(!in_array(null, $csvdata)){
						$import_data['sku'] = strip_tags(trim($csvdata[0]));
						$import_data['finder_id'] = $finder_id;
						for($fid=1;$fid<count($csvdata);$fid++){
							if($csvdata[$fid]!=""){
								$import_data['field'.$fid] = trim($csvdata[$fid]);
							}
						
						}
						$model_product->setData($import_data);
						try {
							$model_product->save()->getId();
						} catch (\Exception $e) {
							$this->messageManager->addException($e, __('Something went wrong while saving the product.'));
							//$this->messageManager->addError($e->getMessage());
						}
						$success_cnt++;
						$insertedRecords .=$csvdata[0].","; 
					}else{
						$requiredRecord .=$csvdata[0].","; 
					}
					$cnt++;
				}
				if($insertedRecords!=""){
					$success_msg = $success_cnt." products were successfully imported.";
					$this->messageManager->addSuccess(__($success_msg));
					$this->_getSession()->setFormData(false);
				}
				if($requiredRecord!=""){
					$error_msg_null = $requiredRecord." products not imported, null values is not allowed.";
					$this->messageManager->addError($error_msg_null);
					$this->_getSession()->setFormData(false);
				}
			}
					
			$this->_redirect('*/finder/edit', array('id' => $finder_id));
            return;		
						        
		}
	}
	
	protected function _isAllowed(){
       return $this->_authorization->isAllowed('Magebees_Finder::finder_content');
	}
}