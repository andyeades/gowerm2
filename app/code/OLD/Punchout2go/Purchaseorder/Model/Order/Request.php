<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Punchout2go\Purchaseorder\Model\Order;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\DataObject;
use Magento\Framework\Webapi\Exception;
use Magento\Sales\Model\Service\OrderService;
//use Symfony\Component\Config\Definition\Exception\Exception;

class Request
    extends \Magento\Framework\DataObject
{

    /** @var \Punchout2go\Purchaseorder\Helper\Data  */
    protected $_helper;

    /** @var \Magento\Customer\Model\Customer */
    protected $_customer;

    /** @var \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest */
    protected $_document;

    /** @var StdObj */
    protected $_source;

    /** @var array */
    protected $_tempOrder;

    /** @var \Magento\Framework\Event\Manager */
    protected $_eventManager;

    /** @var \Magento\Catalog\Model\ProductFactory */
    protected $_productFactory;

    /** @var \Magento\Customer\Model\CustomerFactory */
    protected $_customerFactory;

    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    protected $_productRepositoryInterface;

    /** @var \Magento\Catalog\Helper\Product */
    protected $_productHelper;

    /** @var \Magento\Store\Api\StoreRepositoryInterface */
    protected $_storeRepository;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface */
    protected $_customerRepository;

    /** @var \Magento\Sales\Model\Service\OrderService */
    protected $_orderService;

    /** @var \Magento\Store\Model\Store */
    protected $_store;

    /** @var \Magento\Framework\Registry */
    protected $registry;

    /** @var \Punchout2go\Purchaseorder\Model\Order\Request\ItemsFactory */
    protected $_itemsFactory;

    /** @var \Magento\Framework\DataObject */
    protected $_dataObject;

    /** @var \Punchout2go\Purchaseorder\Model\Order\Admin\Create */
    protected $_createOrder;

    protected $_applyProvidedShipping;
    protected $_orderShippingPrice;
    protected $_orderTax;

    protected $_objectManager;
    protected $_coreRegistry;
    protected $_salesConfig;
    protected $_quoteSession;
    protected $_logger;
    protected $_objectCopyService;
    protected $_messageManager;
    protected $_quoteInitializer;
    protected $_addressRepository;
    protected $_addressFactory;
    protected $_metadataFormFactory;
    protected $_groupRepository;
    protected $_scopeConfig;
    protected $_emailSender;
    protected $_stockRegistry;
    protected $_quoteItemUpdater;
    protected $_objectFactory;
    protected $_quoteRepository;
    protected $_accountManagement;
    protected $_customerInterfaceFactory;
    protected $_customerMapper;
    protected $_quoteManagement;
    protected $_dataObjectHelper;
    protected $_orderManagement;
    protected $_quoteFactory;
    protected $_searchCriteriaBuilder;
    protected $_orderItemRepository;
    protected $appState;

    /**
     * Request constructor.
     * @param \Magento\Framework\Event\Manager $eventManager
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Sales\Model\Service\OrderService $orderService
     * @param \Magento\Framework\Registry $registry
     * @param \Punchout2go\Purchaseorder\Model\Order\Request\ItemsFactory $itemsFactory
     * @param \Magento\Framework\DataObject $dataObject
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Sales\Model\Config $salesConfig
     * @param \Magento\Backend\Model\Session\Quote $quoteSession
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Sales\Model\AdminOrder\Product\Quote\Initializer $quoteInitializer
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory
     * @param \Magento\Customer\Model\Metadata\FormFactory $metadataFormFactory
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Sales\Model\AdminOrder\EmailSender $emailSender
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Quote\Model\Quote\Item\Updater $quoteItemUpdater
     * @param \Magento\Framework\DataObject\Factory $objectFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagement
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory
     * @param \Magento\Customer\Model\Customer\Mapper $customerMapper
     * @param \Magento\Quote\Api\CartManagementInterface $quoteManagement
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Sales\Api\OrderManagementInterface $orderManagement
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository
     * @param \Magento\Framework\App\State $appState
     * @param \Punchout2go\Purchaseorder\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\Event\Manager $eventManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService,
        \Magento\Framework\Registry $registry,
        \Punchout2go\Purchaseorder\Model\Order\Request\ItemsFactory $itemsFactory,
        \Magento\Framework\DataObject $dataObject,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Sales\Model\Config $salesConfig,
        \Magento\Backend\Model\Session\Quote $quoteSession,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Sales\Model\AdminOrder\Product\Quote\Initializer $quoteInitializer,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory,
        \Magento\Customer\Model\Metadata\FormFactory $metadataFormFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\AdminOrder\EmailSender $emailSender,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Quote\Model\Quote\Item\Updater $quoteItemUpdater,
        \Magento\Framework\DataObject\Factory $objectFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerInterfaceFactory,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Sales\Api\OrderItemRepositoryInterface $orderItemRepository,
        \Magento\Framework\App\State $appState,
        \Punchout2go\Purchaseorder\Helper\Data $helper
    ){
        $this->_eventManager = $eventManager;
        $this->_productFactory = $productFactory;
        $this->_customerFactory = $customerFactory;
        $this->_productRepositoryInterface = $productRepositoryInterface;
        $this->_productHelper = $productHelper;
        $this->_storeRepository = $storeRepository;
        $this->_customerRepository = $customerRepository;
        $this->_orderService = $orderService;
        $this->_registry = $registry;
        $this->_itemsFactory = $itemsFactory;
        $this->_dataObject = $dataObject;
        $this->_objectManager = $objectManager;
        $this->_coreRegistry = $coreRegistry;
        $this->_salesConfig = $salesConfig;
        $this->_quoteSession = $quoteSession;
        $this->_logger = $logger;
        $this->_objectCopyService = $objectCopyService;
        $this->_messageManager = $messageManager;
        $this->_quoteInitializer = $quoteInitializer;
        $this->_addressRepository = $addressRepository;
        $this->_addressFactory = $addressFactory;
        $this->_metadataFormFactory = $metadataFormFactory;
        $this->_groupRepository = $groupRepository;
        $this->_scopeConfig = $scopeConfig;
        $this->_emailSender = $emailSender;
        $this->_stockRegistry = $stockRegistry;
        $this->_quoteItemUpdater = $quoteItemUpdater;
        $this->_objectFactory = $objectFactory;
        $this->_quoteRepository = $quoteRepository;
        $this->_accountManagement = $accountManagement;
        $this->_customerInterfaceFactory = $customerInterfaceFactory;
        $this->_customerMapper = $customerMapper;
        $this->_quoteManagement = $quoteManagement;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->_orderManagement = $orderManagement;
        $this->_quoteFactory = $quoteFactory;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_orderItemRepository = $orderItemRepository;
        $this->appState = $appState;

        $this->_customer = null;
        $this->_store = null;

        $this->_helper = $helper;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        /** @var \Punchout2go\Purchaseorder\Model\Order\Request\Json $document */
        $document = $this->_document;
        $source = $document->getJson();

        $exceptionMessages = array();

        if (property_exists($source, 'mode')) {
            if (!in_array($source->mode, array('test', 'development', 'production'))) {
                $exceptionMessages[] = 'Mode is not valid';
            }
        } else {
            $exceptionMessages[] = 'Mode not found';
        }
        if (property_exists($source, 'header')) {
            // more here
        } else {
            $exceptionMessages[] = 'Header data not found';
        }
        if (property_exists($source, 'details')) {
            // more here
        } else {
            $exceptionMessages[] = 'Details data not found';
        }
        if (property_exists($source, 'items')) {
            $items = $source->items;
            foreach ($items as $itemCounter => $item) {
                if (property_exists($item, 'supplier_aux_id')) {
                    $splitAuxId = explode('/', $item->supplier_aux_id);
                    if (count($splitAuxId) == 2) {
                        if (!is_numeric($splitAuxId[0])
                            || !is_numeric($splitAuxId[1])) {
                            $exceptionMessages[] = 'invalid supplier_aux_id, ' .
                                'non-numeric values: ' .
                                $item->supplier_aux_id;
                        }
                    } else {
                        $exceptionMessages[] = 'invalid supplier_aux_id: ' .
                            $item->supplier_aux_id;
                    }
                } else {
                    $exceptionMessages[] = 'supplier_aux_id missing from item ' .
                        ($itemCounter + 1);
                }
            }
        } else {
            $exceptionMessages[] = 'Items data not found';
        }

        if (count($exceptionMessages) > 0) {
            throw new \Exception(implode(', ', $exceptionMessages), 100);
        }

        return true;
    }

    /**
     * create an order from this request.
     *
     * @return mixed
     */
    public function createOrder()
    {
        $helper = $this->_helper;

        $helper->debug('Creating order');

        try {
            /** @var \Punchout2go\Purchaseorder\Model\Order\Admin\Create $createOrder */
            $createOrder = $this->getCreateOrder();

            if (!$helper->getConfigFlag('punchout2go_purchaseorder/orders/allow_reorder')) {
                $exceptionMessages = [];
                $allItems = $this->_createOrder->getQuote()->getAllVisibleItems();
                foreach ($allItems as $item) {
                    $searchCriteria = $this->_searchCriteriaBuilder
                        ->addFilter('quote_item_id', $item->getId(), 'eq')
                        ->create();

                    /** @var \Magento\Quote\Model\Quote\Item $orderItem */
                    $orderItems = $this->_orderItemRepository->getList($searchCriteria);
                    if (count($orderItems) > 0) {
                        $exceptionMessages[] = "quote item {$item->getId()} " .
                            "({$item->getProduct()->getName()})";
                    }
                }
                if (count($exceptionMessages) > 0) {
                    throw new \Exception(
                        "The following item(s) were already ordered:\n" .
                        implode(", ", $exceptionMessages)
                    );
                }
            }
            $helper->debug('Dispatching procurement_orderrequest_order_setup');
            $this->_eventManager->dispatch('procurement_orderrequest_order_setup', [
                'document' => $this->getDocument(),
                'create_order' => $createOrder
            ]);

        } catch (\Exception $e) {
            $message = "Setup Order Exception : " . $e->getMessage();
            throw new \Exception($message);
        }

        /**
         * ORDER CONTENT SECTION
         */
        try {

            // billing address
            $helper->debug('Adding Billing Address');
            $this->setupAddress('billing');

            // shipping address
            $helper->debug('Adding Shipping Address');
            $this->setupAddress('shipping');

            // Add the items if not direct load.
            $helper->debug('Adding Items');
            $this->setupOrderItems();

            // Shipping
            $helper->debug('Adding Shipping Method');
            $method = $this->setupShippingMethod();
            $helper->debug('Shipping Method : ' . $method);

            // BP: skipping discount

            $helper->debug("Adding Payment");
            $this->setupPaymentMethod();

            $createOrder->recollectCart();

            // BP: skipping custom order mapping

            $helper->debug('Dispatching procurement_orderrequest_order_ready');
            $this->_eventManager->dispatch('procurement_orderrequest_order_ready', array(
                'document' => $this->getDocument(),
                'create_order' => $createOrder
            ));

        } catch (\Exception $e) {
            $message = "Populate Order Exception : " . $e->getMessage();
            throw new \Exception($message);
        }

        /**
         * ORDER CREATE SECTION
         */
        try {
            $createOrder->getQuote()->getItemsCount();
            $helper->debug("Final Recollect Cart");
            $createOrder->recollectCart();
            $helper->debug("Save Quote");
            $createOrder->saveQuote();
            $helper->debug("Dispatching procurement_orderrequest_order_quote_saved");
            $this->_eventManager->dispatch("procurement_orderrequest_order_quote_saved", [
                'document' => $this->getDocument(),
                'create_order' => $createOrder
            ]);

            // set send confirmation if based on config
            $notifyCustomer = $helper->getConfigFlag('punchout2go_purchaseorder/orders/notify_customer');
            if ($notifyCustomer) {
                $helper->debug("Setting to send confirmation {$notifyCustomer}");
                $createOrder->setSendConfirmation(true);
            } else {
                $helper->debug("Not setting to send confirmation.");
            }

            $helper->debug("Creating order from quote");
            /** @var \Magento\Sales\Model\Order $order */
            $order = $createOrder->createOrder();

            // if apply_provided_tax is set to Yes,
            // look for the shipping code in the order request document
            $applyProvidedTax = $helper->getConfigFlag('punchout2go_purchaseorder/orders/apply_provided_tax');
            if (true == $applyProvidedTax) {
                $this->_orderTax = $this->getDocument()->getTax();
                $helper->debug("apply_provided_tax set to true; using header-level tax {$this->_orderTax}");

                // apply tax at the order level only; remove tax from item level
                $items = $order->getAllItems();
                foreach ($items as $item) {
                    $item->setTaxAmount(0);
                    $item->setTaxPercent(0);
                }

                $order->setTaxAmount($this->_orderTax);
                $order->setBaseTaxAmount($this->_orderTax);
            }

            if (true == $this->_applyProvidedShipping) {
                $helper->debug("force apply provided shipping : {$this->_orderShippingPrice}");
                $order->setShippingAmount($this->_orderShippingPrice);
                $order->setBaseShippingAmount($this->_orderShippingPrice);
            }

            $order->setGrandTotal($order->getSubtotal() + $order->getShippingAmount() + $order->getTaxAmount());
            $order->setBaseGrandTotal($order->getBaseSubtotal() + $order->getBaseShippingAmount() + $order->getBaseTaxAmount());

            // place the order
            $order = $this->_orderService->place($order);
            $realOrderId = $order->getRealOrderId();
            $helper->debug("Order placed; ID = {$realOrderId}");

            // follow up, make sure the status is correct.
            if ($helper->setNewPurchaseOrderStatus($order, $this->getCreateOrder()->getQuote())) {
                /** @var \Magento\Sales\Model\Order $order */
                $helper->debug("new purchase order status set successfully");
                //return $realOrderId;
            }

            $realOrderId = $order->getRealOrderId();
            //return $realOrderId;

        } catch (\Exception $e) {
            $helper->debug("Order creation error : {$e->getMessage()}");
            throw $e;
        }

        /**
         * POST-ORDER CREATE SECTION
         */
        try {
            $helper->debug("Dispatch procurement_orderrequest_order_created");
            $this->_eventManager->dispatch("procurement_orderrequest_order_created", [
                'document' => $this->getDocument(),
                'order' => $order,
                'create_order' => $createOrder
            ]);
        } catch (\Exception $e) {
            $helper->debug("Post Create Order Exception : " . $e->getMessage());
            throw $e;
        }

        return $order;
    }

    /**
     * @param string $addressType
     * @return $this|null
     */
    protected function setupAddress($addressType = 'billing')
    {
        $helper = $this->_helper;
        $helper->debug("addressType : {$addressType}");
        if (!in_array($addressType, array('billing', 'shipping'))) {
            return null;
        }

        $getMethod = "getBillToAddress";
        $setMethod = "setBillingAddress";
        if ($addressType == 'shipping') {
            $getMethod = "getShipToAddress";
            $setMethod = "setShippingAddress";
        }

        $helper->debug("calling this->getDocument()->{$getMethod}()");
        $address = $this->getDocument()->{$getMethod}();
        $helper->debug("address : " . print_r($address, true));
        $default = $helper->getConfig("punchout2go_purchaseorder/default_{$addressType}_address");
        $helper->debug("default : " . print_r($default, true));
        $result = new \Magento\Framework\DataObject();
        $result->setAddress($address);
        $result->setDefault($default);

        // dispatches orderrequest_setup_billing or orderrequest_setup_shipping
        $helper->debug("dispatching orderrequest_setup_{$addressType}");
        $this->_eventManager->dispatch("orderrequest_setup_{$addressType}", [
            'result' => $result,
            'request' => $this
        ]);
        $address = $result->getAddress();
        $this->_helper->debug("post-merge {$addressType} address : " . print_r($address, true));
        $this->getCreateOrder()->{$setMethod}($address);
        return $this;
    }

    /**
     * @return $this
     */
    protected function setupOrderItems()
    {
        $helper = $this->_helper;

        /** @var \Punchout2go\Purchaseorder\Model\Order\Request\Items $outItems */
        $outItems = $this->getDocument()->getItems();

        foreach ($outItems as $outItem) {
            $helper->debug('++Adding');
            /** @var \Magento\Quote\Model\Quote\Item $item */
            $item = $this->addOutItemToCreate($outItem);
            if ($item) {
                $helper->debug('--Added : '. $item->getProduct()->getName() ." (LineNumber - ". $item->getLineNumber() .")");
            } else {
                $helper->debug('--Not Added! Possibly Disabled...');
            }
            $helper->debug('ensuring store ID is correct based on an item : ' . $item->getStoreId());
            $this->getCreateOrder()->getQuote()->setStoreId($item->getStoreId());
            $helper->debug('collecting totals on quote');
            $this->getCreateOrder()->getQuote()->collectTotals();
            $this->getCreateOrder()->setRecollect(true);
        }

        $helper->debug("Process weights and remove unlined items.");
        $items = $this->getCreateOrder()->getQuote()->getItemsCollection();

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($items as $item) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $item->getProduct();
            if (null == $item->getWeight()) {
                $helper->debug("Updating weight to {$product->getWeight()}");
                $item->setWeight($product->getWeight());
                $item->getProduct()->setWeight($product->getWeight());
                $item->save(); // BP: save is deprecated
                if ($item->getParentItem()) {
                    $currentWeight = $item->getParentItem()->getWeight();
                    $item->getParentItem()->setWeight($currentWeight + $product->getWeight());
                    $item->getParentItem()->save(); // BP: save is deprecated
                }
            }
            $helper->debug("Weight for sku {$item->getSku()} : {$item->getWeight()}");
        }

        // remove any unused items from direct entry
        if ($this->getCreateOrder()->getDirectLoad()) {
            $helper->debug("Direct load... check for items removed from the PO.");
            $items = $this->getCreateOrder()->getQuote()->getAllVisibleItems();
            /** @var \Magento\Quote\Model\Quote\Item $item */
            foreach ($items as $item) {
                if (null == $item->getLineNumber()) {
                    $helper->debug("Direct load... removed item {$item->getName()} ({$item->getId()})");
                    $this->getCreateOrder()->getQuote()->removeItem($item->getId());
                }
            }
        }

        if (false == $helper->getConfigFlag('punchout2go_purchaseorders/orders/allow_item_removals')) {
            $helper->debug("Testing item removal restriction");
            $originalQuoteId = $this->getDocument()->getQuoteId();
            if (empty($originalQuoteId)) {
                $helper->debug("Multi-session quote cannot evaluate removals.");
            }
        }

        return $this;
    }

    /**
     * @param \Punchout2go\Purchaseorder\Model\Order\Request\Items\Item\AbstractItem $outItem
     * @return Mage_Sales_Model_Quote_Item
     */
    public function addOutItemToCreate($outItem)
    {
        $helper = $this->_helper;

        /** @var \Magento\Quote\Model\Quote\Item $item */
        $item = null;

        /** @var array $normalItem */
        $normalItem = $this->getDocument()->readKeyItemData($outItem);

        $itemId = null;
        $quoteId = null;
        $sku = $normalItem->getPartId();
        $productId = $normalItem['internal_reference_id'];
        $itemKey = $normalItem['internal_reference_id'];
        $qty = $normalItem['quantity'];
        if (preg_match("/^([^\/]+)\/([^\/]+)$/",$itemKey,$s)) {
            $quoteId = $s[1];
            $itemId = $s[2];
        }

        if ($this->getCreateOrder()->getDirectLoad()) {
            $helper->debug("Matching original item with {$sku} ({$itemKey}) x {$qty}");
            if (!empty($itemId)) {
                $helper->debug("getting item {$itemId}");
                /** @var \Magento\Quote\Model\Quote\Item $item */
                $item = $this->getCreateOrder()->getQuote()->getItemById($itemId);
                if (!empty($item)) {
                    $helper->debug("Matched {$item->getSku()}");
                    /** @var \Magento\Catalog\Model\Product $product */
                    $product = $item->getProduct();
                    if (!$product->isAvailable()) {
                        $helper->debug('product is no-longer available : '. $item->getName());
                        if ($helper->getConfigFlag('punchout2go_purchaseorder/orders/check_availability')) {
                            throw new \Exception(
                                "Product is not available : {$item->getName()} {$item->getSku()}"
                            );
                        } else {
                            $helper->debug('Trying to keep.');
                            $item->isDeleted(false);
                        }
                    }
                    if ($item->getQty() != $normalItem['quantity']) {
                        $helper->debug("non-matching quantity {$item->getQty()} to po qty {$normalItem->getQuantity()}");
                        if ($helper->getConfigFlag('punchout2go_purchaseorder/orders/allow_qty_edits')) {
                            // if is allowed, then update the quantity
                            $helper->debug("modifying quantity from {$item->getQty()} to {$normalItem['quantity']}");
                            $item->setQty($normalItem['quantity']);
                        } else {
                            throw new \Exception(
                                "Line item qty modification on {$item->getName()} {$item->getSku()}"
                            );
                        }
                    }
                } else {
                    $helper->debug("Could not match existing line item from id");
                }
            } else {
                $helper->debug("Unable to extract an item id from {$itemKey}");
            }
        }

        // if item is not established, try creating
        if (empty($item)) {
            $requestData = null;
            if (!empty($quoteId)
                && !empty($itemId)
                && $quoteId != $this->getCreateOrder()->getQuote()->getId()) {
                $helper->debug('looking for buyer request for '. $itemId);
                $buyerRequest = $this->findBuyerRequestFromOtherOrder($quoteId,$itemId);
                if (!empty($buyerRequest)) {
                    // check if data is in simple JSON first...
                    $requestData = json_decode($buyerRequest, true);
                    // then check if data is in slash-escaped JSON
                    if (is_null($requestData)) {
                        $requestData = json_decode(stripslashes($buyerRequest), true);
                    }
                    // finally, try unserializing and converting to an array
                    if (is_null($requestData)) {
                        $requestData = (array) unserialize($buyerRequest);
                    }
                    unset($requestData['uenc']);
                    $product = $requestData['product'];
                    $requestData['qty'] = $qty;
                    $helper->debug('found request data : '. json_encode($requestData));
                } else {
                    $helper->debug('no request data found.');
                }
            }
            if (empty($requestData)) {
                $helper->debug("Adding basic item {$sku} ($productId} x {$qty}");
                /** @var \Magento\Catalog\Model\Product $product */
                $product = $this->lookupProductByIdOrSku($productId, $sku);
            }
            $item = $this->addBasicProduct($product, (!empty($requestData) ? $requestData : $qty));
        }

        if ($item) {
            $helper->debug("Setting line number : {$normalItem['line_number']}");
            if (null != $item->getLineNumber()) {
                if ($item->getLineNumber() == $normalItem['line_number']) {
                    $helper->debug("Line number already set : {$item->getLineNumber()}");
                } else {
                    $helper->debug("Updating line number mis-match : {$item->getLineNumber()}");
                    $item->setLineNumber($normalItem['line_number']);
                }
            } else {
                $item->setLineNumber($normalItem['line_number']);
            }

            // BP: adding line number, PO number, and request ID to item additional data for M2
            /** @var \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest $document */
            $document = $this->getDocument();
            /** @var string $poNumber */
            $poNumber = $document->getPoNumber();
            /** @var string $requestId */
            $requestId = $document->getRequestId();
            /*try {
                $additionalData = (array) unserialize($item->getAdditionalData());
            } catch (\Exception $e) {
                $additionalData = array();
            }*/

            // check if data is in simple JSON first...
            $additionalData = json_decode($item->getAdditionalData(), true);
            // then check if data is in slash-escaped JSON
            if (is_null($additionalData)) {
                $additionalData = json_decode(stripslashes($item->getAdditionalData()), true);
            }
            // finally, try unserializing and converting to an array
            if (is_null($additionalData)) {
                $additionalData = (array) unserialize($item->getAdditionalData());
            }

            $additionalData['line_number'] = $normalItem['line_number'];
            $additionalData['po_number'] = $poNumber;
            $additionalData['request_id'] = $requestId;
            $item->setAdditionalData(serialize($additionalData));

            $item->setCustomPrice($normalItem['unit_price']);
            $item->setOriginalCustomPrice($normalItem['unit_price']);
            $item->setNoDiscount(true);
            $item->checkData();
            //$item->calcRowTotal();

            // TODO: implement and call $this->addCustomMappedLineItemData()
            // $helper->debug("Add custom mapped data {$item->getSku()}");
            // $this->addCustomMappedLineItemData($item,$normalItem,$outItem);

            // dispatch item setup, use for un-stashings.
            $helper->debug('Dispatching procurement_orderrequest_item_setup');
            $this->_eventManager->dispatch('procurement_orderrequest_item_setup',array (
                'item' => $item,
                'out_item' => $outItem,
                'normal_item' => $normalItem
            ));

            // save item in case any changes were made
            $item->save(); // BP: save is deprecated
        }

        return $item;
    }

    /**
     * get the info_buyRequest used to add an item to the cart.
     * (note, this will not retail cart level item collection)
     *
     * @param $quoteId
     * @param $itemId
     * @return mixed|null
     */
    protected function findBuyerRequestFromOtherOrder ($quoteId,$itemId)
    {
        $helper = $this->_helper;
        $searchCriteria = $this->_searchCriteriaBuilder
            ->addFilter('main_table.entity_id', $quoteId, 'eq')
            ->create();
        /** @var \Magento\Framework\Api\SearchResults $quoteList */
        $quoteList = $this->_quoteRepository->getList($searchCriteria);
        if ($quoteList->getTotalCount() == 1) {
            $itemsArr = $quoteList->getItems();
            if (isset($itemsArr[$quoteId])) {
                /** @var \Magento\Quote\Model\Quote $quoteObj */
                $quoteObj = $itemsArr[$quoteId];
                /** @var \Magento\Quote\Model\Quote\Item $itemObj */
                $itemObj = $quoteObj->getItemById($itemId);
                if ($itemObj instanceof \Magento\Quote\Model\Quote\Item) {
                    /** @var \Magento\Quote\Model\Quote\Item\Option $buyerRequest */
                    $buyerRequest = $itemObj->getOptionByCode('info_buyRequest');
                    if (!empty($buyerRequest)) {
                        $request = $buyerRequest->getValue();

                        // check if data is in simple JSON first...
                        $requestData = json_decode($request, true);
                        // then check if data is in slash-escaped JSON
                        if (is_null($requestData)) {
                            $requestData = json_decode(stripslashes($request), true);
                        }
                        // finally, try unserializing and converting to an array
                        if (is_null($requestData)) {
                            $requestData = (array) unserialize($request);
                        }

                        $helper->debug('adding item store ID to requestData : ' . $itemObj->getStoreId());
                        $requestData['store_id'] = $itemObj->getStoreId();
                        $helper->debug('adding item price to requestData : ' . $itemObj->getPrice());
                        $requestData['price'] = $itemObj->getPrice();
                        $helper->debug('adding item base price to requestData : ' . $itemObj->getBasePrice());
                        $requestData['base_price'] = $itemObj->getBasePrice();
                        $helper->debug('adding item tax_percent to requestData : ' . $itemObj->getBasePrice());
                        $requestData['tax_percent'] = $itemObj->getTaxPercent();

                        $request = json_encode($requestData);

                        return $request;
                    }
                }
            }
        }
        return null;
    }
    /**
     * @return \Punchout2go\Purchaseorder\Model\Order\Admin\Create
     */
    public function getCreateOrder()
    {
        if (null == $this->_createOrder) {
            $helper = $this->_helper;
            $helper->debug('Creating new procurement order request');

            /** @var  $customer */
            $customer = $this->getCustomer();
            $store = $this->getStore();
            $quoteId = $this->getDocument()->getQuoteId();

            /** @var \Punchout2go\Purchaseorder\Model\Order\Admin\Create $createOrder */
            $createOrder = new \Punchout2go\Purchaseorder\Model\Order\Admin\Create(
                $this->_objectManager,
                $this->_eventManager,
                $this->_coreRegistry,
                $this->_salesConfig,
                $this->_quoteSession,
                $this->_logger,
                $this->_objectCopyService,
                $this->_messageManager,
                $this->_quoteInitializer,
                $this->_customerRepository,
                $this->_addressRepository,
                $this->_addressFactory,
                $this->_metadataFormFactory,
                $this->_groupRepository,
                $this->_scopeConfig,
                $this->_emailSender,
                $this->_stockRegistry,
                $this->_quoteItemUpdater,
                $this->_objectFactory,
                $this->_quoteRepository,
                $this->_accountManagement,
                $this->_customerInterfaceFactory,
                $this->_customerMapper,
                $this->_quoteManagement,
                $this->_dataObjectHelper,
                $this->_orderManagement,
                $this->_quoteFactory,
                $this->_helper
            );

            // basic create setup
            // BP: MAGE1 has $createOrder->getSession()->setCustomer($customer); but that method isn't supported in M2
            $createOrder->getSession()->setCustomerId($customer->getId());
            // BP: MAGE1 has $createOrder->getSession()->setStore($store); but that method isn't supported in M2
            $createOrder->getSession()->setStoreId($store->getId());

            // if the admin already has a quote id, lets clear it.
            if (is_numeric($createOrder->getSession()->getQuoteId())) {
               $helper->debug('Detected existing admin session quote id.');
               $createOrder->getSession()->setQuoteId('');
            }

            $createOrder->initRuleData();
            // eg: {"store_id":"1","website_id":"1","customer_group_id":1}
            $helper->debug('Rule data : ' . \Zend\Json\Json::encode($this->_registry->registry('rule_data')));

            // pulling quote from its quote
            $helper->debug('Getting quote with : ' . (false === $quoteId ? '-mixed-' : $quoteId));
            $quote = $createOrder->getQuote($quoteId); // this needs to be the FIRST call to getQuote()

            // check to see if we got matching quote ids
            $helper->debug('Quote ID : ' . (null === $quote->getId() ? 'new' : $quote->getId()));
            if (!empty($quoteId) && $quote->getId() == $quoteId) {
                $helper->debug('Is a direct load');
                $createOrder->setDirectLoad(true);
            } elseif (empty($quoteId) || !is_numeric($quote->getId())) {
                $helper->debug('Quote does not have an id.. lets save to create it.');
                $quote->save(); // BP: deprecated; need to revisit
                $helper->debug('New ID : '. ($quote->getId() == null ? '??' : $quote->getId()));
            } else {
                $helper->debug('Not a direct load, but non-matching ID?');
            }

            // if no customer is set, then set as guest
            if (null === $createOrder->getSession()->getCustomerId()) {
                $helper->debug("No customer is set; set order customer as guest");
                $createOrder->getQuote()->setCustomerIsGuest(1);
            }

            // don't skip saleable check
            // BP: may want to later replace this with a config
            $this->_productHelper->setSkipSaleableCheck(false);

            // quote has items?
            if ($quote->getItemsCount() > 0) {
                $helper->debug('!! Quote already has an item count : '. $quote->getItemsCount());
                if ($createOrder->getDirectLoad()) {
                    // expected, it is a direct load cart.
                    $helper->debug('This is good, we are pulling existing quote.');
                    $helper->debug('Reset line number values on existing items.');
                    /** @var \Magento\Quote\Model\Quote\Item[] $items */
                    $items = $quote->getAllVisibleItems();
                    /** @var \Magento\Quote\Model\Quote\Item $item */
                    foreach ($items AS $item) {
                        $item->setLineNumber(null);
                        $item->save(); // BP: save is deprecated
                    }
                } else {
                    // not expected, loaded another admin cart
                    $helper->debug('This must be a lingering quote that did not clear.');

                    $info = new \Magento\Framework\DataObject();
                    $info->setContinueFlag(false);

                    // event to control what should be done about this.
                    // set $info->setContinueFlag(true) to skip exception
                    $helper->debug('Dispatching procurement_orderrequest_already_has_items');
                    $this->_eventManager->dispatch('procurement_orderrequest_already_has_items', [
                        'quote' => $quote,
                        'info' => $info
                    ]);

                    if (false === $info->getContinueFlag()) {
                        throw new \Exception(
                            '!!!! Quote appears to already have item data. '. $quote->getId()
                        );
                    }
                }
            }

            /** @var \Punchout2go\Purchaseorder\Model\Order\Admin\Create */
            $this->_createOrder = $createOrder;

            $this->_createOrder->getQuote()->setIsPurchaseOrderRequest(true);
        }

        return $this->_createOrder;
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        if ($this->_customer == null) {
            $customerId = $this->getDocument()->getCustomerId();
            $this->_customer = $this->_customerFactory->create()->load($customerId);
        }
        return $this->_customer;
    }

    /**
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        if ($this->_store == null) {
            $storeId = $this->getDocument()->getStoreId();
            $this->_store = $this->_storeRepository->getById($storeId);
        }
        return $this->_store;
    }

    /**
     * @param mixed $document
     */
    public function setDocument($document)
    {
        if ($document instanceof \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest) {
            $this->document = $document;
            return $this;
        }

        // test to see if JSON
        if (is_string($document) && null != json_decode($document)) {
            /** @var \Punchout2go\Purchaseorder\Model\Order\Request\Json _document */
            $this->_document = new \Punchout2go\Purchaseorder\Model\Order\Request\Json(
                $this->_itemsFactory,
                $this->_dataObject,
                $this->_customerRepository,
                $this->_storeRepository,
                $this->_helper
            );
            $this->_document->setDocument($document);
            return $this;
        }

        // test to see if XML; TODO
        if (is_string($document)) {
            libxml_use_internal_errors(true);
            $xmlDoc = simplexml_load_string($document);
            $xml = explode("\n", $document);
            if ($xmlDoc) {
                /** @var \Punchout2go\Purchaseorder\Model\Order\Request\Cxml _document */
                $this->_document = new \Punchout2go\Purchaseorder\Model\Order\Request\Cxml(
                    $this->_itemsFactory,
                    $this->_customerRepository,
                    $this->_storeRepository,
                    $this->_helper
                );
                $this->_document->setDocument($document);
            }
        }
    }

    /**
     * @return \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest
     */
    public function getDocument()
    {
        return $this->_document;
    }

    /**
     * @param int $productId
     * @param string $sku
     * @return \Magento\Catalog\Model\Product|null
     */
    public function lookupProductByIdOrSku($productId, $sku)
    {
        /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->_helper;

        /** @var \Magento\Catalog\Model\ProductFactory $productFactory */
        $productFactory = $this->_productFactory;

        if (is_numeric($productId)) {
            $helper->debug("Looking up product by ID ({$productId})");
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->_productFactory->create()->load($productId);

            if ($product && $product->getId() == $productId) {
                $helper->debug("Matched {$product->getName()}");
            } else {
                $helper->debug("did not match ID ({$productId})");
                $product = null;
            }
        }
        if (empty($product) && !empty($sku)) {
            $helper->debug("Looking up by sku {$sku}");
            $productId = $productFactory->getIdBySku($sku);
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $productFactory->load($productId);
            if ($product && $product->getId() == $productId) {
                $helper->debug("Matched {$product->getName()}");
            } else {
                $helper->debug("did not match ID ({$productId}");
                $product = null;
            }
        }

        return $product;
    }

    /**
     * @param $product
     * @param $reqData
     * @return \Magento\Quote\Model\Quote\Item
     */
    public function addBasicProduct($product, $reqData)
    {
        $helper = $this->_helper;
        $productFactory = $this->_productFactory;


        if (!is_array($reqData) && !($reqData instanceOf \Magento\Framework\DataObject)) {
            $reqData = array('qty' => $reqData);
        }

        $config = new \Magento\Framework\DataObject($reqData);

        /** @var \Magento\Catalog\Model\Product $product */
        if (!($product instanceof \Magento\Catalog\Model\Product)) {
            $productId = $product;
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $productFactory->create()
                //->setStore($this->getCreateOrder()->getSession()->getStore())
                ->setStoreId($this->getCreateOrder()->getSession()->getStoreId())
                ->load($productId);
            if (!$product->getId()) {
                throw new \Exception(
                    "Failed to add a product to cart by id {$productId}"
                );
            }
        }

        if (!$this->_helper->getConfigFlag('punchout2go_purchaseorder/orders/check_availability')) {
            // don't check unless requiring
            $product->setSkipCheckRequiredOption(true);
        }

        /** @note does not interpret decimal stock */
        /** @var \Magento\CatalogInventory\Model\StockRegistry $stockRegistry */
        //$stockRegistry = new \Magento\CatalogInventory\Model\StockRegistry();
        /** @var \Magento\CatalogInventory\Api\Data\StockItemInterface $stockItem */
        //$stockItem = $stockRegistry->getStockItem($productId);
        //if ($stockItem && $stockItem->getIsQtyDecimal()) {
        //    $product->setIsQtyDecimal(1);
        //} else {
        // BP: this line doesn't make sense; $config->getQty() is always null, right?
        //    $config->setQty((int) $config->getQty());
        //}

        $product->setCartQty($config->getQty());

        /** @var \Magento\Quote\Model\Quote\Item $item */
        $item = $this->getCreateOrder()->getQuote()->addProduct(
            $product,
            $config
        );

        // add store ID, price, and base price from request data
        $helper->debug('setting item store ID to ' . $reqData['store_id']);
        $item->setStoreId($reqData['store_id']);
        $helper->debug('setting item price to ' . $reqData['price']);
        $item->setPrice($reqData['price']);
        $helper->debug('setting item base price to ' . $reqData['base_price']);
        $item->setBasePrice($reqData['base_price']);

        $helper->debug('running calcRowTotal on new item');
        $item->calcRowTotal();

        /*
        if (is_string($item)) {
            if ($product->getTypeId() != \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE) {
                $item = $this->getCreateOrder()->getQuote()->addProduct(
                    $product,
                    $config,
                    \Magento\Catalog\Model\Product\Type\AbstractType::PROCESS_MODE_LITE
                );
            }
        }
        */
        if (is_string($item)) {
            throw new \Exception(
                "Failed to add product : {$item}"
            );
        }

        $this->getCreateOrder()->setRecollect(true);

        while (null != $item->getParentItem()) {
            $item = $item->getParentItem();
        }

        return $item;
    }

    /**
     * @return bool|null|string
     */
    public function setupShippingMethod()
    {
        /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->_helper;

        $createOrder = $this->getCreateOrder();

        $createOrder->collectShippingRates();

        /** @var  $rates */
        $rates = $this->getShippingRates(); // rate count should be debug logged.

        if (count($rates) == 0) {
            // no rates may be required, just let it go.
            $helper->debug('No shipping rates were found. possibly none are needed.');
            return '-';
        }

        $helper->debug('starting shipping rule logic');

        $originalQuoteId = $this->getDocument()->getQuoteId();

        $method = $this->findShippingMethodByPolicy();

        // if apply_provided_shipping is set to Yes,
        // look for the shipping code in the order request document
        $applyProvidedShipping = $helper->getConfigFlag('punchout2go_purchaseorder/orders/apply_provided_shipping');
        if (true == $applyProvidedShipping) {
            $method = $this->getDocument()->getShippingCode();
            $this->_applyProvidedShipping = true;
            $this->_orderShippingPrice = $this->getDocument()->getShippingPrice();
            $helper->debug("apply_provided_shipping set to true; using header-level shipping code {$method}");
        }

        if (empty($method)) {
            $method = $this->findShippingMethodByPrice($rates);
        }

        if (empty($method)) {
            $helper->debug("No shipping method was found, throwing out");
            throw new \Exception(
                "No shipping method was found, throwing out"
            );
        } else {
            $helper->debug("Using shipping method {$method}");
            /** @var \Magento\Quote\Model\Quote\Address\Rate $rate */
            $rate = $createOrder->getShippingAddress()->getShippingRateByCode($method);
        }

        if (!empty($this->_orderShippingPrice)) {
            $helper->debug("apply_provided_shipping set to true; using header-level shipping price {$this->_orderShippingPrice}");
            $rate->setPrice($this->_orderShippingPrice);
        }

        if (!empty($rate)) {
            $helper->debug("Rate has value : {$rate->getPrice()}");
            $createOrder->setShippingMethod($method);
            $helper->debug("Set method : {$method}");
            $address = $createOrder->getShippingAddress();
            $name = $rate->getCarrierTitle() ." - ". $rate->getMethodTitle();
            if (null != $rate->getMethodDescription()) {
                $name .= " (". $rate->getMethodDescription() .")";
            }
            $address->setShippingDescription($name);
            $helper->debug("Set shipping amount {$rate->getPrice()}");
            $address->setShippingAmount($rate->getPrice());
            $helper->debug("Set rate to address");
        } else {
            $helper->debug("Shipping method {$method} was defined, but no rate was matched. Throwing out.");
            throw new \Exception(
                "Shipping method {$method} was defined, but no rate was matched. Throwing out."
            );
        }

        $createOrder->getQuote()->setTotalsCollectedFlag(false);
        $createOrder->collectShippingRates();

        return $method;
    }

    /**
     * @return array
     */
    public function getShippingRates()
    {
        /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->_helper;

        $createOrder = $this->getCreateOrder();
        //$createOrder->getQuote()->setTotalsCollectedFlag(false);
        //$createOrder->collectShippingRates();

        // emulate the backend area to collect shipping rates;
        // this does not work as expected as of Mage 2.1.5 so we set a registry value on the PO controller instead
        $helper->debug("Get all shipping rates while emulating as " . \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE);
        $rates = $this->appState->emulateAreaCode(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
            function($createOrder) {
                $address = $createOrder->getShippingAddress();
                $address->collectShippingRates();
                $rates = $address->getAllShippingRates();
                return $rates;
            },
            [$createOrder]);

        $helper->debug("Shipping rates : " . count($rates));

        return $rates;
    }

    /**
     * @return bool|null
     */
    public function findShippingMethodByPolicy()
    {
        /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->_helper;

        $policy = unserialize($helper->getConfig('punchout2go_purchaseorder/orders/shipping_policy'));

        if (!empty($policy) && is_array($policy)) {
            $helper->debug('testing policy shipping option.');
            foreach ($policy AS $p) {
                $code = $p['key'];
                $helper->debug("Test shipping {$code}");
                $rate = $this->getCreateOrder()->getShippingAddress()->getShippingRateByCode($code);
                if (!empty($rate)) {
                    if (!$rate->isDeleted()) {
                        // got one, return the code, don't test more
                        return $code;
                    }
                }
            }
            $helper->debug("No policies were matched");
            return false;
        }
        $helper->debug("No policies were defined");
        return null;
    }

    /**
     * @param array $rates
     * @return bool|string
     */
    public function findShippingMethodByPrice($rates)
    {
        /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->_helper;
        $lowValue = false;
        $method = false;

        if (empty($method)) {
            $helper->debug("No ship policy found, finding cheapest option.");
            /** @var \Magento\Quote\Model\Quote\Address\Rate $rate */
            foreach ($rates AS $rate) {
                $code = $rate->getCode();
                if (null == $rate->getErrorMessage()) {
                    $helper->debug("Checking shipping {$code} : ". $rate->getPrice());
                    if ($lowValue === false
                        || $lowValue > (float) $rate->getPrice()) {
                        $lowValue = $rate->getPrice();
                        $method = $rate->getCarrier() ."_". $rate->getMethod();
                        $helper->debug("{$method} : {$lowValue}");
                    }
                } else {
                    $helper->debug("{$code} error : ". $rate->getErrorMessage());
                }
            }
        }

        return $method;
    }

    /**
     * sets payment data on the \Magento\Sales\Model\AdminOrder\Create object
     */
    public function setupPaymentMethod()
    {
        /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->_helper;

        $paymentType = $helper->getConfig('punchout2go_purchaseorder/orders/payment_method');

        /** @var \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest $document */
        $document = $this->getDocument();

        /** @var string $poNumber */
        $poNumber = $document->getPoNumber();

        /** @var string $requestId */
        $requestId = $document->getRequestId();

        $helper->debug("Setting payment method to {$paymentType}");
        $paymentData = ['method'=>$paymentType];
        $helper->debug("Setting PO number to {$poNumber}");
        $paymentData['po_number'] = $poNumber;
        $helper->debug("Setting request ID to {$requestId}");
        $paymentData['request_id'] = $requestId;

        $data = new \Magento\Framework\DataObject();
        $data->setData($paymentData);

        $this->_eventManager->dispatch('orderrequest_setup_payment',['paymentData'=>$data,'request'=>$this]);

        $paymentData = $data->getData();

        $helper->debug("Payment data : " . print_r($paymentData, true));

        try {
            $this->getCreateOrder()->setPaymentData($paymentData);
        } catch (\Exception $e) {
            $helper->debug("Throwing payment error : {$e->getMessage()}");
            throw $e;
        }
    }

}
