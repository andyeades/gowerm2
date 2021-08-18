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
namespace Aheadworks\Giftcard\Plugin\Model;

use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Magento\Quote\Model\Quote;
use Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Quote\CollectionFactory as GiftcardQuoteCollectionFactory;
use Magento\Framework\EntityManager\EntityManager;
use Aheadworks\Giftcard\Plugin\Model\Quote\QuoteRepository\SaveHandlerPlugin;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class QuotePlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model
 */
class QuotePlugin
{
    /**
     * @var GiftcardQuoteCollectionFactory
     */
    private $giftcardQuoteCollectionFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SaveHandlerPlugin
     */
    private $saveHandlerPlugin;

    /**
     * @param GiftcardQuoteCollectionFactory $giftcardQuoteCollectionFactory
     * @param EntityManager $entityManager
     * @param SaveHandlerPlugin $saveHandlerPlugin
     */
    public function __construct(
        GiftcardQuoteCollectionFactory $giftcardQuoteCollectionFactory,
        EntityManager $entityManager,
        SaveHandlerPlugin $saveHandlerPlugin
    ) {
        $this->giftcardQuoteCollectionFactory = $giftcardQuoteCollectionFactory;
        $this->entityManager = $entityManager;
        $this->saveHandlerPlugin = $saveHandlerPlugin;
    }

    /**
     * Replace quote_id in Gift Card quote table after merge quote
     *
     * @param Quote $subject
     * @param \Closure $proceed
     * @param Quote $quote
     * @return Quote
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundMerge(Quote $subject, \Closure $proceed, Quote $quote)
    {
        /** @var Quote $quoteAfterMerge */
        $quoteAfterMerge = $proceed($quote);

        $giftcardQuoteItems = $this->giftcardQuoteCollectionFactory->create()
            ->addFieldToFilter('quote_id', $quote->getId())
            ->load()
            ->getItems();

        if (!$giftcardQuoteItems) {
            return $quoteAfterMerge;
        }

        $profileGiftcardQuoteItems = $this->giftcardQuoteCollectionFactory->create()
            ->addFieldToFilter('quote_id', $quoteAfterMerge->getId())
            ->load()
            ->getItems();

        /** @var GiftcardQuoteInterface $giftcardQuoteItem */
        foreach ($giftcardQuoteItems as $giftcardQuoteItem) {
            $found = false;
            foreach ($profileGiftcardQuoteItems as $profileGiftcardQuoteItem) {
                if ($giftcardQuoteItem->getGiftcardId() == $profileGiftcardQuoteItem->getGiftcardId()) {
                    $found = true;
                    continue;
                }
            }

            if (!$found) {
                $giftcardQuoteItem->setQuoteId($quoteAfterMerge->getId());
                $this->entityManager->save($giftcardQuoteItem);
            } else {
                $this->entityManager->delete($giftcardQuoteItem);
            }
        }

        return $quoteAfterMerge;
    }

    /**
     * Save Gift Card codes to Gift Card quote table (Compatible with EE Customer Balance)
     *
     * @param Quote $subject
     * @param Quote $quote
     * @return Quote
     * @throws LocalizedException
     */
    public function afterSave($subject, $quote)
    {
        return $this->saveHandlerPlugin->processQuoteGiftcards($quote);
    }
}
