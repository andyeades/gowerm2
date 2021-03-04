<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Updateq extends \Magento\Checkout\Controller\Cart
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
    protected $cartAddonsModal;
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
        \Elevate\CartAssignments\Model\CartAssignments $cartAddonsModal,
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
        $this->_cartAddonsModal = $cartAddonsModal;
      

        $this->_localeDate = $localeDate;
        $this->_assignmentsMap = $assignmentsMap;

    }






    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {


        //here we update the qty using ajax

        //Parent Item ID - 1000595
        //e.g chair or bed main product cart quote Item ID
        $parent_id = $this->getRequest()->getParam('itemid');
        $new_qty = $this->getRequest()->getParam('qty'); //updated qty
        //$cart = Mage::getModel('checkout/cart');
       // $cart->init();

        /* Main Product Item Update - Start */
        //load by the quote item id - e.g bed or chair
      //  $item = $this->_itemModel->load($parent_id);
        $item = $this->cart->getQuote()->getItemById($parent_id);

       // $item = $cart->getQuote()->getItemById($parent_id);   //current item id i.e chair / desk / bed
        if (!$item) {
            return; //        Mage::throwException($this->__('Quote item is not found.'));
        }

        $assignment = $this->_cartAssignmentsModal->loadAssigned($parent_id);
        //find the current item, we should do this via id

        //$new_qty = $this->getRequest()->getParam('qty'); //updated qty
        $original_qty = $item->getQty();
        $assignment_qty = $assignment->getQty();

        $difference_qty = $new_qty - ($original_qty - $assignment_qty) ; //difference via ajax request fires on keyboard, so user can have added 2 extra, 2 minus for example if pressed button twice quickly


        $update_qty = $original_qty + $difference_qty;


        // echo "$update_qty";

        //lets change the main item qty for the linked addon
        if ($update_qty < 1) {
            $this->cart->removeItem($parent_id);
        } else {


            $item->setQty($update_qty)->save();

        }










        //lets figure out what to do with the addons / depends on quantity match types etc for each assignment





        $collection = $this->_cartAssignments->create()->getCollection()
                          ->addFieldToFilter('parent_quote_item_id', $parent_id);

        $total_addon_qty = 0;
        foreach($collection as $data) {

            $addon = $this->_cartAddonsModal->load($data->getData('addon_id'));



            if($addon->getData('enable_addon') == 1){

                $total_addon_qty = $total_addon_qty + $data['qty'];


                $match_enabled = $addon->getData('match_quantity');
                if ($match_enabled == '1') {


                    //echo "MATCH";
                    //exit;
                    //$update_child_qty = $new_qty;

                    //what should this new qty be?
                    //

                  //  $child_item = $this->_itemModel->load($parent_id);
                    $child_item = $this->cart->getQuote()->getItemById($parent_id);
                    $original_child_qty = $child_item->getQty(); //e.g child sku 5 qty
                    $original_assignments_qty = $data['qty'];


                    $original_assignments_qty_new = $new_qty;
                    $difference_qty_assign = $original_assignments_qty_new - $original_assignments_qty;

                    $original_child_qty_new = $original_child_qty + $difference_qty_assign;




                    $data->setQty($original_assignments_qty_new)->save();


                    //sets the pillows quantity
                    $child_item->setQty($original_child_qty_new);
                    $child_item->save();


                }

                // $new_qty = $total_addon_qty + $new_qty;
            }






        }


        /*End Update the Children Products*/
        $this->cart->save();


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $settingsHelper = $objectManager->get('Elevate\CartAssignments\Helper\Settings');
        $price_type = $settingsHelper->getPriceType();
        if($price_type == 'ex_vat'){

            $output_price = $item->getRowTotal();
        }
        else{
            $output_price = $item->getRowTotalInclTax();
        }

        $row_total = $output_price;

        $row_id = $item->getId();
        $response['row_total'] = $row_total;
        $response['row_id'] = $row_id;




        echo json_encode($response);

        exit;
        //New Code Here
        $this->loadLayout();
        Mage::app()->getLayout()->getUpdate()->addHandle('checkout_cart_index');

        //Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
        // $cart_items = $this->getLayout()->getBlock('checkout.cart')->toHtml();
        //$responssse['checkout_block'] = $checkout_page;
        $cart_sideblock = Mage::getStoreConfig('elevate_assignments/general/cart_sideblock');
        $cart_sideblock = 'ev_minicart';
        $sidebar_block = $this->getLayout()->getBlock($cart_sideblock);
        // $response['toplink'] = $toplink;
        if($sidebar_block){
            $sidebar = $sidebar_block->toHtml();
        }
        $response['sidebar'] = $sidebar;
        $response['grand_total_inc_vat'] = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());
        $response['cart_quantity'] =  $this->_getCart()->getSummaryQty();
        $price_type = Mage::getStoreConfig('elevate_assignments/general/price_type');

        if($price_type == 'ex_vat'){

            $output_price = $item->getRowTotal();
        }
        else{
            $output_price = $item->getRowTotalInclTax();
        }

        $row_total = $output_price;

        $row_id = $item->getId();
        $response['row_total'] = $row_total;
        $response['row_id'] = $row_id;

        // $response['cart_items'] = $cart_items;

        $grand_total_incvat = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());
        $subtotal_exvat = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getSubtotal());
        $shipping_amount = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getShippingAmount());
        $tax_amount = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal() - Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getShippingAmount() - Mage::getSingleton('checkout/cart')->getQuote()->getSubtotal());

        $total_data['subtotal_exvat'] = $subtotal_exvat;
        //$grand_total_ex_vat =  Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->getShippingAmount()Mage::getSingleton('checkout/cart')->getQuote()->getSubtotal());
        $total_data['grand_total_ex_vat'] = $grand_total_ex_vat;
        $total_data['subtotal_exvat'] = $subtotal_exvat;
        $total_data['shipping_amount'] = $shipping_amount;
        $total_data['tax_amount'] = $tax_amount;

        $response['grand_total_inc_vat'] = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());
        $response['cart_quantity'] =  $this->_getCart()->getSummaryQty();
        $totalsBlock = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml');
        $totalsBlock = $totalsBlock->tohtml();
        $response['cart_totals'] = $totalsBlock;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
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