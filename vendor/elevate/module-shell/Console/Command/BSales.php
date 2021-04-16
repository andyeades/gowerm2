<?php

namespace Elevate\Shell\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Bsales extends Command
{
    private $objectManager;
    protected $log_file = 'sale_flag.log';
    protected $pageSize = 1000;
    protected $fileDelimiter = ',';


/* Debug Info */
    protected $limitCollectionSku = '';
   // protected $limitCollectionSku = 'AMERICAN_WHITE_FINISH_SOLID_PI.854';



    protected $dateFromOverride = '2021-01-01 00:00:00';
    protected $dateToOverride = '2021-02-25 23:59:59';
                                
    //protected $dateToOverride = '2019-12-03 23:59:59';

    protected $productRun = true;
    protected $salesRun = true;
    protected $_processData = true;    
    protected $genRun = true;
    protected $evenDistribution = true;
    /*end debug info*/

    protected $resourceConnection;
    protected $read;
    protected $write;
    protected $table;
    protected $fileHandle;
    protected $boostArray = [];
    protected $_orderCollectionFactory;
    protected $orderRepository;

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
        $this->setName('elevate:shell:bsales')->setDescription('run bsales');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $gen_run = $this->genRun;
        $sales_run = $this->salesRun;
        $product_run = $this->productRun;
        $even_distribution_run = $this->evenDistribution;
        
        $categoryFactory = $this->objectManager->get('\Magento\Catalog\Model\CategoryFactory');
        $categoryId = 179;
        $category = $categoryFactory->create()->load($categoryId);
        $productslist = $category->getProductCollection()->addAttributeToSelect('*');

        $boost = 1000;

        foreach ($productslist as $product)
        {
            $this->boostArray[$product->getSku()] = $boost;
            $boost = $boost - 10;
        }


        $connection  = $this->resourceConnection->getConnection();


        $this->table = $connection->getTableName('elevate_bestseller_data');

           if($product_run){
           
           
               $truncate_q = "DELETE FROM  elevate_product_data;";
            $connection->query($truncate_q);




            echo date('c');

            $initialMem = memory_get_usage();
            //$outputFilename = Mage::getBaseDir('base') . '/' . $this->outputFile;


            $productsCollection = $this->_getProductCollectionProduct();

            $pages = $productsCollection->getLastPageNumber();
            $currentPage = 1;
            echo "\n Pages: " . $pages . "\n";



                $rows = $this->_getRowsProduct($productsCollection);


                echo date('c') . ': ' . "Page: " . $currentPage . "\n";
          //      exit;

                // do things
                     
         

        }




      
        //truncate existing rules
        if($sales_run){
               $truncate_q = "DELETE FROM  elevate_order_data;";
            $connection->query($truncate_q);

    



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
          //      exit;

                // do things
                     
                $productsCollection->clear();
            }

        }



             if($this->_processData){
                       $truncate_q = "DELETE FROM  ".$this->table.";";
            $connection->query($truncate_q);
           $this->processData();
           }
        //assign the bestsellers
        if($gen_run){
            echo date('c');

            $initialMem = memory_get_usage();



            $productsCollection2 = $this->_getProductCollection2();

            $pages = $productsCollection2->getLastPageNumber();
            $currentPage = 1;
            echo "\n Pages: " . $pages . "\n";


           

                $rows2 = $this->_getRows2($productsCollection2);


                echo date('c') . ': ' . "Page: " . $currentPage . "\n";

                // do things

        


        }


        
        if($even_distribution_run){

            echo date('c');

           


 $attribute_information_unique = "Select distinct(associated_product_id) FROM associated_configurable_link "; //check for the  custom attribute condition". WHERE id = " . $manufacture . ";";


// fetchOne it return the one value
$_associatedProductsUnique = $this->resourceConnection->getConnection()->fetchAll($attribute_information_unique);


if (!empty($_associatedProductsUnique)) {
    foreach ($_associatedProductsUnique as $_associatedProductUnique) {  
    
    
    
    
    $attribute_information = "Select * FROM associated_configurable_link WHERE product_id = '".$_associatedProductUnique['associated_product_id']."'"; //check for the  custom attribute condition". WHERE id = " . $manufacture . ";";
    $_associatedProducts = $this->resourceConnection->getConnection()->fetchAll($attribute_information);
                
      $bestseller = [];
if (!empty($_associatedProducts)) {
    foreach ($_associatedProducts as $_associatedProduct) {
                                      
        // echo $_associatedProduct['associated_product_id'];
        // $swatch_info = $_product->getResource()->getAttributeRawValue($_associatedProduct['associated_product_id'],'swatch_colour', $storeID);


        $_associatedProduct = $this->objectManager->create('Magento\Catalog\Model\Product')->load($_associatedProduct['associated_product_id']);
      //  $swatch_info = $_associatedProduct->getAttributeText('swatch_colour');
                
           $bestseller[$_associatedProduct->getData('bestseller')]  = $_associatedProduct->getId();
        
        
       //     echo "\nRANK:".$sd."\n";
       //     $productIds = [$product->getId()];
       //     $attributesData = ['bestseller' => $sd];
       //     $storeId = 0;
       //     $productMassAction = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Catalog\Model\Product\Action');
       //     $productMassAction->updateAttributes($productIds, $attributesData, $storeId);
        
        }
        $bestseller2 = [];
          krsort($bestseller);
                    $best_count = 0;
          foreach($bestseller AS $bestkey => $bestval){
                   if($best_count == 0){
                    $bestseller2[$bestval] = $bestkey;
                   }
                   else{
                    $bestseller2[$bestval] = ($bestkey / 1.1);
                   }
                   
                   
                   $best_count++;
          
          }
          foreach($bestseller2 AS $key => $val){
          
                 echo "\nRANK:".$key."|".$val."\n";
       $productIds = [$key];
          $attributesData = ['bestseller' => $val];
        $storeId = 0;
       $productMassAction = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Catalog\Model\Product\Action');
          $productMassAction->updateAttributes($productIds, $attributesData, $storeId);
          }
          print_r($bestseller2);
                  
        
        }
        
            
          //      exit;

                // do things
                      
        
          }
          }
                              
        }
        echo "END NEXT";
        exit;

        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection->addAttributeToSelect('*');
        $productCollection->setStoreId(0);
        $productCollection->addAttributeToFilter('price', array('eq' => '0'));
        $productCollection->load();

        $message = '';

        foreach ($productCollection as $product){
            $product->setStoreId(0);
            echo $product->getSku().": ".$product->getName(). "-" . $product->getPrice() . "\n";
            $message .= $product->getSku().": ".$product->getName()."\n";
            $product->setPrice('10000');
            $product->setSpecialPrice('10000');
            $product->save();
            $product->setStoreId(4);
            $product->setPrice('10000');
            $product->setSpecialPrice('10000');
            $product->save();
        }

        $this->sendEmail($message);
    }
       protected function _getProductCollectionProductEvenDist()
    {


        $limitCollectionSku = $this->limitCollectionSku;
        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection->addAttributeToSelect('*');
        //$productCollection->setStoreId(0);
        //$productCollection->addAttributeToFilter('price', array('eq' => '0'));
         $productCollection->addAttributeToFilter('sku', array('eq' => 'PHOENIX_WHITE_WOODEN_OTTOMAN_S.4217'));
        if(!empty($limitCollectionSku)){

            $productCollection->addAttributeToFilter('sku', array('eq' => $limitCollectionSku));


        }

        $productCollection->load();


        $productCollection->setOrder('entity_id', 'DESC');
        $productCollection->setPageSize($this->pageSize);
        return $productCollection;
    }
       protected function _getProductCollectionProduct()
    {


        $limitCollectionSku = $this->limitCollectionSku;
        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection->addAttributeToSelect('*');
        //$productCollection->setStoreId(0);
        //$productCollection->addAttributeToFilter('price', array('eq' => '0'));

        if(!empty($limitCollectionSku)){

            $productCollection->addAttributeToFilter('sku', array('eq' => $limitCollectionSku));


        }

        $productCollection->load();


        $productCollection->setOrder('entity_id', 'DESC');
        $productCollection->setPageSize($this->pageSize);
        return $productCollection;
    }
    protected function _getProductCollection2()
    {


        $limitCollectionSku = $this->limitCollectionSku;
        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection->addAttributeToSelect('*');
        //$productCollection->setStoreId(0);
        //$productCollection->addAttributeToFilter('price', array('eq' => '0'));

        if(!empty($limitCollectionSku)){

            $productCollection->addAttributeToFilter('sku', array('eq' => $limitCollectionSku));


        }

        $productCollection->load();


        $productCollection->setOrder('entity_id', 'DESC');
        $productCollection->setPageSize($this->pageSize);
        return $productCollection;
    }

    protected function _getProductCollection()
    {



        /* Debug Info */
    $limitCollectionSku =  $this->limitCollectionSku;
    $dateFromOverride =  $this->dateFromOverride;
    $dateToOverride =  $this->dateToOverride;


        $ordershippeddays = 30; // number of days you want
        $fromDate         = gmdate("Y-m-d H:i:s", gmmktime(0, 0, 0, gmdate("m"), gmdate("d") - $ordershippeddays, gmdate("Y")));
        //echo "<br/>";
        $toDate           = gmdate("Y-m-d H:i:s", gmmktime(23, 59, 59, gmdate("m"), gmdate("d"), gmdate("Y")));


   if(!empty($dateFromOverride)){

       $fromDate = $dateFromOverride;
   }
        if(!empty($dateToOverride)){

            $toDate = $dateToOverride;
        }
        echo "fromdate--".$fromDate; //from date
        echo "\n";
        echo "toDate--".$toDate; // todate
        echo "\n";




        $ordercollection = $this->_orderCollectionFactory->create()->addAttributeToSelect('*')
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('created_at')
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate));




       // $ordercollection->setPageSize($this->pageSize);
     $ordercollection->setPageSize(500);

        return $ordercollection;
    }
    
 

       protected function _getRowsProduct($productCollection)
    {
    
    
        $cat_lookup[5] = 'mattress';
        $cat_lookup[40] = 'mattress';
        $cat_lookup[41] = 'mattress';
        $cat_lookup[49] = 'mattress';
        $cat_lookup[11] = 'mattress';
        $cat_lookup[58] = 'mattress';
        $cat_lookup[59] = 'mattress';
        $cat_lookup[60] = 'mattress';
        $cat_lookup[61] = 'mattress';
        $cat_lookup[62] = 'mattress';
        $cat_lookup[63] = 'mattress';
        $cat_lookup[64] = 'mattress';

        $cat_lookup[7] = 'bed';
        $cat_lookup[69] = 'bed';
        $cat_lookup[33] = 'bed';
        $cat_lookup[83] = 'bed';
        $cat_lookup[34] = 'bed';
        $cat_lookup[47] = 'bed';
        $cat_lookup[72] = 'bed';
        $cat_lookup[29] = 'bed';
        $cat_lookup[75] = 'bed';
        $cat_lookup[28] = 'bed';
        $cat_lookup[31] = 'bed';
        $cat_lookup[30] = 'bed';
        $cat_lookup[73] = 'bed';
        $cat_lookup[36] = 'bed';
        $cat_lookup[35] = 'bed';
        $cat_lookup[74] = 'bed';
        $cat_lookup[76] = 'bed';
        $cat_lookup[81] = 'bed';
        $cat_lookup[67] = 'bed';
        $cat_lookup[66] = 'bed';
        $cat_lookup[77] = 'bed';
        $cat_lookup[68] = 'bed';
        $cat_lookup[78] = 'bed';
        $cat_lookup[79] = 'bed';
        $cat_lookup[80] = 'bed';
       // $resource = Mage::getResourceSingleton('catalog/product');
        //            echo "ONE\n";
        foreach($productCollection as $product) {
        
     
     
$current_type = '';   
$item_cat = '';

      $despatch_days = $product->getData('dispatch_days');


                 
                            $product_type = $product->getData('product_type');

         
             
                       
                                   if($product_type == 'simple'){
             

                    $parent_main = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($simple_id);




                    if(isset($parent_main[0])){


                        $parent = $this->objectManager->create('Magento\Catalog\Model\Product')->load($parent_main[0]);



                        //this is parent product id..
                        $simple_id = $parent->getId();
                           
                        $cats = $parent->getCategoryIds();
                        foreach ($cats as $category_id) {

                           // $categoryFactory = $this->objectManager->get('\Magento\Catalog\Model\CategoryFactory');

                           // $_cat = $categoryFactory->create()->load($category_id);
                            if(array_key_exists($category_id, $cat_lookup)){
                                $item_cat = $cat_lookup[$category_id];
                            }


                        }
                    }



                }else{
                     $cats = $product->getCategoryIds();
                        foreach ($cats as $category_id) {

                           // $categoryFactory = $this->objectManager->get('\Magento\Catalog\Model\CategoryFactory');

                           // $_cat = $categoryFactory->create()->load($category_id);
                            if(array_key_exists($category_id, $cat_lookup)){
                                $item_cat = $cat_lookup[$category_id];
                            }


                        }
                }



                   
                if(empty($current_type)){
                    if($item_cat == 'bed' || $item_cat == 'mattress'){
                        $current_type = $item_cat;
                    }
                }
                else{
                    if($current_type == 'bed'){
                        if($item_cat == 'mattress'){
                            $current_type = 'both';
                        }
                    }
                    if($current_type == 'mattress'){
                        if($item_cat == 'bed'){
                            $current_type = 'both';
                        }
                    }
                }
         
        
        
                                  // 1288027
                    $insert_query = "INSERT INTO  elevate_product_data (
    `product_id` ,
    `item_cat` ,
    `despatch_days`
    
    )
    VALUES (
    '".$product->getId()."' ,  
    '".$current_type."',
    '".$despatch_days."'
    );   
  
    ";
        try{
                        $write_subq = $this->resourceConnection->getConnection()->query($insert_query);
                    }catch(Exception $e){
                        print_r($e->getData());
                    }
        }

    }


    protected function _getRows2($productCollection)
    {
       // $resource = Mage::getResourceSingleton('catalog/product');
        //            echo "ONE\n";
        foreach($productCollection as $product) {
            //            echo "TWO\n";
            //
            echo "ID:".$product->getId()."\n";
            echo "SKU:".$product->getSku()."\n";
            $resultsbed = $this->resourceConnection->getConnection()->fetchAll("select sales_decay, cat from ".$this->table." WHERE cat='bed' order by sales_decay desc LIMIT 0, 1");

            $cat = '';
            $before_decay = 1;
            foreach($resultsbed as $result){
                //  print_r($result);
                $before_decay = $result['sales_decay'];
                $cat = $result['cat'];
            }

            $resultsmattress = $this->resourceConnection->getConnection()->fetchAll("select sales_decay, cat from ".$this->table." WHERE cat='mattress' order by sales_decay desc LIMIT 0, 1");

            foreach($resultsmattress as $result){
                $before_decay = $result['sales_decay'];
                $cat = $result['cat'];
            }
            //   echo "\n\nCAT:$cat\n\n";


            $now = time(); // or your date as well
            $your_date = strtotime($product['created_at']);
            $datediff = $now - $your_date;
            $days = floor($datediff / (60 * 60 * 24));

            echo "\nDAYS:".$days."\n";


            $time_interval = floor($days / 30 * 100);
            $percentage_decay = 0.5;
            if($time_interval < 30){

                echo "\nTIME:".$time_interval."\n";


                $new_in_rank = $before_decay * (pow((1-(80/100)), $time_interval/100));
                echo "\nNEWINRANK:".$new_in_rank."\n";
            }
            else{
                $new_in_rank = 0;
            }
            $sku = $product->getSku();

            if(array_key_exists($sku, $this->boostArray)){
                $new_in_rank = $this->boostArray[$sku];
                echo "sku:".$product->getSku()."|$new_in_rank\n";
            }




            $prodid = $product->getId();

            $results = $this->resourceConnection->getConnection()->fetchAll("select * from ".$this->table." where product_id = '$prodid'");

            /**
             * Print out the results
             */

            $sd = 0;
            $checked = false;
            foreach($results as $result){
                $checked = true;
                if(is_numeric($result["sales_decay"])){
                    $sd = $result["sales_decay"];
                }
                echo "\nCOMPARE:: $new_in_rank :: $sd \n";
                if($new_in_rank > $sd){
                    echo "IS OVER";
                    $sd = $new_in_rank;
                }


            }
            if(!$checked){
                if($new_in_rank > $sd){
                    echo "IS OVER";
                    $sd = $new_in_rank;
                }

            }

            if(empty($sd) || $sd < 0){
                $sd = 0;
            }

            echo "\nRANK:".$sd."\n";
            $productIds = [$product->getId()];
            $attributesData = ['bestseller' => $sd];
            $storeId = 0;
            $productMassAction = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Catalog\Model\Product\Action');
            $productMassAction->updateAttributes($productIds, $attributesData, $storeId);

          //  $product->setData('bestseller', $sd);
          //  $product->saveAttribute($product, 'bestseller');
            //  exit;
        }

    }
    protected function _getRows( $ordercollection)
    {


        $cat_lookup[5] = 'mattress';
        $cat_lookup[40] = 'mattress';
        $cat_lookup[41] = 'mattress';
        $cat_lookup[49] = 'mattress';
        $cat_lookup[11] = 'mattress';
        $cat_lookup[58] = 'mattress';
        $cat_lookup[59] = 'mattress';
        $cat_lookup[60] = 'mattress';
        $cat_lookup[61] = 'mattress';
        $cat_lookup[62] = 'mattress';
        $cat_lookup[63] = 'mattress';
        $cat_lookup[64] = 'mattress';

        $cat_lookup[7] = 'bed';
        $cat_lookup[69] = 'bed';
        $cat_lookup[33] = 'bed';
        $cat_lookup[83] = 'bed';
        $cat_lookup[34] = 'bed';
        $cat_lookup[47] = 'bed';
        $cat_lookup[72] = 'bed';
        $cat_lookup[29] = 'bed';
        $cat_lookup[75] = 'bed';
        $cat_lookup[28] = 'bed';
        $cat_lookup[31] = 'bed';
        $cat_lookup[30] = 'bed';
        $cat_lookup[73] = 'bed';
        $cat_lookup[36] = 'bed';
        $cat_lookup[35] = 'bed';
        $cat_lookup[74] = 'bed';
        $cat_lookup[76] = 'bed';
        $cat_lookup[81] = 'bed';
        $cat_lookup[67] = 'bed';
        $cat_lookup[66] = 'bed';
        $cat_lookup[77] = 'bed';
        $cat_lookup[68] = 'bed';
        $cat_lookup[78] = 'bed';
        $cat_lookup[79] = 'bed';
        $cat_lookup[80] = 'bed';

        $order_loop_data = array();

                $initialMem = "STAR COL MEM".memory_get_usage();
        foreach ($ordercollection as $orders) {
          
          
          
          
          
                                                            
            $eid = $orders->getEntityId();
            //   $eid = '29131';
         //   $order           = $this->orderRepository->get($eid);
            $customerorderid = $orders->getIncrementId();
            $customeremailid = $orders->getCustomerEmail();
            $customername    = $orders->getCustomerFirstname();

            unset($order_loop_data);
            $order_loop_data = [];
            foreach ($orders->getAllItems() as $item) {

                $item_cat = '';

                $simple_id = $item->getData('product_id');
                $product_type = $item->getData('product_type');



                try{
            //   $simple_lookup = $this->objectManager->create('Magento\Catalog\Model\Product')->load($simple_id);

                }
                catch(Exception $e){
                    print_r($e->getMessage());

                }

             $despatch_days = '';
          //  $despatch_days = $simple_lookup->getData('dispatch_days');

                          

                if($product_type == 'simsple'){
             

                    $parent_main = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($simple_id);




                    if(isset($parent_main[0])){


                        $parent = $this->objectManager->create('Magento\Catalog\Model\Product')->load($parent_main[0]);



                        //this is parent product id..
                        $simple_id = $parent->getId();
                           
                        $cats = $parent->getCategoryIds();
                        foreach ($cats as $category_id) {

                           // $categoryFactory = $this->objectManager->get('\Magento\Catalog\Model\CategoryFactory');

                           // $_cat = $categoryFactory->create()->load($category_id);
                            if(array_key_exists($category_id, $cat_lookup)){
                                $item_cat = $cat_lookup[$category_id];
                            }


                        }
                    }



                }
$current_type = '';          
                if(empty($current_type)){
                    if($item_cat == 'bed' || $item_cat == 'mattress'){
                        $current_type = $item_cat;
                    }
                }
                else{
                    if($current_type == 'bed'){
                        if($item_cat == 'mattress'){
                            $current_type = 'both';
                        }
                    }
                    if($current_type == 'mattress'){
                        if($item_cat == 'bed'){
                            $current_type = 'both';
                        }
                    }
                }
         

               // $order_loop_data[''.$customerorderid.'']['order_type'] = $current_type;
               // $order_loop_data[''.$customerorderid.'']['items'][] =
              //      array(
              // //         'product_id' => $simple_id,
               //         'product_type' => $product_type,
              //          'item_cat' => $item_cat,
              //          'created_at' => $item->getData('created_at'),
              //          'despatch_days' => $despatch_days
              //      );


                              // 1288027
                    $insert_query = "INSERT INTO  elevate_order_data (
    `item_id` ,
    `order_type` ,
    `product_id` ,
    `product_type` ,
    `item_cat`,
    `created_at`,
    `despatch_days`
    
    )
    VALUES (
    '',
    '".$current_type."' , 
    '".$simple_id."' , 
    '".$product_type."', 
    '".$item_cat."',
    '".$item->getData('created_at')."', 
    '".$despatch_days."'
    );   
  
    ";
                    //echo "\n";
                    //echo $insert_query;
                    // echo "\n-----"; echo "\n";
                    try{
                        $write_subq = $this->resourceConnection->getConnection()->query($insert_query);
                    }catch(Exception $e){
                        print_r($e->getData());
                    }
            }




            //exit;
        }
  }


    public function processData(){
    
                          
            
             $order_loop_data = $this->resourceConnection->getConnection()->fetchAll("select eod.created_at, eod.product_id, epd.item_cat, epd.despatch_days from elevate_order_data eod left join elevate_product_data epd on epd.product_id = eod.product_id");

       
        


                foreach ($order_loop_data as $the_items) {
                    $despatch_days = $the_items['despatch_days'];
                    $now = time(); // or your date as well
                    $your_date = strtotime($the_items['created_at']);
                    $datediff = $now - $your_date;
                    $days = floor($datediff / (60 * 60 * 24));
                    //echo "<br />TIME:".$days."<br />";


                    $rank_val = 0.5;
                    $amount = 1;
                   // if($order_loop_vals['order_type'] == 'both' && $the_items['item_cat'] == 'mattress'){
                 //       $rank = 0.9;
                //        $amount = 0.1;
                //    }

                    if($despatch_days > 10){
                        $amount = 0.1;
                    }
                    //echo "<br />AMOUNT:".$amount."<br />";



                    //$despatch_days


                    $time_interval = floor($days / 30 * 100);
                    //$rank = (pow($amount*($amount - 0.5), $time_interval/100));
                    $rank = round($amount * (pow((1-(80/100)), $time_interval/100)), 5);
                    //echo "RANK:::".$rank."<br />";



                    // 1288027
                    $insert_query = "INSERT INTO  `".$this->table."` (
                     `entity_id` ,
    `customer_id` ,
    `product_id` ,
    `impression` ,
    `sales` ,
    `sales_decay` ,
    `boost` ,
    `cat` ,
    `despatch_days`
    )
    VALUES (
    99".$the_items['product_id']." ,  
    '99' , 
    '".$the_items['product_id']."' , 
    '', 
    '1',
    '$rank', 
    '', 
    '".$the_items['item_cat']."' ,
    '' 
    )
    ON DUPLICATE KEY UPDATE sales = sales + 1, sales_decay = sales_decay + $rank;   
    ;
    ";    
    
                    //echo "\n";
                    //echo $insert_query;
                    // echo "\n-----"; echo "\n";
                    try{
                        $write_subq = $this->resourceConnection->getConnection()->query($insert_query);
                    }catch(Exception $e){
                        print_r($e->getData());
                    }
                    //   if($item->getId() == '57101'){
                    // echo $customerorderid.",".$item->getId().",";
                    //    echo $item->getCreatedAt()."<br />";
                    //  }


           
            }
    }

  
    private function sendEmail($message){
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
    }
}
