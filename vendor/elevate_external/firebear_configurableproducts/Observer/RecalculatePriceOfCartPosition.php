<?php

namespace Firebear\ConfigurableProducts\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer;

class RecalculatePriceOfCartPosition implements ObserverInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    private $messageManager;

    /**
     * RecalculatePriceOfCartPosition constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        $item = $observer->getEvent()->getData('quote_item');
        $item = ($item->getParentItem() ? $item->getParentItem() : $item );
        foreach ($item->getChildren() as $childItem) {
            if ($item->getProduct()->getTypeId() == 'bundle' &&
                $childItem->getProduct()->getTypeId() == 'configurable') {
                $selectedVariation = $this->productRepository->get($childItem->getSku());
                $useDecimal = $childItem->getQtyOptions()[$selectedVariation->getId()]->getIsQtyDecimal();
                if ($useDecimal) {
                    $this->recalculatePricePosition($childItem, $selectedVariation);
                } elseif (floor($childItem->getData('qty_to_add')) != $childItem->getData('qty_to_add')) {
                    $this->messageManager->addWarningMessage(
                        __('You can not use decimal quantity for ') .
                        $selectedVariation->getName() .
                        __(' product. The quantity was rounded.')
                    );
                }
            }
        }
    }

    /**
     * Recalculate the price of the bundle product in which there is
     * a configurable product in decimal
     *
     * @param Item $item
     * @param ProductInterface $selectedVariation
     */
    private function recalculatePricePosition(Item $item, ProductInterface $selectedVariation)
    {
        $item->getProduct()->setIsSuperMode(true);
        $qtyToAdd = $item->getData('qty_to_add');
        $item->setData(CartItemInterface::KEY_QTY, $qtyToAdd);
        $item->setQty(1);
        $variationPrice = 0;
        foreach ($selectedVariation->getTierPrices() as $tierPrice) {
            if ($qtyToAdd >= $tierPrice->getQty()) {
                $variationPrice = $tierPrice->getValue();
            }
        }
        if (!$variationPrice) {
            $variationPrice = $selectedVariation->getPrice();
        }
        $item->setCustomPrice($variationPrice * $qtyToAdd);
        $item->setOriginalCustomPrice($variationPrice * $qtyToAdd);
        $item->getProduct()->setIsSuperMode(true);
    }
}
