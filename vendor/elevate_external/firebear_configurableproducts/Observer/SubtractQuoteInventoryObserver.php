<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Firebear\ConfigurableProducts\Observer;

use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\CatalogInventory\Observer\ItemsForReindex;
use Magento\CatalogInventory\Observer\ProductQty;
use Magento\Framework\Event\ObserverInterface;
use Magento\CatalogInventory\Api\StockManagementInterface;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Quote\Model\Quote;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\ProductRepository;

/**
 * Catalog inventory module observer
 */
class SubtractQuoteInventoryObserver implements ObserverInterface
{
    /**
     * @var StockManagementInterface
     */
    protected $stockManagement;

    /**
     * @var ProductQty
     */
    protected $productQty;

    /**
     * @var ItemsForReindex
     */
    protected $itemsForReindex;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StockItemRepository
     */
    private $stockItemRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var StockStateInterface
     */
    protected $stockState;

    /**
     * SubtractQuoteInventoryObserver constructor.
     *
     * @param StockManagementInterface $stockManagement
     * @param ProductQty $productQty
     * @param ItemsForReindex $itemsForReindex
     * @param LoggerInterface $logger
     * @param StockItemRepository $stockItemRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        StockManagementInterface $stockManagement,
        ProductQty $productQty,
        ItemsForReindex $itemsForReindex,
        LoggerInterface $logger,
        StockItemRepository $stockItemRepository,
        ProductRepository $productRepository,
        StockStateInterface $stockState
    ) {
        $this->logger = $logger;
        $this->stockManagement = $stockManagement;
        $this->productQty = $productQty;
        $this->itemsForReindex = $itemsForReindex;
        $this->stockItemRepository = $stockItemRepository;
        $this->productRepository = $productRepository;
        $this->stockState = $stockState;
    }

    /**
     * Subtract quote items qtys from stock items related with quote items products.
     *
     * Used before order placing to make order save/place transaction smaller
     * Also called after every successful order placement to ensure subtraction of inventory
     *
     * @param EventObserver $observer
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(EventObserver $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        // Maybe we've already processed this quote in some event during order placement
        // e.g. call in event 'sales_model_service_quote_submit_before' and later in 'checkout_submit_all_after'
        if ($quote->getInventoryProcessed()) {
            return $this;
        }
        $items = $this->productQty->getProductQty($quote->getAllItems());
        $quoteItems = $quote->getAllItems();
        $configurableProductFlag = false;
        $bundleProductFlag = false;
        foreach ($quoteItems as $item) {
            if ($item->getProduct()->getTypeId() == 'configurable') {
                $configurableProductFlag = true;
            }
            if ($item->getProduct()->getTypeId() == 'bundle') {
                $bundleProductFlag = true;
            }
            if ($bundleProductFlag && $configurableProductFlag) {
                $selectedOption = $item->getOptionByCode('simple_product');
                if ($selectedOption) {
                    $selectedProduct = $selectedOption->getProduct();
                } else {
                    continue;
                }
                $stockQtySimpleProductInConfigurable = $this->stockState->getStockQty($selectedProduct->getId());
                if ($item->getQty() > $stockQtySimpleProductInConfigurable) {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('Not all of your products are available in the requested quantity.')
                    );
                } elseif ($item->getProduct()->getTypeId() == 'configurable') {
                        $id = $selectedOption->getProduct()->getId();
                        $items[$id] = $items[$item->getProduct()->getId()];
                }
            }
        }

        $itemsForReindex = $this->stockManagement->registerProductsSale(
            $items,
            $quote->getStore()->getWebsiteId()
        );
        $this->itemsForReindex->setItems($itemsForReindex);

        $quote->setInventoryProcessed(true);

        return $this;
    }
}
