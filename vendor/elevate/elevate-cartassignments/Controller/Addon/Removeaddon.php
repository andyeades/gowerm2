<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Removeaddon extends \Magento\Checkout\Controller\Cart
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    protected $_sessionQuote;
    protected $_itemModel;
    protected $_cart;
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
        $this->_cart = $cart;
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
    public function execute() {



            $assignment_id = $this->getRequest()->getParam('assignment_id'); //this is the item to remove

            $assigned_item = $this->_cartAssignmentsModal->load($assignment_id);

$data = $assigned_item->getData();

            $linked_quote_item_id = $data['linked_quote_item_id'];

            $parent_quote_item_id = $data['parent_quote_item_id'];

            $qty = $assigned_item->getQty(); //HOW MANY ASSIGNED

            $result = [];

            try {


                //load the current item

                $childitem = $this->_cart->getQuote()->getItemById($linked_quote_item_id);
               // $childitem = $cart->getQuote()->getItemById($linked_quote_item_id);

                //remove product by assignment_id
                try {



                   $assigned_item->delete($assignment_id);

                    $curen = json_decode($childitem->getEvCartassignmentsData(), true);


                    unset($curen[$childitem->getId()]);
                    //$result = array_replace($return, $curen);

                    $childitem->setEvCartassignmentsData(json_encode($curen));
                    //$item->save();

                } catch(Exception $e) {
                    print_r($e->getMessage());
                }

                $current_qty = $childitem->getQty();
                $new_qty = $current_qty - $qty;
                //   echo $childitem->getItemId();
                //echo $childitem->getName();
                //         exit;
                // exit;
                if ($new_qty < 1) {

                    $this->_cart->removeItem($childitem->getItemId());
                } else {

                    $childitem->setQty($new_qty);
                    $childitem->save();
                }
              $this->_cart->save();


              //  $result['qty'] = $this->_getCart()->getSummaryQty();

                $result['success'] = 1;
                $result['redirect'] = true;
                $result['redirect_url'] = '/checkout/cart';
                $response['sidebar'] = '';
                $result['grand_total_inc_vat'] = '';
                $result['cart_quantity'] = '';

                $result['cart_totals'] = '';
                //   $cart_items = $this->getLayout()->getBlock('checkout.cart')->toHtml();
                $result['parent_id'] = $parent_quote_item_id;
                echo json_encode($result);
                exit;

                $quote = Mage::getSingleton('checkout/session')->getQuote();

                $quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates();

                // Collect Totals & Save Quote
                $quote->collectTotals()->save();
                $totalsBlock = $this->getLayout()->createBlock('checkout/cart_totals')->setTemplate('checkout/cart/totals.phtml');
                $totalsBlock = $totalsBlock->tohtml();
                $cart_sideblock = Mage::getStoreConfig('elevate_assignments/general/cart_sideblock');
                $cart_sideblock = 'ev_minicart';
                $sidebar_block = $this->getLayout()->getBlock($cart_sideblock);
                // $response['toplink'] = $toplink;
                if ($sidebar_block) {
                    $sidebar = $sidebar_block->toHtml();
                }
                $response['sidebar'] = $sidebar;
                $result['grand_total_inc_vat'] = Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());
                $result['cart_quantity'] = $this->_getCart()->getSummaryQty();

                $result['cart_totals'] = $totalsBlock;
                //   $cart_items = $this->getLayout()->getBlock('checkout.cart')->toHtml();
                $result['parent_id'] = $parent_quote_item_id;
                //$result['cart_items'] = $cart_items;

            } catch(Exception $e) {
                $result['success'] = 0;
                $result['error'] = $this->__('Can not save item.');
            }

            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

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