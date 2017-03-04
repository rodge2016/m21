<?php
namespace Smartwave\com\Controller\Index;

class Index2 extends \Magento\Framework\App\Action\Action {

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {

        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        echo '<h3> Index2.php  compatibility  module</br>';
        $version = apache_get_version();
         echo "<h2>$version\n";
         echo 'call test  controller';
          echo  "display  controller";
    }

      
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_AdminNotification::show_list');
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */

        public function execute()
    {

        echo 'call test';
        return $this->resultPageFactory->create();

    }
       public function test(){
        echo 'call test';

     }
}
?>
<script>
//    alert(window.location.href);
       alert($().jquery);
</script>

