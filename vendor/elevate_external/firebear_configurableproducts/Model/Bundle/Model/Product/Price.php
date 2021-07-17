<?php

namespace Firebear\ConfigurableProducts\Model\Bundle\Model\Product;


use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Api\Data\ProductTierPriceExtensionFactory;

class Price extends \Magento\Bundle\Model\Product\Price
{
    /**
     * @var IcpHelper
     */

      public $evHelper;

                 
        protected $customerFactory;
        protected $customerRepository;
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
         \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        PriceCurrencyInterface $priceCurrency,
        GroupManagementInterface $groupManagement,
        \Magento\Catalog\Api\Data\ProductTierPriceInterfaceFactory $tierPriceFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        ProductTierPriceExtensionFactory $tierPriceExtensionFactory = null,
        
        \Elevate\Promotions\Helper\Data $evHelper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        parent::__construct(
            $ruleFactory,
            $storeManager,
            $localeDate,
            $customerSession,
            $eventManager,
            $priceCurrency,
            $groupManagement,
            $tierPriceFactory,
            $config,
            $catalogData,
            $serializer,            
            $tierPriceExtensionFactory
        );
   
           $this->evHelper = $evHelper;
  
                       
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;

        $this->resource = $resource;
    }
  
    /**
     * Get product final price
     *
     * @param float $qty
     * @param \Magento\Catalog\Model\Product $product
     * @return float
     */
    public function getFinalPrice($qty, $product)
    {
    
        
              
        if ($qty === null && $product->getCalculatedFinalPrice() !== null) {
            return $product->getCalculatedFinalPrice();
        }

        $finalPrice = $this->getBasePrice($product, $qty);
        $product->setFinalPrice($finalPrice);
        $this->_eventManager->dispatch('catalog_product_get_final_price', ['product' => $product, 'qty' => $qty]);
        $finalPrice = $product->getData('final_price');

        $finalPrice = $this->_applyOptionsPrice($product, $qty, $finalPrice);
        $finalPrice += $this->getTotalBundleItemsPrice($product, $qty);

        $finalPrice = max(0, $finalPrice);
        $product->setFinalPrice($finalPrice);
     
 
                
        return $finalPrice;
    }
}
