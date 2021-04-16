<?php

namespace Elevate\Reviews\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetReviews extends Command
{
    private $objectManager;
    protected $log_file = 'sale_flag.log';
    protected $pageSize = 1000;
    protected $fileDelimiter = ',';

    protected $resourceConnection;
    protected $read;
    protected $write;
    protected $table;
    protected $fileHandle;
    protected $boostArray = [];
    protected $_orderCollectionFactory;
    protected $orderRepository;
    protected $merchant = 'posturite';
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productrepository,
        \Magento\Framework\App\State $state,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    )
    {
        $this->objectManager = $objectmanager;
        $this->productRepository = $productrepository;
        $this->state = $state;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('elevate:reviews:getreviews')->setDescription('get reviews');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
            echo date('c');

            $initialMem = memory_get_usage();
            //$outputFilename = Mage::getBaseDir('base') . '/' . $this->outputFile;


            $productsCollection = $this->_getProductCollection();

            $pages = $productsCollection->getLastPageNumber();
            $currentPage = 1;
            echo "\n Pages: " . $pages . "\n";


            for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
                $productsCollection->setCurPage($currentPage);

                $rows = $this->_getRows($productsCollection);


                echo date('c') . ': ' . "Page: " . $currentPage . "\n";

                // do things

                $productsCollection->clear();
            }
  
      
        echo "END";
        exit;

        $this->sendEmail($message);
    }



    protected function _getProductCollection()
    {

        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection->addAttributeToSelect('feefo_link_sku');
        //$productCollection->setStoreId(0);
                                      
             
   // $productCollection->addAttributeToFilter('sku', array('eq' => 'OPLOFTWEB'));
        $productCollection->load();

        $productCollection->setOrder('entity_id', 'DESC');
        $productCollection->setPageSize($this->pageSize);
        return $productCollection;
    }





    protected function _getRows($productcollection)
    {

           
      
                   

        foreach ($productcollection as $product) {
        
                 

            $id = $product->getId();

            $feefo_link_sku = $product->getData('feefo_link_sku');

            if (empty($feefo_link_sku)) {
                $feefo_link_sku = $product->getSku();
            }
                       
            $feefo_link_sku = str_replace(' ', '%20', $feefo_link_sku);
            $rating = 0;
            $sku = $product->getSku();   
            $rating_count = 0;
             $feefo_merchant_id = 'happy-beds';    $sku_lookup_type = 'parent_product_sku';
              
              if($this->merchant == 'posturite'){
              $feefo_merchant_id = 'posturite-ltd';
              $sku_lookup_type = 'product_sku';
              }
              
              $optionIds = [];      
                     $optionIds_num = [];
            try {

                //echo "in try loop";
                $page = file_get_contents("https://api.feefo.com/api/10/reviews/summary/product?merchant_identifier=".$feefo_merchant_id."&".$sku_lookup_type."=" . $feefo_link_sku . "&since_period=all");
             echo "https://api.feefo.com/api/10/reviews/summary/product?merchant_identifier=".$feefo_merchant_id."&".$sku_lookup_type."=" . $feefo_link_sku . "&since_period=all";
                $ap = json_decode($page, true); // decode the json record

                $rating = $ap['rating']['rating'];

                // echo "https://api.feefo.com/api/11/products/ratings?merchant_identifier=happy-beds&parent_product_sku=".$feefo_link_sku."&since_period=all";
                // $product_count = $ap['rating']['product']['count'];
                // $service_count = $ap['rating']['service']['count'];
                // $meta_count = $ap['meta']['count'];
                // $rating = $ap['rating']['rating'];
                 
                if (!is_numeric($rating) || $rating == 0) {
                    $page = file_get_contents("https://api.feefo.com/api/11/products/ratings?merchant_identifier=".$feefo_merchant_id."&".$sku_lookup_type."=" . $feefo_link_sku . "&since_period=all");

                    $ap = json_decode($page, true); // decode the json record
                    $counter = 0;
                    $rating = 0;
                    
                    if(isset($ap['products'])){
                    foreach ($ap['products'] AS $key => $val) {
                        $counter++;

                        $rating = $rating + $val['rating'];

                    }
                    }
                    if ($rating > 0) {
                        $rating = round(($rating / $counter), 1);
                    }

                }
                      
                if ($rating > 0) {

                    //  echo "https://api.feefo.com/api/10/reviews/summary/all?merchant_identifier=happy-beds&parent_product_sku=".$feefo_link_sku."&since_period=all";
                    try {
                        $feefo_link_sku2 = str_replace(' ', '%20', $feefo_link_sku);
                        $pagecount = file_get_contents("https://api.feefo.com/api/10/reviews/summary/all?merchant_identifier=".$feefo_merchant_id."&".$sku_lookup_type."=" . $feefo_link_sku2 . "&since_period=all");

                    } catch(Exception $e) {
                        print_r($e->getMessage());
                    }

                    $apcount = json_decode($pagecount, true); // decode the json record

                    $rating_count = $apcount['rating']['product']['count'];
                }
                        
                if (!is_numeric($rating)) {
                    $rating = 0;

                }
                    
                if ($rating >= 4) {
                    $adj_rating = 4;
                    
                      $optionIds_num[] = 4;
                    $colourId = $product->getResource()->getAttribute("filter_rating")->getSource()->getOptionId(4);
                    $optionIds[] = $colourId;
                }
                if ($rating >= 3) {
                    $adj_rating = 3;
                         $optionIds_num[] = 3;
                    $colourId = $product->getResource()->getAttribute("filter_rating")->getSource()->getOptionId(3);
                    $optionIds[] = $colourId;
                }
                if ($rating >= 2) {
                    $adj_rating = 2;
                         $optionIds_num[] = 2;
                    $colourId = $product->getResource()->getAttribute("filter_rating")->getSource()->getOptionId(2);
                    $optionIds[] = $colourId;
                }
                if ($rating >= 1) {
                    $adj_rating = 1;
                         $optionIds_num[] = 1;
                    $colourId = $product->getResource()->getAttribute("filter_rating")->getSource()->getOptionId(1);
                    $optionIds[] = $colourId;
                }
                      
                // echo "RATING4=$rating\n";
                //if(count($optionIds) > 0){
                                //   print_r($optionIds);
                                   $optionIds_imp = implode(',', $optionIds); 
                //print_r($optionIds);
                 
                  
               $product->setData('filter_rating', $optionIds_imp);

                //}

                //$resource = $product->getResource();
                $product->setData('product_rating', $rating);
                //$resource->saveAttribute($product, 'product_rating');
                $product->setData('rating_count', $rating_count);


 $product->getResource()->saveAttribute($product, 'product_rating');
$product->getResource()->saveAttribute($product, 'rating_count');
$product->getResource()->saveAttribute($product, 'filter_rating');
                //echo "before product save";
                //print_r($product->getData());
         //    $product->getResource()->save($product);

                //echo "after product save";
                     
                unset($optionIds);
                echo $sku . "|" . $rating . "|$rating_count" . "\n";


              //  $writer->writeRow("$sku, $rating, $rating_count");

                //
                //$product->setRatingFilter($colourId)->save();

                //    $product->save();

               // if ($product_count != 0) {
                    //	$model = Mage::getModel('reviewfeefo/reviewfeefo');
                    //		$model->setSku($sku);
                    //			$model->setAverage($rating);
                    //				$model->setCount($product_count);
                    //					$model->save();
              //  }
                //echo "end of loop";
            } catch(\Exception $e) {
                print_r($e->getMessage());
            }
            
            
            //end feefo
        
        

            //exit;
        }





    }
    private function sendEmail($message){
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
    }
}
