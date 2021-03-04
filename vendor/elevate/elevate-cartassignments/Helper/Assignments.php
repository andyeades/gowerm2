<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */


namespace Elevate\CartAssignments\Helper;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;


class Assignments extends \Magento\Framework\App\Helper\AbstractHelper {
    protected $_coreSession;
    protected $_assetRepo;
    protected $_cartAssignments;
    protected $_ruleFactory;
    protected $_itemModel;
    /**
     * @var DateTime
     */
    protected $_cart;
    private $date;
    protected $assigned_addons = [];
    protected $_cartAddons;
    protected $_quoteItemAssignments;
    /**
     * @var TimezoneInterface
     */
    private $timezone;
    public function __construct(

        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        DateTime $date,
        TimezoneInterface $timezone,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory,
        \Elevate\CartAssignments\Model\QuoteItemAssignmentsFactory $cartAssignments,
        \Elevate\CartAssignments\Model\QuoteItemAssignments $quoteItemAssignments,
        \Elevate\CartAssignments\Model\CartAssignments $cartAddons,
        \Magento\Quote\Model\Quote\Item $itemModel,
        \Magento\Checkout\Model\Cart $cart

    ) {

        $this->_coreSession = $coreSession;
        $this->_assetRepo = $assetRepo;
        $this->date = $date;
        $this->_cartAssignments = $cartAssignments;
        $this->timezone = $timezone;
        $this->_cart = $cart;
        $this->_ruleFactory = $ruleFactory;
        $this->_cartAddons = $cartAddons;
        $this->_itemModel = $itemModel;
        $this->_quoteItemAssignments = $quoteItemAssignments;

    }

    function getUKPostcodeFirstPart($postcode) {
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

    public function assignAddon(            $addon_id,
                                            $addon_location,
                                            $addon_qty,
                                            $addon_product_id,
                                            $quote_item_id_parent,
                                            $postcode){


        if (!$addon_id) {

            return;
        }





        $addonObj = $this->_cartAddons->load($addon_id);
        $enable_postcode = $addonObj->getData('enable_postcode');




        if (!is_numeric($quote_item_id_parent)) {
            return;
        }

        //postcode is passed here to final check its ok to add this on
        $response['lookup_id'] = $quote_item_id_parent."_".$addon_id;
        $response['quote_item_id_parent'] = $quote_item_id_parent;
        $response['addon_id'] = $addon_id;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $item = $this->_cart->getQuote()->getItemById($quote_item_id_parent);

        $quote = $this->_cart->getQuote();

        //get current item
        //  $item = Mage::getModel('sales/quote_item')->load($quote_item_id_parent);
        // if(!$item){

        //  echo "EROR:QID:".$quote_item_id_parent;
        //  exit;
        // }

        $mModel = $objectManager->create('Magento\Catalog\Model\Product')->load($addon_product_id);

        //  $mModel = Mage::getModel('catalog/product')->load($addon_product_id);

        if ($enable_postcode == 1) {

            $short_postcode = $this->getUKPostcodeFirstPart($postcode);
            $postcodeHelper = $objectManager->get('Elevate\CartAssignments\Helper\Postcode');
           // $postcodeHelper = $this->helper('Elevate\CartAssignments\Helper\Items');
            $postcode_arr = $postcodeHelper->getPostcodeRules();



            $this->_coreSession->setPostcode($postcode);
            if (array_key_exists($short_postcode, $postcode_arr)) {
                $validate_skus = $postcode_arr[$short_postcode];
            } else {


                $response['status'] = 'ERROR';
                $response['message'] = __('CANT ADD THIS PRODUCT - NOT ALLOWED FOR THE POSTCODE AREA');
                // $this->getResponse()->setHeader('Content-type', 'application/json');
                //$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

                return;

            }

            if($validate_skus[$mModel->getSku()] != 1){
                $response['status'] = 'ERROR';
                $response['message'] = __('CANT ADD THIS PRODUCT - NOT ALLOWED FOR THE POSTCODE AREA');
                // $this->getResponse()->setHeader('Content-type', 'application/json');
                // $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

                return $response;

            }
        }

        // $addon_product_id
        /*End Postcode Stuff*/


        //[CHILD] - Assigned Items
        $collection = $this->_cartAssignments->create()->getCollection()
                                             ->addFieldToFilter('parent_quote_item_id', $quote_item_id_parent);


        foreach ($collection as $data) {

            /*
                 [assignment_id] => 5
                 [linked_quote_item_id] => 1000607
            location = top, bottom, trundle = etc
                 [parent_quote_item_id] => 1000606
                 [addon_id] => 1
                 [template_item_id] => 1
                 [created_at] => 2019-07-29 13:30:20
                 [updated_at] => 0000-00-00 00:00:00
                 [qty] => 4.0000
            */


            $this->assigned_addons[$data['parent_quote_item_id'] . "_" . $data['addon_id']][$data['location']][$data['linked_quote_item_id']] = array(
                'linked_quote_item_id' => $data['linked_quote_item_id'],
                'parent_quote_item_id' => $data['parent_quote_item_id'],
                'addon_id'             => $data['addon_id'],
                'qty'                  => $data['qty'],
                'assignment_id'        => $data['assignment_id'],
                'location'             => $data['location']

            );


        }

        $item_qty = $item->getQty();
        $assigned_addon_qty = 0;
        //$item_sku = $item->getProduct()->getSku();

        //always match parent if this setting is on
        $match_enabled = $addonObj->getData('match_quantity');
        if ($match_enabled == '1') {
            $addon_qty = $item_qty;
        }
        if (array_key_exists($quote_item_id_parent."_".$addon_id, $this->assigned_addons)) {

            foreach ($this->assigned_addons[$quote_item_id_parent . "_" . $addon_id] AS $location => $subcollection) {

                foreach ($subcollection as $linked_id => $addon) {
                    //  print_r($addon);
                    $assigned_addon_qty = $addon['qty'];
                }
            }
        }

        if($assigned_addon_qty >= $item_qty){
            // echo "REURN";
            // exit;
            /// if over - i guess we should really delete
            // return ;
        }
        /*
                $match_enabled = $addon->getData('match_quantity');
                $parent_qty = $item->getQty();

                if ($match_enabled == '1') {
                    $match_qty = $item->getQty();
                    $difference = $match_qty - $update_qty;

                    $update_qty + $difference;
                    $qty + $difference;
                }
        */
        /* validate is allowed */ //should be same validation rules as before built into a helper
        /*if(validateAddon){
                 $check_addon_sku = $mModel->getSku();

                           if(array_key_exists($check_addon_sku, $validate_skus)){

                            $can_allow = $validate_skus[$check_addon_sku];
                           }
        }
        else{
              $response['status'] = 'ERROR';
              $response['message'] = $this->__('CANT ADD THIS PRODUCT - NOT ALLOWED FOR THE POSTCODE AREA');
           $this->getResponse()->setHeader('Content-type', 'application/json');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
           exit;
        }
        */

        try {


            //echo $mModel->getName()."<br>";
            if ($mModel->getTypeId() == 'simple') {

                if(!is_numeric($addon_qty)){
                    $addon_qty = 1;
                }

                //add the new product into the standard basket
                try {
                    //   echo "ADD";
                    $quoteItem2 = $quote->addProduct($mModel, $addon_qty);
                }
                catch(Exception $e){
                    // echo "ADDON".$addon_qty;
                    print_r($e->getMessage());
                }
                $item = $quote->getItemByProduct($mModel);
                $item->getProduct()->setIsSuperMode(true); // this is crucial
                $quote_id = $quote->getId();

                $quote->getShippingAddress()->setCollectShippingRates(true);
                //assign this new product also

                //$item->setParentPurchase(json_encode($return));
                $quote->collectTotals();
                $quote->save();
                //Grab the id from the new quote item
                $linked_quote_item_id = $quoteItem2->getId();


                $return = $this->_quoteItemAssignments->assignProduct(
                    $quote_item_id_parent, $linked_quote_item_id, $quote_id, $addon_id, $addon_location, $addon_qty
                );

                //  $curen = json_decode($item->getEvCartassignmentsData());

                //$result = array_replace($return, $curen);

                // $item->setEvCartassignmentsData(json_encode($return));
                $item->save();
            }

            //   $cart->save();

            //Mage::getSingleton('checkout/session')->setCartWasUpdated(true);


            /**
             * @todo remove wishlist observer processAddToCart
             */

            $response['status'] = 'SUCCESS';

            $response['redirect'] = true;
            $response['redirect_url'] = '/checkout/cart';

            // $cart_items = $this->getLayout()->getBlock('checkout.cart')->toHtml();

            //  $response['cart_items'] = $cart_items;


        } catch(Mage_Core_Exception $e) {
            $msg = "";
            //  if ($this->_getSession()->getUseNotice(true)) {
            //      $msg = $e->getMessage();
            //  } else {
            $messages = array_unique(explode("\n", $e->getMessage()));
            foreach ($messages as $message) {
                $msg = $message . '<br/>';
            }
            // }

            $response['status'] = 'ERROR';
            $response['message'] = $msg;
        } catch(Exception $e) {
            $response['status'] = 'ERROR';
            $response['message'] = __('Cannot add the item to shopping cart.');
            // Mage::logException($e);
        }
        return $response;
    }



}
