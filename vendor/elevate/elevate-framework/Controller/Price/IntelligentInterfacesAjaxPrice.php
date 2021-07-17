<?php

namespace Elevate\Framework\Controller\Price;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class IntelligentInterfacesAjaxPrice extends \Magento\Framework\App\Action\Action
{


    /**
     * @var Session
     */
    private $session;
    /**
     * @var StockItemRepository
     */
            protected $taxHelper;

    /**
     * @var Data
     */
    protected $_jsonHelper;

    /**
     * @var LoggerInterface
     */
    protected $_logger;
        protected $layoutFactory;

    protected $_productCollectionFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param Data $jsonHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        LoggerInterface $logger,
        \Magento\Customer\Model\Session $session,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Catalog\Helper\Data $taxHelper
    ) {
        parent::__construct($context);
        $this->_jsonHelper = $jsonHelper;
        $this->_logger = $logger;
        $this->session = $session;
        $this->_productCollectionFactory = $productCollectionFactory; 
        $this->layoutFactory = $layoutFactory; 
         $this->taxHelper = $taxHelper;


    }
 public function getPrice(\Magento\Catalog\Model\Product $product)
{
    $priceRender = $this->layoutFactory->create()->getBlock('product.price.render.default');
    if (!$priceRender) {
        $priceRender = $this->layoutFactory->create()->createBlock(
            \Magento\Framework\Pricing\Render::class,
            'product.price.render.default',
            ['data' => ['price_render_handle' => 'catalog_product_prices']]
        );
    }

    $price = '';
    if ($priceRender) {
        $price = $priceRender->render(
            \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
            $product,
            [
                'display_minimal_price'  => true,
                'use_link_for_as_low_as' => true,
                'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
            ]
        );
    }

    return $price;
}
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
    
    //https://p2.posturite.co.uk/elevate/price/intelligentinterfacesajaxprice?skus=9787381BLA,9787381NAV,9787381RED,9787381ROY
    
        $json = [];                              
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$abstractProductBlock = $this->layoutFactory->create()->createBlock('Magento\Catalog\Block\Product\ListProduct');
               
                      //$this->layoutFactory->create()->createBlock('Block\Class\Here');
         $product_ids_array = explode(',', $this->getRequest()->getParam('skus'));
         $productCollection = $this->_productCollectionFactory->create();
         $productCollection->addAttributeToSelect('*')
                           ->addFieldToFilter('sku', array('in'=> $product_ids_array))
                           ->addMinimalPrice()
                           ->addFinalPrice()
                           ->addTaxPercents()
                           ->addAttributeToSelect('image')
                          ->addAttributeToSelect('price')
                          ->addAttributeToSelect('name');

//$productCollection->load();

        foreach($productCollection AS $product){
         // <img src=" echo $abstractProductBlock->getImage($product, 'latest_collection_list')->getImageUrl();" alt=" echo $product->getName()" />

          //  $_product = Mage::getModel('catalog/product')->load($product->getId());
                 $priceWithoutTax = $product->getPriceInfo()->getPrice('final_price')->getAmount()->getBaseAmount();
          //  $priceWithTax = $product->getFinalPrice();
            $priceWithTax = $this->taxHelper->getTaxPrice($product, $product->getFinalPrice(), true);
             //   $html = $this->getLayout()->createBlock('catalog/product_price')->setTemplate('catalog/product/fresh.phtml')->setProduct($_product)->setDisplayMinimalPrice(true)->setIdSuffix($idSuffix = 'amit')->toHtml();
             //   $productBlock = $this->getLayout()->createBlock('catalog/product_price')->setTemplate('catalog/product/fresh.phtml');
                //  $json[$_product->getSku()] = json_decode(str_replace('<span class=\"price\">', '', $html)); ///$productBlock->getPriceHtml($_product);
                // echo $productBlock->getPriceHtml($_product);
                $json[$product->getSku()]['ex_vat'] = $priceWithoutTax;
                $json[$product->getSku()]['inc_vat'] = $priceWithTax;

        }

        $json = json_encode($json);
       // $json = preg_replace("!\r?\n!", "", $json);
       echo $json;
exit;

       

    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->_jsonHelper->jsonEncode($response)
        );
    }


}