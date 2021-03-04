<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Addaddon extends \Magento\Checkout\Controller\Cart
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    protected $_sessionQuote;
    protected $_itemModel;
    protected $_cartAssignments;
    protected $_cartAssignmentsModal;
    protected $_cartAddons;
    protected $assigned_addons;
    protected $has_assigned_html;
    protected $validation_errors = [];

    protected $_assignmentsMap;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param ProductRepositoryInterface $productRepository
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Quote\Model\Quote\Item $itemModel,
        \Elevate\CartAssignments\Model\QuoteItemAssignmentsFactory $cartAssignments,
        \Elevate\CartAssignments\Model\QuoteItemAssignments $cartAssignmentsModal,
        \Elevate\CartAssignments\Model\CartAssignmentsFactory $cartAddons,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Elevate\CartAssignments\Model\AssignmentsMap $assignmentsMap

    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->productRepository = $productRepository;
        $this->_sessionQuote = $sessionQuote;
        $this->_itemModel = $itemModel;
        $this->_cartAssignments = $cartAssignments;
        $this->_cartAssignmentsModal = $cartAssignmentsModal;
        $this->_cartAddons = $cartAddons;
      

        $this->_localeDate = $localeDate;
        $this->_assignmentsMap = $assignmentsMap;

    }



    private function getUKPostcodeFirstPart($postcode)
    {
        // validate input parameters
        $postcode = strtoupper($postcode);


        // UK mainland / Channel Islands (simplified version, since we do not require to validate it)
        if (preg_match('/^[A-Z]([A-Z]?\d(\d|[A-Z])?|\d[A-Z]?)\s*?\d[A-Z][A-Z]$/i', $postcode))
            return preg_replace('/^([A-Z]([A-Z]?\d(\d|[A-Z])?|\d[A-Z]?))\s*?(\d[A-Z][A-Z])$/i', '$1', $postcode);
        // British Forces
        if (preg_match('/^(BFPO)\s*?(\d{1,4})$/i', $postcode))
            return preg_replace('/^(BFPO)\s*?(\d{1,4})$/i', '$1', $postcode);
        // overseas territories
        if (preg_match('/^(ASCN|BBND|BIQQ|FIQQ|PCRN|SIQQ|STHL|TDCU|TKCA)\s*?(1ZZ)$/i', $postcode))
            return preg_replace('/^([A-Z]{4})\s*?(1ZZ)$/i', '$1', $postcode);

        // well ... even other form of postcode... return it as is


        return $postcode;
    }
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }



    public function addaddon() {



        $postcode_check = false;

        $product_id = $this->getRequest()->getParam('product_id');  //product to assign to
        $quote_item_id_parent = $this->getRequest()->getParam('quote_item_id_parent'); //product to add on

        $postcode = $this->getRequest()->getParam('pc'); //product to add on


        $short_postcode = $this->getUKPostcodeFirstPart($postcode);



        if($postcode_check){
            $postcode_arr = Mage::helper('elevate_assignments/postcode')->getPostcodeRules();
            if(array_key_exists($short_postcode, $postcode_arr)){
                $validate_skus = $postcode_arr[$short_postcode];
            }
            else{


                $response['status'] = 'ERROR';
                $response['message'] = $this->__('CANT ADD THIS PRODUCT - NOT ALLOWED FOR THE POSTCODE AREA');
              //  $this->getResponse()->setHeader('Content-type', 'application/json');
              //  $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

                return;


            }

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $customerSession = $objectManager->create('Magento\Customer\Model\Session');
            $customerSession->setPostcode($postcode);



        }


        //get parent
        /** @var $quote \Magento\Quote\Model\Quote */
        $quote = $this->_sessionQuote->getQuote();

        try {

            //get parent qty
            $quoteItemObject = $this->cart->getQuote()->getItemById($quote_item_id_parent);
            //$quoteItemObject = $this->_itemModel->load($quote_item_id_parent);
            $parent_qty = $quoteItemObject->getQty();

            //load the new addon product

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $mModel = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);



            $check_addon_sku = $mModel->getSku();

           // if(array_key_exists($check_addon_sku, $validate_skus)){

           //     $can_allow = $validate_skus[$check_addon_sku];
          //  }
/*
            if($can_allow == 0 && 1==2){

                $response['status'] = 'ERROR';
                $response['message'] = $this->__('CANT ADD THIS PRODUCT - NOT ALLOWED FOR THE POSTCODE AREA');
                print_r($response);
            //    $this->getResponse()->setHeader('Content-type', 'application/json');
            //    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                exit;
            }
*/
            if ($mModel->getTypeId() == 'simple') {



                $quoteItem2 = $this->cart->addProduct($mModel, $parent_qty);
               // $quoteItem2 = $quote->addProduct($mModel, $parent_qty);



                $item = $this->cart->getQuote()->getItemByProduct($mModel);
//                $item->getProduct()->setIsSuperMode(true); // this is crucial
                //get existing linked products
               //wouldnt have any straught away on an add
                $existing_links = $item->getEvCartassignmentsData();


               // $json_mapper = Mage::getModel('elevate_assignments/jsonmapper');
                try{

if(!$this->isJson($existing_links)){

    $existing_links = json_encode (json_decode ("{}"));
}

                   // $user = $this->_jsonMapper->map(json_decode($existing_links), $this->_assignmentsMap);
                }catch(Exception $e){

                   // $user = Mage::getModel('elevate_assignments/cartassignments');
                    //    print_r($e->getMessage());
                }



                //$jsonUpate = $user->getJson();

                $this->_assignmentsMap->addParent($quote_item_id_parent, $parent_qty);

                $jsonUpate = $user->getJson();



                $item->setEvCartassignmentsData($jsonUpate)->save();


                $this->cart->getQuote()->save();
                $this->cart->getQuote()->collectTotals()->save();

                //Grab the id from the new quote item
                $sub_id = $item->getId(); //$quoteItem2->getId();



            }




            //now we have assigned the product - we need to assign it as a child to the parrent
            $item = $this->cart->getQuote()->getItemById($quote_item_id_parent);
            $item->getProduct()->setIsSuperMode(true);

            $parent_purchase = $item->getEvCartassignmentsData();




            try{

                if(!$this->isJson($parent_purchase)){

                    $parent_purchase = json_encode (json_decode ("{}"));
                }


            }catch(Exception $e){

                // $user = Mage::getModel('elevate_assignments/cartassignments');
                //    print_r($e->getMessage());
            }




                try{

                //   $cartAssignmentChild = $this->_jsonMapper->map(json_decode($parent_purchase), $this->_assignmentsMap);

                }catch(Exception $e){

                    $cartAssignmentChild = $this->_assignmentsMap;

                }


                $cartAssignmentChild->addChild($sub_id);

                $jsonUpdate = $cartAssignmentChild->getJson();


            $item->setEvCartassignmentsData($jsonUpdate)->save();


            $this->cart->save();

            $response['status'] = 'SUCCESS';

            echo json_encode($response);

            exit;

            $this->_getSession()->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */

            $response['status'] = 'SUCCESS';
            $response['message'] = $message;
            $response['related'] = $relatedProducts;
            $response['redirect'] = true;
            $response['redirect_url'] = '/checkout/cart';

            // $cart_items = $this->getLayout()->getBlock('checkout.cart')->toHtml();

            //  $response['cart_items'] = $cart_items;

            $totalsBlock = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml');
            $totalsBlock = $totalsBlock->tohtml();
            $response['cart_totals'] = $totalsBlock;
        } catch(Mage_Core_Exception $e) {
            $msg = "";
            if ($this->_getSession()->getUseNotice(true)) {
                $msg = $e->getMessage();
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $msg = $message . '<br/>';
                }
            }

            $response['status'] = 'ERROR';
            $response['message'] = $msg;
        } catch(Exception $e) {
            $response['status'] = 'ERROR';
            $response['message'] = $this->__('Cannot add the item to shopping cart.');
            Mage::logException($e);
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

        return;
        //   $this->_redirect('checkout/cart');
    }







    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        //current addon promotion served to the customer
        $addon_id = $this->getRequest()->getParam('addon_id');
        $addon_location = $this->getRequest()->getParam('type'); //product to add on
        $addon_qty = $this->getRequest()->getParam('qty'); //product to add on
        $addon_product_id = $this->getRequest()->getParam('product_id'); //product to add on
        $quote_item_id_parent = $this->getRequest()->getParam('quote_item_id_parent'); //product to add on
        $postcode = $this->getRequest()->getParam('pc');

        if(!is_numeric($addon_qty)){

            $addon_qty = 1;
        }
        $assignmentsHelper = $objectManager->get('Elevate\CartAssignments\Helper\Assignments');

      //  $assignmentsHelper = $this->helper('Elevate\CartAssignments\Helper\Items');


        $response = $assignmentsHelper->assignAddon(
            $addon_id,
            $addon_location,
            $addon_qty,
            $addon_product_id,
            $quote_item_id_parent,
            $postcode
        );



        /// what to refresh here


      //  $cart_sideblock = Mage::getStoreConfig('elevate_assignments/general/cart_sideblock');
     //   $cart_sideblock = 'ev_minicart';
      //  $sidebar_block = $this->getLayout()->getBlock($cart_sideblock);

        // $response['toplink'] = $toplink;
      //  if($sidebar_block){
      //      $sidebar = $sidebar_block->toHtml();
      //  }
      //  $response['sidebar'] = $sidebar;
        //  Mage::getSingleton('checkout/cart')->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        // $new_quote = Mage::getModel('checkout/cart')->getQuote();
        // $new_quote->collectTotals();
        // $quote->collectTotals()
        $response['status'] = 'SUCCESS';

        echo json_encode($response);

        exit;
        exit;

        $quote = Mage::getSingleton('checkout/session')->getQuote();

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->unsetData('cached_items_all');
        $quote->getShippingAddress()->unsetData('cached_items_nominal');
        $quote->getShippingAddress()->unsetData('cached_items_nonnominal');


        $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates();


        // Collect Totals & Save Quote
        $quote->collectTotals()->save();

        $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates();


        // Collect Totals & Save Quote
        $quote->collectTotals()->save();

        $response['grand_total_inc_vat'] = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());
        //  $response['grand_total_ex_vat'] = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());

        $response['cart_quantity'] =  Mage::getSingleton('checkout/cart')->getSummaryQty();
        $totalsBlock = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml');
        $totalsBlock = $totalsBlock->tohtml();
        $response['cart_totals'] = $totalsBlock;

        echo json_decode($response);
        exit;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

        return;
        //   $this->_redirect('checkout/cart');

       // $this->addaddon();
        exit;


        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $quote = $cart->getQuote();
        $quote->setTotalsCollectedFlag(false);

        $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates();


        // Collect Totals & Save Quote
        $quote->collectTotals();
        $quote->save();

       /// $this->loadLayout();
      //  Mage::app()->getLayout()->getUpdate()->addHandle('checkout_cart_index');
        //   Mage::getSingleton('checkout/type_onepage')->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        /*$shippingAddress = $quote->getShippingAddress();
        if (!$shippingAddress->getShippingMethod()) {
            $shippingAddress->setCountryId('GB')->setShippingMethod('productmatrix_Standard')->save();
            $shippingAddress->save();
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $quote->save();
            Mage::getSingleton('checkout/cart')->save();

        }
*/

        $resultPage = $this->_resultPageFactory->create();
     //   $totalsBlock = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml');
      //  $totalsBlock = $totalsBlock->tohtml();

          $totalsBlock = $resultPage->getLayout()
                              ->createBlock('Magento\Checkout\Block\Cart\Totals')
                              ->setTemplate('Magento_Checkout::cart/totals.phtml')
                              //->setData('data',$data)
                              ->toHtml();
        $response['cart_totals'] = $totalsBlock;

        echo json_encode($response);




        exit;

        $itemData = [];
        $responseData = [];





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