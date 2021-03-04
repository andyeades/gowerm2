<?php

namespace Punchout2go\Purchaseorder\Model;

class Quote extends \Magento\Quote\Model\Quote
{
    /** @var boolean */
    protected $_isPurchaseOrderRequest;

    /** @var \Punchout2go\Purchaseorder\Helper\Data */
    protected $_helper;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Quote\Model\Quote\AddressFactory $quoteAddressFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $quoteItemCollectionFactory,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Framework\Message\Factory $messageFactory,
        \Magento\Sales\Model\Status\ListFactory $statusListFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Quote\Model\Quote\PaymentFactory $quotePaymentFactory,
        \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory $quotePaymentCollectionFactory,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Quote\Model\Quote\Item\Processor $itemProcessor,
        \Magento\Framework\DataObject\Factory $objectFactory,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $criteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        \Magento\Quote\Model\Cart\CurrencyFactory $currencyFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector,
        \Magento\Quote\Model\Quote\TotalsReader $totalsReader,
        \Magento\Quote\Model\ShippingFactory $shippingFactory,
        \Magento\Quote\Model\ShippingAssignmentFactory $shippingAssignmentFactory,
        \Punchout2go\Purchaseorder\Helper\Data $helper,
        $resource,
        $resourceCollection,
        array $data
    ) {
        $this->_isPurchaseOrderRequest = false;
        $this->_helper = $helper;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $quoteValidator,
            $catalogProduct, $scopeConfig, $storeManager, $config, $quoteAddressFactory, $customerFactory,
            $groupRepository, $quoteItemCollectionFactory, $quoteItemFactory, $messageFactory, $statusListFactory,
            $productRepository, $quotePaymentFactory, $quotePaymentCollectionFactory, $objectCopyService,
            $stockRegistry, $itemProcessor, $objectFactory, $addressRepository, $criteriaBuilder, $filterBuilder,
            $addressDataFactory, $customerDataFactory, $customerRepository, $dataObjectHelper,
            $extensibleDataObjectConverter, $currencyFactory, $extensionAttributesJoinProcessor, $totalsCollector,
            $totalsReader, $shippingFactory, $shippingAssignmentFactory, $resource, $resourceCollection, $data);
    }

    /**
     * @return bool
     */
    public function getIsPurchaseOrderRequest()
    {
        return (true === $this->_isPurchaseOrderRequest);
    }

    /**
     * @param boolean $isPurchaseOrderRequest
     */
    public function setIsPurchaseOrderRequest($isPurchaseOrderRequest)
    {
        $this->_isPurchaseOrderRequest = $isPurchaseOrderRequest;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param null $request
     * @param null $processMode
     * @return array|\Magento\Quote\Model\Quote\Item
     */
    public function addProductAdvanced($product, $request = null, $processMode = null)
    {
        if (null === $request) {
            $request = 1;
        }
        if (is_numeric($request)) {
            $request = new \Magento\Framework\DataObject(array('qty' => $request));
        }
        if (!($request instanceof \Magento\Framework\DataObject)) {
            throw new \Exception(
                "Invalid request for adding product to quote."
            );
        }

        $cartCandidates = $product->getTypeInstance(true)
            ->prepareForCartAdvanced($request, $product, $processMode);

        // if $cartCandidates is a string, it's an error message
        if (is_string($cartCandidates)) {
            return $cartCandidates;
        }

        // if prepare process return one object
        if (!is_array($cartCandidates)) {
            $cartCandidates = array($cartCandidates);
        }

        $parentItem = null;
        $errors = array();
        $items = array();
        foreach ($cartCandidates as $candidate) {
            // child items can be stuck together only within their parent
            $stickWithinParent = $candidate->getParentProductId() ? $parentItem : null;
            $candidate->setStickWithinParent($stickWithinParent);
            /** @var \Magento\Quote\Model\Quote\Item $item */
            $item = $this->_addCatalogProduct($candidate, $candidate->getCartQty());
            if ($request->getResetCount() && !$stickWithinParent && $item->getId() === $request->getId()) {
                $item->setData('qty', 0);
            }
            $items[] = $item;

            // As parent item, we should always use the item of first-added product
            if (!$parentItem) {
                $parentItem = $item;
            }
            if ($parentItem && $candidate->getParentProductId()) {
                $item->setParentItem($parentItem);
            }

            // We specify qty after we know about parent (for stock)
            $item->addQty($candidate->getCartQty());

            // collect errors instead of throwing first one
            if ($item->getHasError()) {
                $message = $item->getMessage();
                // filter duplicate messages
                if (!in_array($message, $errors)) {
                    $errors[] = $message;
                }
            }
        }
        if (!empty($errors)) {
            throw new \Exception(
                "Error(s) while attempting to add product : " .
                implode("\n", $errors)
            );
        }

        $this->_helper->debug('Dispatching sales_quote_product_add_after');
        $this->_eventManager->dispatch('sales_quote_product_add_after', array('items' => $items));

        return $item;
    }
}