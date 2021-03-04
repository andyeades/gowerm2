<?php

namespace Punchout2go\Purchaseorder\Model\Order\Admin;

//use Symfony\Component\Config\Definition\Exception\Exception;

class Create extends \Magento\Sales\Model\AdminOrder\Create
{

    // if the quote is loaded directly from existing customer quote
    protected $_directLoad;

    /** @var \Punchout2go\Purchaseorder\Helper\Data */
    protected $_helper;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Sales\Model\Config $salesConfig,
        \Magento\Backend\Model\Session\Quote $quoteSession,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Sales\Model\AdminOrder\Product\Quote\Initializer $quoteInitializer,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
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
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Punchout2go\Purchaseorder\Helper\Data $helper,
        array $data = array()
    ) {
        parent::__construct($objectManager, $eventManager, $coreRegistry, $salesConfig, $quoteSession, $logger,
            $objectCopyService, $messageManager, $quoteInitializer, $customerRepository, $addressRepository,
            $addressFactory, $metadataFormFactory, $groupRepository, $scopeConfig, $emailSender, $stockRegistry,
            $quoteItemUpdater, $objectFactory, $quoteRepository, $accountManagement, $customerFactory, $customerMapper,
            $quoteManagement, $dataObjectHelper, $orderManagement, $quoteFactory, $data);

        $this->_directLoad = false;
        $this->_helper = $helper;
    }

    /**
     * @param $boolean
     */
    public function setRecollectCart($boolean)
    {
        $this->_needCollectCart = $boolean;
    }

    /**
     * @return \Magento\Sales\Model\AdminOrder\Create
     */
    public function _validate()
    {
        $this->_parentValidate();
        $this->_helper->debug("validated with " . count($this->_errors) . " errors");
        return $this;
    }

    /**
     * @return $this
     */
    public function _parentValidate()
    {
        if (!$this->getSession()->getStore()->getId()) {
            throw new \Exception('Please select a store.');
        }
        $items = $this->getQuote()->getAllItems();

        if (count($items) == 0) {
            $this->_errors[] = 'You need to specify order items.';
        }

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($items as $item) {
            $messages = $item->getMessage(false);
            if ($item->getHasError() && is_array($messages) && !empty($messages)) {
                $this->_errors = array_merge($this->_errors, $messages);
            }
        }

        if (!$this->getQuote()->isVirtual()) {
            if (!$this->getQuote()->getShippingAddress()->getShippingMethod()) {
                $this->_errors[] = 'Shipping method must be specified.';
            }
        }

        if (!$this->getQuote()->getPayment()->getMethod()) {
            $this->_errors[] = 'Payment method must be specified.';
        } else {
            /** @var \Magento\Payment\Model\MethodInterface $method */
            $method = $this->getQuote()->getPayment()->getMethodInstance();
            if (!$method) {
                $this->_errors[] = 'Payment method instance is not available.';
            } else {
                try {
                    $method->validate();
                } catch (\Exception $e) {
                    $this->_errors[] = $e->getMessage();
                }
            }
        }

        if (!empty($this->_errors)) {
            foreach ($this->_errors as $error) {
                // add error to current session
            }
            throw new \Exception(implode("\n", $this->_errors));
        }
        return $this;
    }

    /**
     * @param null $quoteId
     * @return \Punchout2go\Purchaseorder\Model\Quote
     */
    public function getQuote($quoteId = null)
    {
        if (is_numeric($quoteId)) {
            /** @var \Punchout2go\Purchaseorder\Model\Quote $quote */
            // note that we're using the parent class's $this->quoteFactory
            $quote = $this->quoteFactory->create()
                ->setStoreId($this->getSession()->getStoreId())
                ->load($quoteId);
            if ($quote->getId() == $quoteId) {
                $quote->setIsActive(false);
                $quote->setIgnoreOldQty(true);
                $quote->setIsSuperMode(true);
                $quote->save(); // BP: deprecated; need to revisit

                $this->setQuote($quote);
                $this->getSession()->setQuoteId($quote->getId());
            }
        }
        return parent::getQuote();
    }

    /**
     * @param boolean $directLoad
     */
    public function setDirectLoad($directLoad)
    {
        $this->_directLoad = $directLoad;
        $this->getQuote()->setDirectLoad($directLoad);
    }

    /**
     * @return bool
     */
    public function getDirectLoad()
    {
        return $this->_directLoad;
    }

    /**
     * Create new order
     *
     * @return \Magento\Sales\Model\Order
     */
    public function createOrder()
    {
        $helper = $this->_helper;
        $helper->debug("Preparing customer");
        $this->_prepareCustomer();
        $helper->debug("Validating Admin Create Order");
        $this->_validate();
        $helper->debug("Retrieving quote");
        $quote = $this->getQuote();
        $helper->debug("Preparing quote items");
        $this->_prepareQuoteItems();

        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();
            $originalId = $oldOrder->getOriginalIncrementId();
            $helper->debug("Getting old order; original ID = {$originalId}");
            if (!$originalId) {
                $originalId = $oldOrder->getIncrementId();
                $helper->debug("No original ID; setting to old order increment ID {$originalId}");
            }
            $orderData = [
                'original_increment_id' => $originalId,
                'relation_parent_id' => $oldOrder->getId(),
                'relation_parent_real_id' => $oldOrder->getIncrementId(),
                'edit_increment' => $oldOrder->getEditIncrement() + 1,
                'increment_id' => $originalId . '-' . ($oldOrder->getEditIncrement() + 1)
            ];
            $helper->debug("Order Data : " . print_r($orderData, true));
            $helper->debug("Setting reserved order ID to {$orderData['increment_id']}");
            $quote->setReservedOrderId($orderData['increment_id']);
        }

        $helper->debug("Submitting quote...");

        // checking to see if the quote has already been ordered
        $reservedOrderId = $quote->getReservedOrderId();
        $helper->debug("reservedOrderId : {$reservedOrderId}");
        if (is_numeric($reservedOrderId)) {
            $helper->debug("Quote has already been ordered: {$reservedOrderId}");
            throw new \Exception(
                "Quote has already been ordered: {$reservedOrderId}"
            );
        }

        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        $order = $this->quoteManagement->submit($quote);

        $helper->debug("Preparing customer");
        if ($this->getSession()->getOrder()->getId()) {
            $oldOrder = $this->getSession()->getOrder();
            $helper->debug("Set old order relation child ID to {$order->getId()}");
            $oldOrder->setRelationChildId($order->getId());
            $helper->debug("Set old order relation child real ID to {$order->getIncrementId()}");
            $oldOrder->setRelationChildRealId($order->getIncrementId());
            $helper->debug("Cancel order {$oldOrder->getEntityId()}");
            $this->orderManagement->cancel($oldOrder->getEntityId());
            $helper->debug("Saving order");
            $order->save();
        }
        if ($this->getSendConfirmation()) {
            $helper->debug("Send order confirmation email");
            $this->emailSender->send($order);
        }

        $helper->debug("Dispatching checkout_submit_all_after");
        $this->_eventManager->dispatch('checkout_submit_all_after', ['order' => $order, 'quote' => $quote]);

        return $order;
    }

    /**
     * Set payment data into quote
     *
     * @param array $data
     * @return $this
     */
    public function setPaymentData($data)
    {
        if (!isset($data['method'])) {
            $data['method'] = $this->getQuote()->getPayment()->getMethod();
        }
        $this->_helper->debug("importing quote payment data");
        $this->getQuote()->getPayment()->importData($data);
        $this->_helper->debug("set quote payment PO number to {$data['po_number']}");
        $this->getQuote()->getPayment()->setPoNumber($data['po_number']);
        $this->_helper->debug("set quote PO number to {$data['po_number']}");
        $this->getQuote()->setPoNumber($data['po_number']);
        $this->_helper->debug("set quote Order Request ID to {$data['request_id']}");
        $this->getQuote()->setOrderRequestId($data['request_id']);

        return $this;
    }
}
