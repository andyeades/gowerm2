<?php
/**
 * @copyright: Copyright © 2017 Firebear Studio. All rights reserved.
 * @author   : Firebear Studio <fbeardev@gmail.com>
 */

namespace Firebear\ConfigurableProducts\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ObjectManager;

class Data extends AbstractHelper
    implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    const XML_PATH_CONFIG_COINPAYMENTS = 'firebear_configurableproducts/';

    private $containerData;
    private $catalogProduct;
    private $stockRegistry;
    protected $fields = ['x_axis', 'y_axis'];
    protected $filterProvider;
    protected $storeManager;
       public $evHelper;
        
    protected $mconnectNavId;
             
        protected $customerFactory;
        protected $customerRepository;
        protected $_customerSession;
       protected  $resource;
    
    /**
     * @var ConfigurableAttributeHandler
     */
    protected $configurableAttributeHandler;

    /**
     * @var HttpContext
     */
    private $httpContext;
   protected $coreSession;
    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\CatalogInventory\Model\StockRegistry $stockRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param ConfigurableAttributeHandler $configurableAttributeHandler
     * @param HttpContext|null $httpContext
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\CatalogInventory\Model\StockRegistry $stockRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        ConfigurableAttributeHandler $configurableAttributeHandler,
        HttpContext $httpContext = null,
        \Elevate\Promotions\Helper\Data $evHelper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession,   
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Session\SessionManagerInterface $coreSession   
    ) {
        $this->stockRegistry  = $stockRegistry;
        $this->catalogProduct = $catalogProduct;
        $this->filterProvider = $filterProvider;
        $this->storeManager = $storeManager;
        $this->configurableAttributeHandler = $configurableAttributeHandler;
        $this->httpContext = $httpContext ?: ObjectManager::getInstance()->get(HttpContext::class);
        $this->evHelper = $evHelper;    
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession; 
         $this->coreSession = $coreSession;
        $this->resource = $resource;
        parent::__construct($context);
        $this->defineContainerData();
    }

    
   public function getNavId(){

   return $this->_customerSession->getNavId();
   } 
     public function setNavId($nav_id){

   $this->_customerSession->setNavId($nav_id);
   }   
    /*
     * 
     */
    public function getAttrContent($attr) {
        $store_id = $this->storeManager->getStore()->getId();
        return $this->filterProvider->getBlockFilter()->setStoreId($store_id)->filter($attr);
    }

     function getMconnectPriceNew($key){
      $data = '';
       $connection  = $this->resource->getConnection();
         
     $query = "SELECT data from `elevate_price_index` WHERE customer_idproduct_id = $key";

          try{
        $data = json_decode($connection->fetchOne($query), true);
        }
        catch(Exception $e){
        
        }
        
        return $data;
        
     }
    function createMconnectPriceNew($key, $data){
  
     
         if($key){ 
            $connection  = $this->resource->getConnection();
         
             //For Insert query
              //    echo "NAV PRICE=".$nav_price;
$query = "INSERT INTO `elevate_price_index`(`customer_idproduct_id`, `data`) VALUES ('$key','".$data."')
ON DUPLICATE KEY UPDATE
  customer_idproduct_id     = VALUES(customer_idproduct_id),
  data = VALUES(data)
";

          try{
        $connection->query($query);
        }
        catch(Exception $e){
        
        }
  }
  else{
  //echo "NO NAV PRICE";
  }
    
   
     }

function createMconnectPrice($nav_id, $product_id, $nav_price, $price_type){
  
                 
     /*     
         if($nav_id){ 
            $connection  = $this->resource->getConnection();
         
             //For Insert query
              //    echo "NAV PRICE=".$nav_price;
$query = "INSERT INTO `ecomwise_mconnector_price_index`(`customer_no`, `product_id`, `nav_price`, `price_type`) VALUES ('$nav_id','".$product_id."','".$nav_price."','".$price_type."')";
          try{
        $connection->query($query);
        }
        catch(Exception $e){
        
        }
  }
  else{
  //echo "NO NAV PRICE";
  }
    
      */
     }
  function getMconnectPrice($product_id, $price_type){
         $is_nav_customer = false;      
          $hasCachedPrice = false;     
               $currentCustomer = false;    
$not_logged_in_email = $this->evHelper->getConfig('mconnector/custom_settings/not_logged_in_email');

       $mconnectCustomerData = [];
//if(!empty($this->icpHelper->getNavId())){

      // $nav_id = $this->icpHelper->getNavId();
//}else{
        
     
        
        if(!empty($not_logged_in_email)){
        $is_nav_customer = true;
            $currentCustomer = $this->customerFactory->create()->loadByEmail($not_logged_in_email);
        }else{

            // $currentCustomer = $this->registry->registry('currect_customer_price_rules');
            // $currentCustomer = $this->customerSession->getCustomer();

        }

        if(!($currentCustomer)){
            // $currentCustomer = $this->customerSession->getCustomer();
            // $currentCustomer = $this->registry->registry('currect_customer_price_rules');

        }

        if(!$currentCustomer || !$currentCustomer->getId()){

            // if($storeManager->getAreaCode() === 'adminhtml'){
            //     $currentCustomerId = $this->backendSession->getQuote()->getCustomer()->getId();
            //     $currentCustomer = $this->customerFactory->create()->load($currentCustomerId);
            // }else{
            //    $currentCustomer = $this->customerSession->getCustomer();
            //}
            $currentCustomer = $this->_customerSession->getCustomer();




            $is_contact = (bool) $currentCustomer->getIsContact();
            if($is_contact){
                $parent_customer_id = $currentCustomer->getNavContactCustomerId();
                if(!empty($parent_customer_id)){
                    $parent_customer =  $currentCustomer
                        ->getCollection()
                        ->addAttributeToSelect(['*'])
                        ->addAttributeToFilter('navision_customer_id', $parent_customer_id)
                        ->getFirstItem();
                    if($parent_customer && $parent_customer->getId()){
                        $currentCustomer = $parent_customer;
                    }
                }

            }
            
        }        
          $nav_id = $currentCustomer->getNavisionCustomerId();
    $mconnectCustomerData['customer_price_group'] = $currentCustomer->getCustomerPriceGroup(); 
$mconnectCustomerData['customer_discount_group'] = $currentCustomer->getCustomerDiscountGroup();
  $mconnectCustomerData['customer_group_id']  = $currentCustomer->getCustomerGroupId();
  $mconnectCustomerData['customer_id']  = $currentCustomer->getId();
    $mconnectCustomerData['nav_id']  = $nav_id;
         $this->coreSession->setMconnectCustomerData($mconnectCustomerData);
                                
 $reponse['customer_price_group'] =  $mconnectCustomerData['customer_price_group'];
$reponse['customer_discount_group'] =  $mconnectCustomerData['customer_discount_group'];
  $reponse['customer_group_id']  =  $mconnectCustomerData['customer_group_id'];
  $reponse['customer_id']  =  $mconnectCustomerData['customer_id'];
     $reponse['nav_id'] = $mconnectCustomerData['nav_id']; 
                                    
     $nav_id = $mconnectCustomerData['nav_id'];
        if($nav_id){ 
                $is_nav_customer = true;
  
          $connection  = $this->resource->getConnection();
        // $table is table name
      
        $id = 2;
        $query = "SELECT * FROM `ecomwise_mconnector_price_index`  WHERE customer_no = '$nav_id' AND product_id = '$product_id' AND price_type = '$price_type' LIMIT 0, 1";
        $result1 = $connection->fetchAll($query);
            
         //    echo $query;
         if(count($result1)>0){
            //  print_r($result1);
         if(isset($result1[0]['nav_price']) && isset($result1[0]['customer_no'])){
           $nav_price = $result1[0]['nav_price'];
           
         
                 //               echo "USE CACHED PRICE - $nav_price <br>";
                 $hasCachedPrice = true;               
                 //return $nav_price;
         }
         }
            }
            $reponse = [];
            $reponse['has_nav_cached_price'] = $hasCachedPrice;
            $reponse['is_nav'] = $is_nav_customer;
             $reponse['customer_no'] = $nav_id;  
             $reponse['product_id'] = $product_id;   
             
                 
        //   $e = new \Exception; var_dump($e->getTraceAsString());
          if($hasCachedPrice){
                
                   $reponse['nav_price'] = $nav_price; 
                  
                
                       
          }                       
         return $reponse;
  
  }

    
    public function defineContainerData()
    {
        $this->containerData = [
            'container_x_axis' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement'   => 'container',
                            'componentType' => 'container',
                            'breakLine'     => '',
                            'label'         => '',
                            'required'      => 0,
                            'sortOrder'     => 0,
                            'component'     => 'Magento_Ui/js/form/components/group',
                            'dataScope'     => ''
                        ]
                    ]
                ],
                'children'  => [
                    'x_axis' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType'      => 'text',
                                    'formElement'   => 'select',
                                    'visible'       => 1,
                                    'required'      => 0,
                                    'notice'        => 'Select an attribute code for the X axis',
                                    'default'       => '',
                                    'label'         => 'Attribute code for matrix X axis',
                                    'code'          => 'attribute_for_x_axis',
                                    'source'        => '',
                                    'globalScope'   => '',
                                    'sortOrder'     => 1,
                                    'componentType' => 'field',
                                    'options' => $this->getAttributesOptionsForMatrix()
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'container_y_axis'  => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement'   => 'container',
                            'componentType' => 'container',
                            'breakLine'     => '',
                            'label'         => '',
                            'required'      => 0,
                            'sortOrder'     => 0,
                            'component'     => 'Magento_Ui/js/form/components/group',
                            'dataScope'     => ''
                        ]
                    ]
                ],
                'children'  => [
                    'y_axis' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType'      => 'text',
                                    'formElement'   => 'select',
                                    'visible'       => 1,
                                    'required'      => 0,
                                    'notice'        => 'Select an attribute code for the Y axis',
                                    'default'       => '',
                                    'label'         => 'Attribute code for matrix Y axis',
                                    'code'          => 'attribute_for_y_axis',
                                    'source'        => '',
                                    'globalScope'   => '',
                                    'sortOrder'     => 2,
                                    'componentType' => 'field',
                                    'options' => $this->getAttributesOptionsForMatrix()
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'container_linked_attributes' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement'   => 'container',
                            'componentType' => 'container',
                            'breakLine'     => '',
                            'label'         => '',
                            'required'      => 0,
                            'sortOrder'     => 0,
                            'component'     => 'Magento_Ui/js/form/components/group',
                            'dataScope'     => ''
                        ]
                    ]
                ],
                'children'  => [
                    'linked_attributes' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType'      => 'text',
                                    'formElement'   => 'multiselect',
                                    'visible'       => 1,
                                    'required'      => 0,
                                    'notice'        => '',
                                    'default'       => '',
                                    'label'         => 'Attributes list',
                                    'code'          => 'linked_attributes',
                                    'source'        => '',
                                    'globalScope'   => '',
                                    'sortOrder'     => 1,
                                    'componentType' => 'field',
                                    'options' => $this->getAttributesOptions(),
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'container_display_matrix' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement'   => 'container',
                            'componentType' => 'container',
                            'breakLine'     => '',
                            'label'         => '',
                            'required'      => 0,
                            'sortOrder'     => 0,
                            'component'     => 'Magento_Ui/js/form/components/group',
                            'dataScope'     => ''
                        ]
                    ]
                ],
                'children'  => [
                    'display_matrix' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType'      => 'string',
                                    'formElement'   => 'select',
                                    'visible'       => 1,
                                    'required'      => 0,
                                    'notice'        => '',
                                    'default'       => '',
                                    'label'         => 'Display matrix or swatch',
                                    'code'          => 'display_matrix',
                                    'source'        => '',
                                    'globalScope'   => '',
                                    'sortOrder'     => 1,
                                    'componentType' => 'field',
                                    'options' => $this->getDisplayAttributesOptions(),
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * @param      $field
     * @param null $storeId
     *
     * @return mixed
     */
    private function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param      $code
     * @param null $storeId
     *
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONFIG_COINPAYMENTS . $code, $storeId);
    }

    /**
     * @return array
     */
    public function getContainerData()
    {
        return $this->containerData;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $product
     *
     * @return \Magento\Catalog\Model\Product[]
     */
    public function getAllowProducts($product)
    {

        $skipSaleableCheck = $this->catalogProduct->getSkipSaleableCheck();

        $products = $skipSaleableCheck
            ?
            $product->getTypeInstance()->getUsedProducts($product, null)
            :
            $product->getTypeInstance()->getSalableUsedProducts($product, null);

        return $products;
    }

    public function getChildInStock($product)
    {
        $inStock = [];
        foreach ($this->getAllowProducts($product) as $_product) {
            $stockItem = $this->stockRegistry->getStockItem($_product->getId(), 1);
            $saleable  = $stockItem->getIsInStock();
            if ($saleable) {
                $inStock[] = $_product;
            }
        }

        return $inStock;
    }

    /**
     * Get a list of product attributes
     *
     * @return array
     */
    public function getAttributesOptions()
    {
        $attributesOptions = [];
        foreach ($this->configurableAttributeHandler->getApplicableAttributes() as $attributes) {
            if ($this->configurableAttributeHandler->isAttributeApplicable($attributes)) {
                $attributesOptions[] = [
                    'value' => $attributes->getAttributeId(), 'label' => $attributes->getAttributeCode()];
            }
        }
        return $attributesOptions;
    }

    /**
     * Get a list of product attributes for matrix
     *
     * @return array
     */
    public function getAttributesOptionsForMatrix()
    {
        $attributesOptions = [];
        $attributesOptions[] = ['value' => 'default', 'label' => 'Use extension settings'];
        foreach ($this->configurableAttributeHandler->getApplicableAttributes() as $attributes) {
            if ($this->configurableAttributeHandler->isAttributeApplicable($attributes)) {
                $attributesOptions[] = [
                    'value' => $attributes->getAttributeCode(), 'label' => $attributes->getAttributeCode()];
            }
        }
        return $attributesOptions;
    }

    public function getDisplayAttributesOptions()
    {
        return [
            ['value' => '0', 'label' => __('Use extension settings')],
            ['value' => '1', 'label' => __('Matrix')],
            ['value' => '2', 'label' => __('Swatch')],
        ];
    }

    /**
     * Hide price for unregistered users
     *
     * return bool
     */
    public function hidePrice() {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        return ($this->getGeneralConfig('general/hide_price') && !$isLoggedIn);
    }
}
