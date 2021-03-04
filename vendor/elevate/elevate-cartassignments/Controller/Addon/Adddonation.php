<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Adddonation extends \Magento\Checkout\Controller\Cart
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



    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }



 





    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

             $params = $this->getRequest()->getParams();


        $donation_price = $this->getRequest()->getParam('amount');  //product to assign to
        $donation_type = $this->getRequest()->getParam('type'); //product to add on

 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

         $product = $this->productRepository->get('BORN-FREE-DONATION'); // 1291434
          
        $success = false;
        $messages = array();
                                       
   

      

        try {





        
            //$additional_product = $this->getRequest()->getParam('additional_product');
            // Check product availability
            if (!$product) {
              //  $this->_goBack();

                return;
            }

 

            $this->cart->addProduct($product, $params);
                    
            $item = $this->cart->getQuote()->getItemByProduct($product);
            $item->setCustomPrice($donation_price); // or some other value
            $item->setOriginalCustomPrice($donation_price); // or some other value
            $item->getProduct()->setIsSuperMode(true); // this is crucial

          
          

      
          $messages = "donation added";

            $this->cart->save();
            $success = true;

        } catch(Mage_Core_Exception $e) {

            array_push($messages, $e->getMessage());

        } catch(Exception $e) {

            $messages = array_unique(explode("\n", $e->getMessage()));
        }

        $response = array(
            'success' => $success,
            'message' => $messages,
        );

        if ($success) {
           // $this->loadLayout();
            // Populate the cart total and items count to return in the response
          //  $sidebar_block = $this->getLayout()->getBlock('ev_minicart_old');

            //					$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
            //        		$sidebar = $sidebar_block->toHtml();
          //  $sidebar = $sidebar_block->toHtml();

            // $response['toplink'] = $toplink;
          //  $response['sidebar'] = $sidebar;
        }

        echo json_encode($response);
       // $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));


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