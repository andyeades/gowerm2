<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class Get extends \Magento\Framework\App\Action\Action
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
        \Magento\Customer\Model\Session $session
    ) {
        parent::__construct($context);
        $this->_jsonHelper = $jsonHelper;
        $this->_logger = $logger;
        $this->session = $session;


    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {


        $itemData = [];
        $responseData = [];




        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');

        // get quote items collection
       // $itemsCollection = $cart->getQuote()->getItemsCollection();
        $hasBasket = false;
        $subTotal = 0;
        $totalItems = 0;
        $grandTotal= 0;
        $totalQuantity= 0;
        // get array of all items what can be display directly
        $itemsVisible = $cart->getQuote()->getAllVisibleItems();
        $itemData = [];
        // get quote items array
      //  $items = $cart->getQuote()->getAllItems();
        //todo get product object $product

        $objectManager =\Magento\Framework\App\ObjectManager::getInstance();
        $helperImport = $objectManager->get('\Magento\Catalog\Helper\Image');


        $totalItems = (int)$cart->getQuote()->getItemsCount();

        if($totalItems > 0){
            $hasBasket = true;
        foreach($itemsVisible as $item) {
            $product = $item->getProduct();
            $imageUrl = $helperImport->init($product, 'product_page_image_small')
                                     ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
                                     ->resize(380)
                                     ->getUrl();
            $itemData[] = [
                'id' => $item->getProductId(),
                'image' => $imageUrl,
                'name' => $item->getName(),
                'sku' => $item->getSku(),
                'qty' => $item->getQty(),
                'price' => $item->getPrice()
            ];
        }

        $totalQuantity = $cart->getQuote()->getItemsQty();
        $subTotal = $cart->getQuote()->getSubtotal();
        $grandTotal = $cart->getQuote()->getGrandTotal();

        }

//if we have requirement - we can also get the billing details
      //  $billingAddress = $cart->getQuote()->getBillingAddress();
      //  echo '<pre>'; print_r($billingAddress->getData()); echo '</pre>';

      //  $shippingAddress = $cart->getQuote()->getShippingAddress();
      //  echo '<pre>'; print_r($shippingAddress->getData()); echo '</pre>';

        $responseData = [
            'errors' => false,
            'has_basket' => $hasBasket,
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
            'sub_total' => $subTotal,
            'grand_total' => $grandTotal,
            'item_data' => $itemData
        ];
        echo json_encode($responseData);


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