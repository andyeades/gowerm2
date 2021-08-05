<?php

namespace Elevate\Shell\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateConfigurablePrices extends Command
{
    private $objectManager;

    protected $pageSize = 1000;


    protected $resourceConnection;
    protected $read;
    protected $write;
    protected $table;
  
        
  const FEED_FILE = 'google_merchant_product_feed_peacocks.csv';
  const GOOGLE_PRODUCT_CATEGORY = 'Clothing & Accessories';
  const CUSTOM_LOG_FILE = 'google_merchant_product_feed.log';
  const PRODUCT_TYPE_MAX = 10;

  private $_count_collection;
  private $_count_written = 0;
  private $_count_missing = 0;
  protected $log_file = 'google_feed.log';
  protected $outputFile = 'google_feed.txt';
         protected $_handle;
  protected $fileDelimiter = ',';
  protected $output_skus = [];

  protected $email_data;
  
                   protected $stockRegistry;
  protected $fileHandle;
        protected $productVisibility;
    protected $productStatus;
             protected $dir;    
              protected $_storeManager;
    protected $categoryRepository;   
    protected $_stockFilter;
    protected $productrepository;
                               
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Catalog\Model\ProductRepository $productrepository,
        \Magento\Framework\App\State $state,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\CatalogInventory\Helper\Stock $stockFilter,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    )
    {
        $this->objectManager = $objectmanager;
        $this->productrepository = $productrepository;
        $this->state = $state;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;   
          $this->_storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository; 
        $this->_stockFilter = $stockFilter;  
         $this->dir = $dir;         
         $this->stockRegistry = $stockRegistry;                  
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('elevate:shell:update_configurable_prices')->setDescription('run price cache');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {    $objectManager = $this->objectManager;

        $state = $objectManager->get('Magento\Framework\App\State');
        $state->setAreaCode('frontend');
                                 $this->email_data['total_items'] = 0;
                                    
            echo date('c');

            $initialMem = memory_get_usage();
            //$outputFilename = Mage::getBaseDir('base') . '/' . $this->outputFile;


            $productsCollection = $this->_getProductCollection();

            $pages = $productsCollection->getLastPageNumber();
        

                    $rows = $this->_getRows($productsCollection);
            /*for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
                $productsCollection->setCurPage($currentPage);

                $rows = $this->_getRows($productsCollection);


                echo date('c') . ': ' . "Page: " . $currentPage . "\n";

                // do things

                $productsCollection->clear();
            }
             */

    echo "FINISHED SCRIPT";
    exit;
    
    
    
    
    
    
    
    

        $this->sendEmail($message);
    }

        

    protected function _getProductCollection()
    {



        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        
   //     $productCollection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
       // $productCollection->setVisibility($this->productVisibility->getVisibleInSiteIds());
   $productCollection->addAttributeToFilter('type_id', array('in' => array('configurable')));
        
        $productCollection->addAttributeToSelect('*');
 
    $productCollection->addUrlRewrite();
                           
                            
                            // Sample sku array
//$productCollection->addAttributeToFilter('sku', array('in' => array('PHOENIX_WHITE_WOODEN_OTTOMAN_S.4217')));
    //->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)

        //$productCollection->setStoreId(0);
        //$productCollection->addAttributeToFilter('price', array('eq' => '0'));
        $productCollection->load();


      
       // $productCollection->setPageSize($this->pageSize);
        
   // $this->_stockFilter->addInStockFilterToCollection($productCollection);
         
         
        return $productCollection;
    }





    protected function _getRows( $productcollection)
    {



     
    foreach ($productcollection as $products) {
   // $product = $this->objectManager->create('Magento\Catalog\Model\Product')->load($products->getId());
  
    
$run = false;
 
$_children = $products->getTypeInstance()->getUsedProducts($products);
foreach ($_children as $child){



if(!$run){
   $price = $child->getPrice();
   $was_price = $child->getData('was_price');
   $sale_price = $child->getSpecialPrice();
    
$run = true;
}
else{

   $check_price = $child->getPrice();
   $check_was_price = $child->getData('was_price');
   $check_sale_price = $child->getSpecialPrice();

if($check_price > 0 && $check_price < $price){
   $price = $child->getPrice();
}

if($check_was_price > 0 && $check_was_price < $was_price){
   $was_price = $child->getData('was_price');
}

if($check_sale_price > 0 && $check_sale_price < $sale_price){
   $sale_price = $child->getSpecialPrice();
}


}
 

}
 
 
 $child->setPrice($price);
 
  $child->setWasPrice($was_price);
   $child->setSpecialPrice($sale_price);
   
   $child->save();
     echo "Price = ".$price."\n";         
    echo "Was Price = ".$was_price."\n";
    echo "Sale Price = ".$sale_price."\n";  
   

    
    
 
 
  }

}
}