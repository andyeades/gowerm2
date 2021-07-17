<?php

namespace Elevate\Framework\Controller\Price;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class FreshRelevanceAjaxPrice extends \Magento\Framework\App\Action\Action
{


    /**
     * @var Session
     */
    private $session;
    /**
     * @var StockItemRepository
     */

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
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
        $this->_jsonHelper = $jsonHelper;
        $this->_logger = $logger;
        $this->session = $session;
        $this->_productCollectionFactory = $productCollectionFactory; 
        $this->layoutFactory = $layoutFactory; 


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
    
    
    //route https://p2.posturite.co.uk/elevate/price/freshrelevanceajaxprice?ids=5383,6230,856,8753
    
    
        $json = [];                              
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$abstractProductBlock = $this->layoutFactory->create()->createBlock('Magento\Catalog\Block\Product\ListProduct');
  
                      //$this->layoutFactory->create()->createBlock('Block\Class\Here');
         $product_ids_array = explode(',', $this->getRequest()->getParam('ids'));
         $productCollection = $this->_productCollectionFactory->create();
         $productCollection->addAttributeToSelect('*')
                           ->addFieldToFilter('entity_id', array('in'=> $product_ids_array))
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
       
             //   $html = $this->getLayout()->createBlock('catalog/product_price')->setTemplate('catalog/product/fresh.phtml')->setProduct($_product)->setDisplayMinimalPrice(true)->setIdSuffix($idSuffix = 'amit')->toHtml();
             //   $productBlock = $this->getLayout()->createBlock('catalog/product_price')->setTemplate('catalog/product/fresh.phtml');
                //  $json[$_product->getSku()] = json_decode(str_replace('<span class=\"price\">', '', $html)); ///$productBlock->getPriceHtml($_product);
                // echo $productBlock->getPriceHtml($_product);
                $json[$product->getId()] = $this->getPrice($product);
          

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