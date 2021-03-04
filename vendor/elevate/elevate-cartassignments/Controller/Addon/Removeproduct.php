<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Removeproduct extends \Magento\Checkout\Controller\Cart
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







    //remove entire product
    public function removeproductAction() {


       // $this->loadLayout();
        $id = (int)$this->getRequest()->getParam('productid');


        $result = array();

        if ($id) {
            try {
                $cart = $this->cart;

                $quoteItem = $cart->getQuote()->getItemById($id);
                if (!$quoteItem) {

                    $result['success'] = 1;
                    $result['redirect'] = true;


                    echo(json_encode($result));
                    exit;
                    //    Mage::throwException(__('Quote item is not found.'));
                }
                $qty_to_remove = $quoteItem->getQty();
                //remove the children products first - so we dont leave orphaned products
                /*Update The Children Products*/
                //$existing_links = $quoteItem->getParentPurchase();
              //  $product_assignments = json_decode($existing_links);


               // $product_assignments = json_decode($quoteItem->getEvCartassignmentsData(), true);
                $collection = $this->_cartAssignments->create()->getCollection()
                                                     ->addFieldToFilter('parent_quote_item_id', $id);


                foreach ($collection AS $data) {




                    $quoteItemObject = $cart->getQuote()->getItemById($data['linked_quote_item_id']);


                    if (is_numeric($quoteItemObject->getItemId())) {



                        try{


                            $current_qty = $quoteItemObject->getQty();


                            $quoteItemObject->getProduct()->setIsSuperMode(true);
                         //   $quoteItemObject->delete($data['linked_quote_item_id']);
                            $new_qty = $current_qty - $qty_to_remove;
                            if ($new_qty < 1) {

                                $cart->removeItem($quoteItemObject->getId())->save();

                            } else {


                                $quoteItemObject->setQty($new_qty)->save();

                            }

                        }catch(Exception $e){

                            //$user = $this->_assignmentsMap;
                           print_r($e->getMessage());
                        }


                      //  $jsonUpate = $user->getJson();
                      //  $quoteItemObject->setEvCartassignmentsData($jsonUpate)->save();
                    }

                }


                /*End Update the Children Products*/


                if (!$quoteItem) {
                    echo "NO OBJECT";
                    exit;
                //    Mage::throwException(__('Quote item is not found.'));
                }
                //remove the main item
                $cart->removeItem($id);
                $cart->save();




                $result['success'] = 1;
                $result['redirect'] = true;


echo(json_encode($result));
exit;



                //New Code Here
                $this->loadLayout();
                $toplink = $this->getLayout()->getBlock('top.links')->toHtml();



                $sidebar_block = $this->getLayout()->getBlock('ev_minicart_old');
                Mage::register('referrer_url', $this->_getRefererUrl());
                //	$cart_block = $this->getLayout()->getBlock('cart_sidebar');
                //  $cart_items = $cart_block->toHtml();
                $sidebar = $sidebar_block->toHtml();
                //$cart_items = $this->getLayout()->getBlock('checkout_cart')->toHtml();
                //$cart_items_block = $this->getLayout()->getBlock('cart_items');
                //$cart_items = $cart_items_block->toHtml();
                // $response['toplink'] = $toplink;


                //The main cart items
                //  $cart_items = $this->getLayout()->getBlock('checkout/cart')->setTemplate('checkout/cart.phtml')->toHtml();
                //  $cart_items = $this->getLayout()->createBlock('checkout/cart')->setTemplate('checkout/cart.phtml')->toHtml();
                $cart_items = $this->getLayout()->getBlock('checkout.cart')->toHtml();
                //$response['checkout_block'] = $checkout_page;


                $result['sidebar'] = $sidebar;
                $result['totals'] = $total_html;
                $result['cart_items'] = $cart_items;
                // $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));


            } catch (Exception $e) {
                $result['success'] = 0;
                $result['error'] = $this->__('Can not save item.');
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

$this->removeproductAction();
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