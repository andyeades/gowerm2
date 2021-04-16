<?php

namespace Firebear\ConfigurableProducts\Pricing\Bundle\Render;

use Magento\Bundle\Pricing\Price\FinalPrice;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\CustomOptionPrice;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;
use Firebear\ConfigurableProducts\Helper\Data as IcpHelper;
use Magento\Customer\Model\Session;

class FinalPriceBox extends \Magento\Bundle\Pricing\Render\FinalPriceBox
{
    /**
     * @var IcpHelper
     */
    public $icpHelper;
      public $evHelper;
    protected $_session;
                 
        protected $customerFactory;
        protected $customerRepository;
        protected $_customerSession;
       protected  $resource;
    /**
     * FinalPriceBox constructor.
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param IcpHelper $icpHelper
     * @param array $data
     * @param SalableResolverInterface|null $salableResolver
     * @param MinimalPriceCalculatorInterface|null $minimalPriceCalculator
     */
    public function __construct(
        Context $context,
        SaleableInterface
        $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        IcpHelper $icpHelper,
        array $data = [],
        SalableResolverInterface $salableResolver = null,
        MinimalPriceCalculatorInterface $minimalPriceCalculator = null,
        Session $session,
        \Elevate\Promotions\Helper\Data $evHelper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        parent::__construct(
            $context,
            $saleableItem,
            $price,
            $rendererPool,
            $data,
            $salableResolver,
            $minimalPriceCalculator
        );
        $this->icpHelper = $icpHelper;
           $this->evHelper = $evHelper;
        $this->_session = $session;
                       
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->resource = $resource;
    }



   public function isLoggedIn()
    {
    

$not_logged_in_email = $this->evHelper->getConfig('mconnector/custom_settings/not_logged_in_email');


    
  if($this->_session->isLoggedIn() || !empty($not_logged_in_email)) {
            //customer has logged in
            // your code in here
          //  echo "LOGGED";
            return true;
        }
        
      //  echo "NOT LOGGED";
        return false;
    }
    
    
    
  function createMconnectPrice($nav_id, $product_id, $nav_price, $price_type){
       
       return $this->icpHelper->createMconnectPrice($nav_id, $product_id, $nav_price, $price_type); 
       
         
         if($nav_id ){ 
             //For Insert query
            $connection  = $this->resource->getConnection();
       
      if(is_object($nav_price)){
      
      if(method_exists($nav_price,'getAmount')){
      $nav_price->getAmount();
      }
      } 
     
$query = "INSERT INTO `ecomwise_mconnector_price_index`(`customer_no`, `product_id`, `nav_price`, `price_type`) VALUES ('$nav_id',
'".$product_id."',
'".$nav_price."',
'".$price_type."')";
        // echo $query;
      $connection->query($query);
  }
  else{
  //echo "NO NAV PRICE";
  }
     
     
     }
  function getMconnectPrice($product_id, $price_type){
          return $this->icpHelper->getMconnectPrice($product_id, $price_type);
     
$not_logged_in_email = $this->evHelper->getConfig('mconnector/custom_settings/not_logged_in_email');
         $is_nav_customer = false;
     $hasCachedPrice = false;
   $currentCustomer = false;
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
            $currentCustomer = $this->_customerSession->create()->getCustomer();




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
        if($nav_id){ 
                $is_nav_customer = true;
        
          $connection  = $this->resource->getConnection();
        // $table is table name
      
        $id = 2;
        $query = "SELECT * FROM `ecomwise_mconnector_price_index`  WHERE customer_no = '$nav_id' AND product_id = '$product_id' AND price_type = '$price_type' LIMIT 0, 1";
        $result1 = $connection->fetchAll($query);
               
            
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
            
            $reponse['is_nav'] = $is_nav_customer;
             $reponse['customer_no'] = $nav_id;  
             $reponse['product_id'] = $product_id;   
              $reponse['has_nav_cached_price'] = $hasCachedPrice;
        //   $e = new \Exception; var_dump($e->getTraceAsString());
          if($hasCachedPrice){
                  
                   $reponse['nav_price'] = $nav_price; 
                   
                
                       
          }                       
         return $reponse;
  
  }
    /**
     * @return bool|mixed
     */
    public function showPrice()
    {
        if ($this->icpHelper->hidePrice()) {
            return $this->icpHelper->getGeneralConfig('general/price_text');
        } else {
            return true;
        }
    }
}
