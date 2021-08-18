<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model\Order\MultiShipping;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Model\Order;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Aheadworks\Giftcard\Api\Data\Giftcard\OrderInterfaceFactory as GiftcardOrderInterfaceFactory;
use Aheadworks\Giftcard\Api\Data\Giftcard\OrderInterface as GiftcardOrderInterface;
use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;

/**
 * Class Applier
 *
 * @package Aheadworks\Giftcard\Model\Order\MultiShipping
 */
class Applier
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var GiftcardOrderInterfaceFactory
     */
    private $giftcardOrderFactory;

    /**
     * @var bool
     */
    private $isFirstTimeRun = true;

    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param GiftcardOrderInterfaceFactory $giftcardOrderFactory
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        OrderExtensionFactory $orderExtensionFactory,
        GiftcardOrderInterfaceFactory $giftcardOrderFactory
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->giftcardOrderFactory = $giftcardOrderFactory;
    }

    /**
     * Apply all gift cards to orders created via multi shipping checkout
     *
     * @param Quote $quote
     * @param Address $address
     * @param Order $order
     */
    public function apply($quote, $address, $order)
    {
        /** @var GiftcardQuoteInterface[] $quoteGiftcards */
        $quoteGiftcards = $quote->getExtensionAttributes()->getAwGiftcardCodes();
        if ($this->isFirstTimeRun) {
            $this->isFirstTimeRun = false;
            foreach ($quoteGiftcards as $quoteGiftcard) {
                $quoteGiftcard
                    ->setGiftcardBalanceUsed(0)
                    ->setBaseGiftcardBalanceUsed(0);
            }
        }

        if ($address->getBaseAwGiftcardAmount()) {
            $order->setBaseAwGiftcardAmount($address->getBaseAwGiftcardAmount());
            $order->setAwGiftcardAmount($address->getAwGiftcardAmount());

            $orderGiftcards = [];
            foreach ($quoteGiftcards as $quoteGiftcard) {
                $addressBaseGiftcardAmountLeft =
                    $address->getBaseAwGiftcardAmount() - $address->getBaseAwGiftcardAmountUsed();
                $addressGiftcardAmountLeft =
                    $address->getAwGiftcardAmount() - $address->getAwGiftcardAmountUsed();

                $baseGiftcardAmount = min(
                    $quoteGiftcard->getBaseGiftcardAmount() - $quoteGiftcard->getBaseGiftcardBalanceUsed(),
                    $addressBaseGiftcardAmountLeft
                );
                $giftcardAmount = min(
                    $quoteGiftcard->getGiftcardAmount() - $quoteGiftcard->getGiftcardBalanceUsed(),
                    $addressGiftcardAmountLeft
                );
                if ($baseGiftcardAmount > 0) {
                    /** @var GiftcardOrderInterface $orderGiftcard */
                    $orderGiftcard = $this->giftcardOrderFactory->create();
                    $this->dataObjectHelper->populateWithArray(
                        $orderGiftcard,
                        $quoteGiftcard->getData(),
                        GiftcardOrderInterface::class
                    );
                    $orderGiftcard->setId(null)
                        ->setBaseGiftcardAmount($baseGiftcardAmount)
                        ->setGiftcardAmount($giftcardAmount);
                    $orderGiftcards[] = $orderGiftcard;

                    $quoteGiftcard
                        ->setBaseGiftcardBalanceUsed($quoteGiftcard->getBaseGiftcardBalanceUsed() + $baseGiftcardAmount)
                        ->setGiftcardBalanceUsed($quoteGiftcard->getGiftcardBalanceUsed() + $giftcardAmount);
                    $address
                        ->setBaseAwGiftcardAmountUsed($address->getBaseAwGiftcardAmountUsed() + $baseGiftcardAmount)
                        ->setAwGiftcardAmountUsed($address->getAwGiftcardAmountUsed() + $giftcardAmount);
                }
            }
            $extensionAttributes = $order->getExtensionAttributes()
                ? $order->getExtensionAttributes()
                : $this->orderExtensionFactory->create();
            $extensionAttributes->setAwGiftcardCodes($orderGiftcards);
            $order->setExtensionAttributes($extensionAttributes);
        }
    }
}
