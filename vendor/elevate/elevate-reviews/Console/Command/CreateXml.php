<?php

namespace Elevate\Reviews\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateXml extends Command
{
    private $objectManager;
    protected $log_file = 'sale_flag.log';
    protected $pageSize = 1000;
    protected $fileDelimiter = ',';
    protected $xml;
    protected $fileName;
    protected $resourceConnection;
    protected $read;
    protected $write;
    protected $table;
    protected $fileHandle;
    protected $boostArray = [];
    protected $_orderCollectionFactory;
    protected $_shipmentCollectionFactory;
    protected $orderRepository;
    protected $orderItemRepository;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Catalog\Model\Product $productrepository,
        \Magento\Framework\App\State $state,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemRepository
    )
    {
        $this->objectManager = $objectmanager;
        $this->productRepository = $productrepository;
        $this->state = $state;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_shipmentCollectionFactory = $shipmentCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('elevate:reviews:createxml')->setDescription('create the xml');
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $xml = new \DOMDocument("1.0");
        $root = $xml->createElement("items");
        $xml->appendChild($root);

        /*-----for date selection----*/
        //$frdate = date('Y-m-d'). "00:00" ;

        //10.6. and 20.8.17

        $frdate = date('Y-m-d',strtotime('2 month ago'))."00:00";//date('Y-m-d'). "00:00" ;
        //		$frdate = date('Y-m-d',strtotime('14 weeks ago'))."00:00";//date('Y-m-d'). "00:00" ;
        $todate = date('Y-m-d'). "23:59" ;
        $fromDate = date('Y-m-d H:i:s', strtotime($frdate));
        $toDate = date('Y-m-d H:i:s', strtotime($todate));

        //manual run

        // $toDate = '2017-07-10 23:59:59';

        // Create order collection object



        $collection = $this->_shipmentCollectionFactory->create()->addAttributeToSelect('*');


        // Apply date filter. This would be 1 day in production but using this range as a test.
        $collection->addFieldToFilter('created_at', array('from' => $fromDate, 'to' => $todate));
        //$collection->addAttributeToFilter('status','complete');
        //$collection->addAttributeToFilter('store_id', array('eq' => 4));
        $debug = false;
        if($debug){
            $collection = Mage::getModel('sales/order')->getCollection();
            $collection->addAttributeToFilter('increment_id','400094983');

        }

        $xml->formatOutput = true;
        // Iterate it for displaying results

        foreach ($collection as $order) {

            $order_id = $order->getData('order_id');

         //   $order = Mage::getModel('sales/order')->load($order_id);
            #get all items
            $items = $order->getAllItems();
            $itemcount= count($items);

            $data = array();
            $i=0;
            #loop for all order items
            $names   = $xml->createElement("name");
            $nameTexts = $xml->createTextNode($order->getCustomerName());
            $names->appendChild($nameTexts);

            $emails   = $xml->createElement("email");
            $emailTexts = $xml->createTextNode($order->getCustomerEmail());
            $emails->appendChild($emailTexts);

            $dates   = $xml->createElement("date");
            $dateTexts = $xml->createTextNode($order->getCreatedAt());
            $dates->appendChild($dateTexts);

            $merchant_identifier = $xml->createElement("merchant_identifier");
            $merchant_identifierTexts = $xml->createTextNode("posturite-ltd");
            $merchant_identifier->appendChild($merchant_identifierTexts);

            $logons   = $xml->createElement("logon");
            $logonTexts = $xml->createTextNode("www.posturite.co.uk/UK");
            $logons->appendChild($logonTexts);

            $order_refs   = $xml->createElement("order_ref");
            $order_refTexts = $xml->createTextNode($order->getId());
            $order_refs->appendChild($order_refTexts);

            $tags   = $xml->createElement("tags");
            $tags_refTexts = $xml->createTextNode("campaign=service");
            $tags->appendChild($tags_refTexts);

            $product_search_codes   = $xml->createElement("product_search_code");
            $product_search_codeTexts = $xml->createTextNode('Customer Service');
            $product_search_codes->appendChild($product_search_codeTexts);

            $description   = $xml->createElement("description");
            $descriptionTexts = $xml->createTextNode('Customer Service');
            $description->appendChild($descriptionTexts);


            $item = $xml->createElement("item");
            $item->appendChild($merchant_identifier);
            $item->appendChild($names);
            $item->appendChild($emails);
            $item->appendChild($dates);
            $item->appendChild($description);
            $item->appendChild($tags);
            $item->appendChild($product_search_codes);
            //$item->appendChild($logons);
            $item->appendChild($order_refs);



            $root->appendChild($item);


            $stock_qty = 0;
            foreach ($items as $itemId => $item)
            {

                $lt = 0 ;
                $mainItemOptions = $this->orderItemRepository->create()->addFieldToFilter('parent_item_id', $item->getItemId());
                foreach($mainItemOptions as $ItemOption){

                    $optionproduct = $this->productRepository->load($ItemOption->getProductId());
                    $stock_qty = $optionproduct->getStockItem()->getQty();

                    $option_leadtime[] = $optionproduct->getLeadTime() ;

                    if($optionproduct->getLeadTime() > $lt){
                        if($optionproduct->getLeadTime() > 0 ){
                            $lt = $optionproduct->getLeadTime();

                        }else{
                            $lt = 28;
                        }

                    }


                }


                $leadtime="campaign=6weeks";

                if($stock_qty > 0){
                    if($lt <= 4){
                        $leadtime="campaign=1week";
                    }elseif($lt >= 5 and $lt <= 15){
                        $leadtime="campaign=3weeks";
                    }elseif($lt >= 16){
                        $leadtime="campaign=6weeks";
                    }
                }




                if($item->getParentItemId() == NULL or $item->getParentItemId() == ""){


                    $merchant_identifier= $xml->createElement("merchant_identifier");
                    $merchant_identifierTexts = $xml->createTextNode("posturite-ltd");
                    $merchant_identifier->appendChild($merchant_identifierTexts);

                    $name   = $xml->createElement("name");
                    $nameText = $xml->createTextNode($order->getCustomerName());
                    $name->appendChild($nameText);

                    $email   = $xml->createElement("email");
                    $emailText = $xml->createTextNode($order->getCustomerEmail());
                    $email->appendChild($emailText);

                    $date   = $xml->createElement("date");
                    $dateText = $xml->createTextNode($order->getCreatedAt());
                    $date->appendChild($dateText);

                    $logon   = $xml->createElement("logon");
                    $logonText = $xml->createTextNode("www.posturite.co.uk/UK");
                    $logon->appendChild($logonText);

                    $order_ref   = $xml->createElement("order_ref");
                    $order_refText = $xml->createTextNode($order->getId());
                    $order_ref->appendChild($order_refText);

                    $product= $this->productRepository->load($item->getProductId());
                    $productType=$product->getTypeID();
                    $data[$i]['producttype'] = $productType;

                    $product_link   = $xml->createElement("product_link");
                    $plink = str_replace('/feefoxml.php','',$product->getProductUrl()) ;
                    $product_linkText = $xml->createTextNode($plink);
                    $product_link->appendChild($product_linkText);

                    $product_search_code   = $xml->createElement("product_search_code");
                    $description   = $xml->createElement("description");
                    $tag  = $xml->createElement("tags");

                    if($productType == 'bundleconfig'){

                        $Psku = explode('-',$item->getSku());
                        $psc = $Psku[0] ;
                        $desc = $item->getName();

                    }else{

                      //  $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($item->getProductId());

if($productType == 'simple'){
                        $product = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($item->getProductId());
                        if(isset($product[0])){
                            //this is parent product id..
                            $data[$i]['ConfigurableParent'] = $product[0] ;
                        }
}

                        $go = false;
if(isset($product[0])){
    if($product[0] != NULL or $product[0] != ""){

        $go = true;
    }

}

                        if($go){

                            $Configproduct= $this->productRepository->load($product[0]);
                            $psc = $Configproduct->getSku();
                            $desc = $Configproduct->getName();

                        }else{

                            $psc = $item->getSku();
                            $desc = $item->getName() ;
                            $product = $this->productRepository->load($item->getProductId());
                            $lt =  $product->getLeadTime();
                            $stock_qty = $product->getQty();
                            if($stock_qty > 0) {
                                if($lt <= 4 ){
                                    $leadtime="campaign=1week";
                                }elseif($lt >= 5 and $lt <= 15){
                                    $leadtime="campaign=3weeks";
                                }elseif($lt >= 16 ){
                                    $leadtime="campaign=6weeks";
                                }
                            }else{
                                $leadtime="campaign=6weeks";
                            }

                        }
                    }


                    $tagText = $xml->createTextNode($leadtime);
                    $tag->appendChild($tagText);

                    $product_search_codeText = $xml->createTextNode($psc);
                    $product_search_code->appendChild($product_search_codeText);

                    $descriptionText = $xml->createTextNode($desc);
                    $description->appendChild($descriptionText);


                    $item = $xml->createElement("item");

                    $item->appendChild($merchant_identifier);
                    $item->appendChild($name);
                    $item->appendChild($email);
                    $item->appendChild($date);
                    $item->appendChild($description);
                    $item->appendChild($tag);
                    //$item->appendChild($logon);
                    $item->appendChild($product_search_code);
                    $item->appendChild($order_ref);
                    $item->appendChild($product_link);

                    $root->appendChild($item);

                }
                $i++;
            }

        }
        exit;
        //$xml->saveXML();
        echo "<xmp>". $xml->saveXML() ."</xmp>";
        echo "/microcloud/domains/postur/domains/posturite.co.uk/feeds/".$this->fileName;

        return $xml->save("/microcloud/domains/postur/domains/posturite.co.uk/http/feeds/".$this->fileName);
    }

    public function sendXML($localFileName = null, $remoteFile = null)
    {

        exit;
        //Eleavte John Commented out for testing
        //      $localFileName = 'feefo.xml';
        //   $remoteFile = 'DONOTPROCESS.txt';

        // $ftpHost = 'ftp2.feefo.com';
        // $ftpUser = 'posturite-offline';
        // $ftpPw = 'NDTWsuyDYRM';



        $connectionDetails = array();



        //uses phpseclib bundled with magento
        //Varien_Io_Sftp(); //standard model does not pass mode via write!!
        $sftpIoObject = Mage::getModel('feeforeview/Sftp');

        $connectionDetails['host'] = $ftpHost;
        $connectionDetails['username'] = $ftpUser;
        $connectionDetails['password'] = $ftpPw;
        $connect = $sftpIoObject->open($connectionDetails);
        if (!$connect) {


        }


        $upload = $sftpIoObject->write($remoteFile, $localFileName, NET_SFTP_LOCAL_FILE);
        $sftpIoObject->close();
        echo "send";
        exit;

        return $upload;
    }
    /**
     * {@inheritdoc}
     */
    protected function executeold(InputInterface $input, OutputInterface $output)
    {

        echo "RUN";
        exit;

        $gen_run = true;
        $sales_run = false;

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

        //truncate existing rules
        if($sales_run){


            $truncate_q = "DELETE FROM  ".$this->table.";";
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

                // do things

                $productsCollection->clear();
            }

        }


        //assign the bestsellers
        if($gen_run){
            echo date('c');

            $initialMem = memory_get_usage();



            $productsCollection2 = $this->_getProductCollection2();

            $pages = $productsCollection2->getLastPageNumber();
            $currentPage = 1;
            echo "\n Pages: " . $pages . "\n";


            for ($currentPage = 1; $currentPage <= $pages; $currentPage++) {
                $productsCollection2->setCurPage($currentPage);

                $rows2 = $this->_getRows2($productsCollection2);


                echo date('c') . ': ' . "Page: " . $currentPage . "\n";

                // do things

                $productsCollection2->clear();
            }



        }

        echo "END";
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


    protected function _getProductCollection2()
    {



        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection->addAttributeToSelect('*');
        //$productCollection->setStoreId(0);
        //$productCollection->addAttributeToFilter('price', array('eq' => '0'));
        $productCollection->load();


        $productCollection->setOrder('entity_id', 'DESC');
        $productCollection->setPageSize($this->pageSize);
        return $productCollection;
    }

    protected function _getProductCollection()
    {





        $ordershippeddays = 60; // number of days you want
        $fromDate         = gmdate("Y-m-d H:i:s", gmmktime(0, 0, 0, gmdate("m"), gmdate("d") - $ordershippeddays, gmdate("Y")));
        //echo "<br/>";
        $toDate           = gmdate("Y-m-d H:i:s", gmmktime(23, 59, 59, gmdate("m"), gmdate("d"), gmdate("Y")));

        echo "fromdate--".$fromDate; //from date
        echo "<br>";
        echo "toDate--".$toDate; // todate
        echo "<br>";



        $ordercollection = $this->_orderCollectionFactory->create()->addAttributeToSelect('*')
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('created_at')
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate));




        $ordercollection->setPageSize($this->pageSize);
        // $productCollection->setPageSize(10);

        return $ordercollection;
    }





    protected function _getRows2($productCollection)
    {
       // $resource = Mage::getResourceSingleton('catalog/product');
        //            echo "ONE\n";
        foreach($productCollection as $product) {
            //            echo "TWO\n";
            //

            echo "sku:".$product->getSku();
            $resultsbed = $this->resourceConnection->getConnection()->fetchAll("select sales_decay, cat from ".$this->table." WHERE cat='bed' order by sales_decay desc LIMIT 0, 1");

            $cat = '';
            $before_decay = 1;
            foreach($resultsbed as $result){
                //  print_r($result);
                $before_decay = $result['sales_decay'];
                $cat = $result['cat'];
            }

            $resultsmattress = $this->resourceConnection->getConnection()->fetchAll("select sales_decay from ".$this->table." WHERE cat='mattress' order by sales_decay desc LIMIT 0, 1");

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


        foreach ($ordercollection as $orders) {

            $eid = $orders->getEntityId();
            //   $eid = '29131';
            $order           = $this->orderRepository->get($eid);
            $customerorderid = $order->getIncrementId();
            $customeremailid = $order->getCustomerEmail();
            $customername    = $order->getCustomerFirstname();

            unset($order_loop_data);
            $order_loop_data = [];
            foreach ($order->getAllItems() as $item) {

                $item_cat = '';

                $simple_id = $item->getData('product_id');
                $product_type = $item->getData('product_type');


                try{
                    $simple_lookup = $this->objectManager->create('Magento\Catalog\Model\Product')->load($simple_id);

                }
                catch(Exception $e){
                    print_r($e->getMessage());

                }

echo "CHECKER";
                $despatch_days = $simple_lookup->getData('dispatch_days');


                if($product_type == 'simple'){


                    $parent = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($simple_id);
                    if(isset($product[0])){
                        //this is parent product id..
                        $simple_id = $parent->getId();

                        $cats = $parent->getCategoryIds();
                        foreach ($cats as $category_id) {

                            $categoryFactory = $this->objectManager->get('\Magento\Catalog\Model\CategoryFactory');

                            $_cat = $categoryFactory->create()->load($category_id);
                            if(array_key_exists($_cat->getId(), $cat_lookup)){
                                $item_cat = $cat_lookup[$_cat->getId()];
                            }


                        }
                    }



                }

if(isset($order_loop_data[''.$customerorderid.''])){
                $current_type = $order_loop_data[''.$customerorderid.'']['order_type'];
}
else{
    $current_type = '';
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


                $order_loop_data[''.$customerorderid.'']['order_type'] = $current_type;
                $order_loop_data[''.$customerorderid.'']['items'][] =
                    array(
                        'product_id' => $simple_id,
                        'product_type' => $product_type,
                        'item_cat' => $item_cat,
                        'created_at' => $item->getData('created_at'),
                        'despatch_days' => $despatch_days
                    );



            }



            foreach ($order_loop_data as $order_loop_vals) {


                foreach ($order_loop_vals['items'] as $the_items) {
                    $despatch_days = $the_items['despatch_days'];
                    $now = time(); // or your date as well
                    $your_date = strtotime($the_items['created_at']);
                    $datediff = $now - $your_date;
                    $days = floor($datediff / (60 * 60 * 24));
                    //echo "<br />TIME:".$days."<br />";


                    $rank_val = 0.5;
                    $amount = 1;
                    if($order_loop_vals['order_type'] == 'both' && $the_items['item_cat'] == 'mattress'){
                        $rank = 0.9;
                        $amount = 0.1;
                    }

                    if($despatch_days > 10){
                        $amount = 0.1;
                    }
                    //echo "<br />AMOUNT:".$amount."<br />";



                    //$despatch_days


                    $time_interval = floor($days / 30 * 100);
                    //$rank = (pow($amount*($amount - 0.5), $time_interval/100));
                    $rank = $amount * (pow((1-(80/100)), $time_interval/100));
                    //echo "RANK:::".$rank."<br />";



                    // 1288027
                    $insert_query = "INSERT INTO  `".$this->table."` (
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


            //exit;
        }





    }
    private function sendEmail($message){
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
    }
}
