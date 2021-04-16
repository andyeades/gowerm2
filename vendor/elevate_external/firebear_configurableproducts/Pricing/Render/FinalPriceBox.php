<?php

namespace Firebear\ConfigurableProducts\Pricing\Render;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Firebear\ConfigurableProducts\Helper\Data as ICPHelperData;
use Magento\Customer\Model\Session;


/**
 * Class FinalPriceBox
 * @package Firebear\ConfigurableProducts\Pricing\Render
 */
class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{
    /**
     * @var ICPHelperData
     */
    public $icpHelper;
    public $evHelper;
    
    
             
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
     * @param ICPHelperData $icpHelper
     * @param array $data
     * @param SalableResolverInterface|null $salableResolver
     * @param MinimalPriceCalculatorInterface|null $minimalPriceCalculator
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        ICPHelperData $icpHelper,
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
        $this->_session = $session;
        $this->evHelper = $evHelper;
               
               
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
            return true;
        }
        
        return false;
    }
    
  function createMconnectPrice($nav_id, $product_id, $nav_price, $price_type){
  
     return $this->icpHelper->createMconnectPrice($nav_id, $product_id, $nav_price, $price_type);
     
     }
  function getMconnectPrice($product_id, $price_type){
  
     return $this->icpHelper->getMconnectPrice($product_id, $price_type);
  
  }  
    
    /**
     * {@inheritdoc}
     */
    protected function wrapResult($html)
    {
        if (!$this->icpHelper->hidePrice()) {
            return '<div class="price-box ' . $this->getData('css_classes') . '" ' .
                'data-role="priceBox" ' .
                'data-product-id="' . $this->getSaleableItem()->getId() . '"' .
                '>' . $html . '</div>';
        } else {
            $priceText = $this->icpHelper->getGeneralConfig('general/price_text');
            return '<div ' .
                'data-role="priceBox" ' .
                'data-product-id="' . $this->getSaleableItem()->getId() . '"' .
                '><span>'.$priceText.'</span></div>';
        }
    }
}
