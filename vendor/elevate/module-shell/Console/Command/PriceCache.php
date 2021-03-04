<?php

namespace Elevate\Shell\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PriceCache extends Command
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
        $this->setName('elevate:shell:pricecache')->setDescription('run price cache');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
                                 $this->email_data['total_items'] = 0;
                                    
            echo date('c');

            $initialMem = memory_get_usage();
            //$outputFilename = Mage::getBaseDir('base') . '/' . $this->outputFile;


            $productsCollection = $this->_getProductCollection();

            $pages = $productsCollection->getLastPageNumber();
            $currentPage = 1;
            echo "\n Pages: " . $pages . "\n";

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
    //  $productCollection->addAttributeToFilter('type_id', array('in' => array('configurable', 'bundle')));
        
        $productCollection->addAttributeToSelect('sku');
    $productCollection->addAttributeToSelect('type_id');
    $productCollection->addAttributeToSelect('dispatch_days');
    $productCollection->addAttributeToSelect('associated_products');
    $productCollection->addAttributeToSelect('name');

    
    
   // $productCollection->addAttributeToFilter('exclude_from_google_feed', 0);

    // $productCollection->addAttributeToFilter('status', array('in' => $status));
    //$productCollection->addAttributeToFilter('visibility', array('nin' => $visibility));
    $productCollection->addAttributeToSelect('visibility');
      $productCollection->addAttributeToSelect('date_next_available');

      $productCollection->addAttributeToSelect('handling_time');

    $productCollection->addAttributeToSelect('attribute_set');
    $productCollection->addAttributeToSelect('category_ids');
    $productCollection->addAttributeToSelect('description');

    $productCollection->addAttributeToSelect('manufacturer');
    $productCollection->addAttributeToSelect('size');

    $productCollection->addAttributeToSelect('color_description');
    $productCollection->addAttributeToSelect('rollover_image');
    $productCollection->addAttributeToSelect('swatch_image');

    $productCollection->addAttributeToSelect('product_cat');
    $productCollection->addAttributeToSelect('product_subcat');
    $productCollection->addAttributeToSelect('manufacturer');
    $productCollection->addAttributeToSelect('price');
    $productCollection->addAttributeToSelect('image');
    $productCollection->addAttributeToSelect('google_image');
    //     $productCollection->joinAttribute('image', 'catalog_product/image', 'entity_id', null, 'left');
    $productCollection->addAttributeToSelect('qty');
    $productCollection->addAttributeToSelect('short_description');
    $productCollection->addAttributeToSelect('fabric');
    $productCollection->addAttributeToSelect('barcodes');
    $productCollection->addAttributeToSelect('special_from_date');
    $productCollection->addAttributeToSelect('special_price');
    $productCollection->addAttributeToSelect('special_to_date');
    $productCollection->addAttributeToSelect('url');
    $productCollection->addAttributeToSelect('url_key');
    $productCollection->addAttributeToSelect('url_path');
    $productCollection->addAttributeToSelect('identifier_exists');
    $productCollection->addAttributeToSelect('google_custom_label_0');
    $productCollection->addAttributeToSelect('google_custom_label_1');
    $productCollection->addAttributeToSelect('google_custom_label_2');
    $productCollection->addAttributeToSelect('google_custom_label_3');
    $productCollection->addAttributeToSelect('google_custom_label_4');
    $productCollection->addAttributeToSelect('google_mpn');
    $productCollection->addAttributeToSelect('google_title');
    $productCollection->addAttributeToSelect('google_size');
    $productCollection->addAttributeToSelect('google_age_group');
    $productCollection->addAttributeToSelect('google_material');
    $productCollection->addAttributeToSelect('google_brand');
    $productCollection->addAttributeToSelect('google_colour');
    $productCollection->addAttributeToSelect('google_promotion_id');
    $productCollection->addUrlRewrite();
                           
                            
                            // Sample sku array
$productCollection->addAttributeToFilter('sku', array('in' => array('RHLOGIC200WEB')));
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



            
                
    $outputFilename = $this->dir->getPath('pub') . '/' . $this->outputFile;
    unset($rows);
    unset($row);

    $rows = [];
     
    foreach ($productcollection as $products) {
                 

    

             
      if (array_key_exists($this->_getId($products), $this->output_skus)) {
        //  continue;
      } else {
        $this->output_skus[$this->_getId($products)] = $this->_getId($products);
      }

      $image_array = $this->_getAdditionalImageLink($products);
            
      $image_link = $image_array['main'];
      $additional_images = $image_array['additional'];

      $mapped_cat = $this->_getMappedCategory($products);

      if ($mapped_cat == '') {
        //  continue;
      }

      //  echo " Prod start: ".round((memory_get_usage() / 1024 / 1024), 2) . "MB\n";
               
      if (!is_null($image_link)) {
  
      
   
        $this->email_data['total_items']++;
   //     echo $products->getTypeId();
        $row = array(

          'id'                      => $this->_getId($products),
          // An identifier of the item
          'title'                   => $this->_getTitle($products),
          // Title of the item
          'description'             => $this->_getDescription($products),
          // Description of the item
          'google_product_category' => $this->_getMappedCategory($products),
          // Google's category of the item
          'product_type'            => $this->_getProductType($products),
          // Your category of the item
          'link'                    => $this->_getLink($products),
          // URL directly linking to your item's page on your website
          'image_link'              => $image_link,
          // URL of an image of the item
          'additional_image_link'   => $additional_images,
          // Additional URLs of images of the item
          'condition'               => $this->_getCondition($products),
          // Condition or state of the item ('new', 'used', 'refurbished')

          'availability' => $this->_getAvailability($products),
          // Availability status of the item ('in stock', 'available for order', 'out of stock', 'preorder')
          'price'        => $this->_getPrice($products->getData('price')),
          // Price of the item
          'sale_price'   => $this->_getSalePrice($products),
          // Advertised sale price of the item

          'mpn'   => $this->_getMpn($products),
          // Manufacturer Part Number (MPN) of the item
          'brand' => $this->_getBrand($products),
          // Brand of the item
          'gtin'  => $products->getData('barcodes'),
          // Manufacturer Part Number (MPN) of the item
          'age_group' => $this->_getAgeGroup($products),
          'material'            => $products->getData('google_material'),          	
          'colour'            => $products->getData('google_colour'),
          // Colour of the item                       
          'size'              => $products->getData('google_size'),
          // Size of the item
          'item_group_id'     => $this->_getItemGroupId($products),
          // Shared identifier for all variants of the same product
          'identifier_exists' => $this->_getIdentifierExists($products),
          'custom_label_0'    => $products->getData('google_custom_label_0'),
          'custom_label_1'    => $products->getData('google_custom_label_1'),
          'custom_label_2'    => $products->getData('google_custom_label_2'),
          'custom_label_3'    => $products->getData('google_custom_label_3'),
          'custom_label_4'    => $products->getData('google_custom_label_4'),
          'promotion_id'      => $products->getData('google_promotion_id'),

          //   'dispatch_days' => $products->getData('dispatch_days'), // Size of the item

        );
        echo "C2";
        $row = $this->_sanitiseData($row);
          
          
        $products->setPrice($this->_getPrice($products->getData('price')));
        $products->save(); 
             
       print_r($row);

        $rows[] = $row;
        $this->_writeFile($outputFilename, $rows);
        // print_r($row);
        // exit;
        unset($rows);

      } else {
        echo "missing image" . $this->_getItemGroupId($products);
        Mage::log($product_data['id'] . ' missing image', Zend_Log::NOTICE, self::CUSTOM_LOG_FILE);
        $this->_count_missing++;
      }
      echo "YES";
      if ($this->_getId($products) == '1287960') {

        print_r($products->getData());
      }

      //  echo " Prod end: ".round((memory_get_usage() / 1024 / 1024), 2) . "MB\n";
      $products->clearInstance();
    }

    return true;
  }

  /**
   * get title
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getTitle( $product = NULL) {
    $title = NULL;
    $google_title = trim($product->getGoogleTitle());
    if(!empty($google_title)){
      $title = $google_title;
    }else{  
    if ($product->getName()) {
      $title = trim($product->getName());
    }
    }
    return $title;
  }

  protected function _getIdentifierExists( $product = NULL) {

    $gmpn = $product->getData('barcodes');

    if (!empty($gmpn)) {
      return "true";
    } else {
      return "false";
    }
  }

  protected function _getMappedCategory( $product = NULL) {

    $category_mapping[2] = '436';
    $category_mapping[10] = '4171';
    $category_mapping[17] = '4452';
    $category_mapping[18] = '2927';
    $category_mapping[19] = '2541';
    $category_mapping[20] = '2314';
    $category_mapping[12] = '505764';
    $category_mapping[5] = '2696';
    $category_mapping[11] = '2696';
    $category_mapping[40] = '2696';
    $category_mapping[41] = '2696';
    $category_mapping[42] = '2696';
    $category_mapping[48] = '2696';
    $category_mapping[49] = '2696';
    $category_mapping[50] = '2696';
    $category_mapping[57] = '2696';
    $category_mapping[58] = '2696';
    $category_mapping[59] = '2696';
    $category_mapping[60] = '2696';
    $category_mapping[61] = '2696';
    $category_mapping[62] = '2696';
    $category_mapping[63] = '2696';
    $category_mapping[64] = '2696';
    $category_mapping[65] = '2696';
    $category_mapping[6] = '505764';
    $category_mapping[7] = '505764';
    $category_mapping[28] = '505764';
    $category_mapping[29] = '505764';
    $category_mapping[30] = '505764';
    $category_mapping[31] = '505764';
    $category_mapping[32] = '505764';
    $category_mapping[33] = '505764';
    $category_mapping[34] = '505764';
    $category_mapping[36] = '505764';
    $category_mapping[47] = '505764';
    $category_mapping[66] = '505764';
    $category_mapping[67] = '505764';
    $category_mapping[68] = '505764';
    $category_mapping[69] = '505764';
    $category_mapping[70] = '505764';
    $category_mapping[71] = '505764';
    $category_mapping[72] = '505764';
    $category_mapping[73] = '505764';
    $category_mapping[74] = '505764';
    $category_mapping[75] = '505764';
    $category_mapping[76] = '505764';
    $category_mapping[77] = '505764';
    $category_mapping[78] = '505764';
    $category_mapping[79] = '505764';
    $category_mapping[80] = '505764';
    $category_mapping[81] = '505764';
    $category_mapping[82] = '505764';
    $category_mapping[83] = '505764';
    $category_mapping[8] = '436';
    $category_mapping[23] = '436';
    $category_mapping[24] = '436';
    $category_mapping[25] = '4191';
    $category_mapping[26] = '460';
    $category_mapping[27] = '436';
    $category_mapping[39] = '438';
    $category_mapping[44] = '4063';
    $category_mapping[45] = '436';
    $category_mapping[46] = '6360';
    $category_mapping[85] = '436';
    $category_mapping[86] = '436';
    $category_mapping[87] = '436';
    $category_mapping[91] = '465';
    $category_mapping[92] = '436';
    $category_mapping[9] = '451';
    $category_mapping[13] = '451';
    $category_mapping[14] = '451';
    $category_mapping[15] = '451';
    $category_mapping[16] = '451';

    $product_categories = array();
    foreach ($product->getCategoryIds() as $_categoryId) {
      if (isset($category_mapping[$_categoryId]) && $category_mapping[$_categoryId] != '') {
        $product_categories[] = $category_mapping[$_categoryId];
        break;
      }
    }
    $product_categories = array_unique($product_categories);
    $product_categories = array_slice($product_categories, 0, self::PRODUCT_TYPE_MAX);
    $product_type = implode(",", $product_categories);
    if ($product_type == '') {
      $product_type = '436';
    }

    return $product_type;

  }

  /**
   * get availability
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getAvailability( $product = NULL) {
    // 'in stock', 'available for order', 'out of stock', 'preorder'
    $stockItem = $this->stockRegistry->getStockItem($product->getId());
                                           
    if($stockItem){
    if ($stockItem->getIsInStock()) {
      $availability = 'in stock';
    } else {
      $availability = 'out of stock';
    }
      }
      else{
            $availability = 'out of stock';
      }
      
      echo $availability."\n";
    return $availability;
  }

  /**
   * get condition
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getCondition( ) {
    // 'new', 'used', 'refurbished'
    return 'new';
  }

  /**
   * get size
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getSize( $product = NULL) {
    if (!is_null($product->getSize())) {
      $size = $product->getAttributeText('size');
    }

    return $size;
  }

  /**
   * get google_product_category
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getGMProductCategory( $product = NULL) {
    return self::GOOGLE_PRODUCT_CATEGORY;
  }

  /**
   * get ID
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getId( $product = NULL) {

    return $product->getSku();
  }

  /**
   * get MPN
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getMpn( $product = NULL) {


    $gmpn = $product->getData('barcodes');

    if (!empty($gmpn)) {
      return $product->getSku();
    } else {
      return "";
    }
  }

  protected function _getGtin( $product = NULL) {
    return $product->getBarcodes();
  }

  /**
   * get formatted price
   *
   * @param string
   *
   * @return string
   */
  protected function _getPrice($price) {
         $output = round($price, 2) . ' GBP';
    return $output;
  }

  /**
   * get formatted special price
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getSalePrice( $product = NULL) {
    $sale_price = "";
   // if (!is_null($product->getSpecialPrice()) && Mage::app()->getLocale()->isStoreDateInInterval(NULL, $product->getSpecialFromDate(), $product->getSpecialToDate())) {
   //   $sale_price = $this->_getPrice($product->getSpecialPrice());
   // }
                      $sale_price = $this->_getPrice($product->getSpecialPrice());
   $price =  $product->getPrice();
    if ($sale_price === $price) {
      $sale_price = "";
    }

    return $sale_price;
  }
  /**
   * get brand
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getAgeGroup( $product = NULL) {
   
      $age_group_valid['newborn'] = 'newborn';
      $age_group_valid['toddler'] = 'toddler';
      $age_group_valid['infant'] = 'infant';
      $age_group_valid['kids'] = 'kids';
      $age_group_valid['adult'] = 'adult';
 
    $age_group = $product->getData('google_age_group');	
    if(array_key_exists($age_group, $age_group_valid)){
 
      return $age_group;
    } else {
      return "";
    }

    // return $brand;
  }
  /**
   * get brand
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getBrand( $product = NULL) {
  $brand  = '';
    if (!is_null($product->getManufacturer())) {
      $brand = $product->getAttributeText('manufacturer');
    } else {
      //$brand = Mage::getStoreConfig('general/store_information/name');
    }

    $brand = $product->getData('google_brand');	
    $gmpn = $product->getData('barcodes');

    if (!empty($gmpn)) {
      return $brand;
    } else {
      return "";
    }

    // return $brand;
  }

  /**
   * get colour
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getColour( $product = NULL) {
    $colour = "";

    // if (!is_null($product->getColorDescription())) {
    //     $colour = $product->getColorDescription();
    // }
    // else if (!is_null($product->getColor())) {
    //     $colour = $product->getAttributeText('swatch_colour');
    // } else if (!is_null($product->getColorFilter())) {
    //     $colour = $product->getAttributeText('color_filter');
    // }

    $colour = $product->getResource()->getAttribute('swatch_colour')->getFrontend()->getValue($product);

    $colour = explode("|", $colour);

    $colour = $colour[0];

    if ($colour == "No") {
      $colour = '';
    }

    return $colour;
  }

  /**
   * get google_merchant_age_group
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getGMAgeGroup( $product = NULL) {
    // 'Adult', 'Kids'
    if (!is_null($product->getGoogleMerchantAgeGroup())) {
      $google_merchant_age_group = $product->getAttributeText('google_merchant_age_group');
    } else {
      $google_merchant_age_group = 'Adult';
    }

    return $google_merchant_age_group;
  }

  /**
   * get google_merchant_gender
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getGMGender( $product = NULL) {


    $gender_lookup['Female'] = 'female';
    $gender_lookup['Womens'] = 'female';
    $gender_lookup['Male'] = 'male';
    $gender_lookup['Mens'] = 'male';
    $gender_lookup['Girls'] = 'female';
    $gender_lookup['Boys'] = 'male';
    $gender_lookup['Unisex'] = 'unisex';
    $gender_lookup['N/A'] = 'unisex';
    $gender_lookup['Ladies'] = 'female';
    $gender_lookup['Kids'] = 'unisex';
    $gender_lookup['Baby Boys'] = 'male';
    $gender_lookup['female'] = 'female';

    // 'Male', 'Female', 'Unisex'
    if (!is_null($product->getGender())) {
      $google_merchant_gender = $product->getAttributeText('gender');
    } else {
      $google_merchant_gender = 'female';
    }

    $google_merchant_gender = $gender_lookup[$google_merchant_gender];
    if ($google_merchant_gender == '') {
      $google_merchant_gender = 'female';
    }

    return $google_merchant_gender;
  }

  /**
   * get description
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getDescription( $product = NULL) {
      
      

   
    $product_parent = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($product->getId());
     if(isset($product_parent[0])){
         //this is parent product id..

         $product =  $this->productrepository->getById($product_parent[0]);
    }

      
    if (!is_null($product->getDescription())) {
      $description = $product->getDescription();
    } else if (!is_null($product->getShortDescription())) {
      $description = $product->getShortDescription();
    } else {
      $description = '';
    }

    return iconv("UTF-8", "UTF-8//IGNORE", $description);
  }

  /**
   * get type
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getProductType( $product = NULL) {
    $product_categories = array();
    foreach ($product->getCategoryIds() as $_categoryId) {

                      $category = $this->categoryRepository->get($_categoryId, $this->_storeManager->getStore()->getId());
                  $category2 = $this->categoryRepository->get($category->getData('parent_id'), $this->_storeManager->getStore()->getId());

    

      $cat_name = $category->getName();
      $cat_2 = $category2->getName();

      if ($cat_2 == 'Default Category') {
        $cat_2 = '';
      }
      if (!empty($cat_2)) {

        $cat_name = $category2->getName() . " > " . $cat_name;

        $product_categories[$category2->getName()] = $category2->getName();
        $product_categories[$cat_name] = $cat_name;
      }

    }

    $product_categories = array_unique($product_categories);
    $product_categories = array_slice($product_categories, 0, self::PRODUCT_TYPE_MAX);
    $product_type = implode(",", $product_categories);
    if ($product_type == '') {
      $product_type = 'Divan Beds';
    }

    return $product_type;
  }

  /**
   * get image link
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getImageLink( $product = NULL) {
    $image_link = NULL;
    $image_link = NULL;
    echo $product->getImage();
    if (!is_null($product->getImage()) && $product->getImage() !== "no_selection") {
      $image_link = Mage::getSingleton('catalog/product_media_config')->getBaseMediaUrl() . $product->getImage();
    }

    return $image_link;

  }

  /**
   * get item group id (configurable)
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getItemGroupId( $product = NULL) {


   
    $product_parent = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($product->getId());
     if(isset($product_parent[0])){
         //this is parent product id..

         $product =  $this->productrepository->getById($product_parent[0]);
    }

    return $product->getSku();
  }

  /**
   * get link
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getLink( $product = NULL) {
    $link = $product->getProductUrl();


    return $link;
  }

  /**
   * get additional_image_link
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getAdditionalImageLink( $product = NULL) {


    if($product->getTypeId() == 'simple'){ 
   
    $product_parent = $this->objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($product->getId());
  
   if(isset($product_parent[0])){
         //this is parent product id..

         $product =  $this->productrepository->getById($product_parent[0]);
    }
    } 
    $main_image = $product->getGoogleImage();
    
  
    // If Google Image is not set

    if (empty($main_image) || $main_image == 'no_selection') {
    
      $main_image = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product'.$product->getImage();
    }


        
     


   // $main_image = Mage::getModel('catalog/product_media_config')->getMediaUrl($main_image);

    $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
    $media_gallery = $attributes['media_gallery'];
    $backend = $media_gallery->getBackend();
    $backend->afterLoad($product); // this loads the media gallery to the product object
    $count = 0;
    $lowest_position = false;
    $image_default = false;
    $image_lowest = false;
    $image_first = false;
           $all_images = [];
           
           $this->objectManager->get("Magento\Catalog\Model\Product\Gallery\ReadHandler")->execute($product);

          
   // echo $product->getSku()."\n";
    foreach ($product->getMediaGalleryImages() as $image) {

      //print_r($image->getData());

      $count++;
      if ($count == 9) {
        break;
      } //dont send too many images - in fact we cant break

      //select first image
      if ($count == 1) {
        $image_first = $image->getUrl();
      }
      //set lowest position
      $current_position = $image->getPositionDefault();
      if (!$lowest_position || $current_position < $lowest_position) {
        //echo $current_position." is < ".$lowest_position;
        $image_lowest = $image->getUrl();
        $lowest_position = $current_position;
      }


      //set default image - which is the google feed or default image
      if ($main_image == $image->getUrl()) {
        $image_default = $image->getUrl();
      }

      $current_position = $image->getPositionDefault();

      $all_images[] = $image->getUrl();

    }

    if ($image_default) {
      //echo "image final = image default \n";
      $image_final = $image_default;
    } else if ($image_lowest) {
      //echo "image final = image lowest \n";
      $image_final = $image_lowest;
    } else {
      //echo "image final = image first \n";
      $image_final = $image_first;
    }
    //echo "Image Final: ".$image_final;
    // echo "\n";
    $media_gallery_images['main'] = $image_final;

    $all_images = array_flip($all_images);
    unset($all_images[$image_final]);
    $all_images = array_flip($all_images);

    $only_nine_images_allowed = array_slice($all_images, 0, 9);
    $media_gallery_images['additional'] = implode(",", $only_nine_images_allowed);

               
    return $media_gallery_images;
  }

  /**
   * sanitise data
   *
   * @param array $data
   *
   * @return array
   */
  protected function _sanitiseData(array $data = NULL) {
    foreach ($data as $key => $value) {
      $bad = array(
        "\r\n",
        "\n",
        "\r",
        "\t"
      );
      $good = array(
        ". ",
        ". ",
        ". ",
        ""
      );
      $data[$key] = trim(str_replace($bad, $good, $value));
    }

    return $data;
  }

  /**
   * get image thumbnail link
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getImageThumbLink( $product = NULL) {


    $image_link = NULL;
    if (!is_null($product->getImage()) && $product->getImage() !== "no_selection") {
      $image_link = Mage::helper('catalog/image')->init($product, 'image')->resize(204, 295)->__toString();

      //echo Mage::helper('catalog/image')->init($product, 'rollover_image')->resize(204,295)->__toString();

      //$image_link = Mage::helper('catalog/image')->init($product, 'small_image')->resize(135);
    }

    return $image_link;
  }

  /**
   * get image thumbnail link
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getImageRolloverLink( $product = NULL) {


    $image_link = NULL;
    // echo $product->getRolloverImage();
    if (file_exists($product->getRolloverImage()) && $product->getRolloverImage() !== "no_selection") {


      $image_link = Mage::helper('catalog/image')->init($product, 'rollover_image')->resize(204, 295)->__toString();

      //$image_link = Mage::helper('catalog/image')->init($product, 'small_image')->resize(135);
    }

    return $image_link;
  }

  /**
   * get image thumbnail link
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getImageSwatchLink( $product = NULL) {


    $image_link = NULL;
    if (file_exists($product->getSwatchImage()) && $product->getSwatchImage() !== "no_selection") {
      $image_link = Mage::helper('catalog/image')->init($product, 'swatch_image')->resize(204, 295)->__toString();

      //$image_link = Mage::helper('catalog/image')->init($product, 'small_image')->resize(135);
    }

    return $image_link;
  }

  protected function _getStockLevel( $product = NULL) {
    $stock = 0;
    foreach ($product->getTypeInstance(true)->getUsedProducts(NULL, $product) as $simple) {
      $stock += Mage::getModel('cataloginventory/stock_item')->loadByProduct($simple)->getQty();

    }

    return $stock;
  }

  protected function _applyStockItems($productCollection) {
    if (Mage::helper('core')->isModuleEnabled('Skywire_MultiWarehouse')) {
      $stockId = Mage::helper('multiwarehouse')->getWebsiteStockId();
    } else {
      $stockId = 1;
    }
    Mage::getModel('cataloginventory/stock')->setStockId($stockId)->addItemsToProducts($productCollection);
  }

  /**
   * get type
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getProductCat( $product = NULL) {
    return $product->getResource()->getAttribute('product_cat')->getFrontend()->getValue($product);

  }

  protected function _getProductSubCat( $product = NULL) {
    return $product->getResource()->getAttribute('product_subcat')->getFrontend()->getValue($product);
  }

  /**
   * get type
   *
   * @param Mage_Catalog_Model_Product $product
   *
   * @return string
   */
  protected function _getProductCategory( $product = NULL) {
    $product_categories = array();
    foreach ($product->getCategoryIds() as $_categoryId) {
      $category = Mage::getModel('catalog/category')->load($_categoryId);
      $product_categories[] = $category->getName();
    }
    $product_categories = array_unique($product_categories);
    $product_categories = array_slice($product_categories, 0, 3);
    $product_type = implode(",", $product_categories);

    return $product_type;
  }

  protected function _getUsedProducts($configurable) {
    return Mage::getSingleton('catalog/product_type_configurable')->getUsedProducts(NULL, $configurable);

  }

  protected function _getAssociatedProducts($product) {
    if ($product->getTypeId() != Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
      return '';
    }
    $children = $this->_getUsedProducts($product);
    $skus = array();
    foreach ($children as $child) {
      $skus[] = $child->getSku();
    }

    return implode(',', $skus);
  }

  protected function _getParentProducts($product) {
    $child_id = Mage::getModel('catalog/product')->getIdBySku($product->getSku());
    $parent_ids = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($child_id);
    $parent_collection = Mage::getResourceModel('catalog/product_collection')->addFieldToFilter('entity_id', array('in' => $parent_ids))->addAttributeToSelect('sku');
    $parent_skus = $parent_collection->getColumnValues('sku');

    return implode(',', $parent_skus);
  }

  protected function _writeFile($filename, $data) {


    if (!$this->_handle) {

      $this->_handle = fopen($filename, 'w');
      $headers = array_keys($data[0]);
      $this->_feed_line = implode("\t", $headers) . "\r\n";
      // print_r($this->_feed_line);

      fwrite($this->_handle, $this->_feed_line);
      fflush($this->_handle);

    }
    foreach ($data as $row) {


      $this->_feed_line = implode("\t", $row) . "\r\n";
      fwrite($this->_handle, $this->_feed_line);
      fflush($this->_handle);
    }

  }

  protected function _closeFile() {
    fclose($this->_handle);
  }

  protected function _removeLocalFile($filePath) {
    if (!unlink($filePath)) {
      throw new Exception("Failed to unlink $filePath");
    }
  }
    private function sendEmail($message){
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
   //     mail("andy.eades@elevateweb.co.uk","Products With Price of Zero", $message);
    }
}