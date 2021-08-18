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
namespace Aheadworks\Giftcard\Plugin\Model\Quote\QuoteRepository;

use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository\SaveHandler;
use Magento\Framework\EntityManager\EntityManager;

/**
 * Class SaveHandlerPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model\Quote\QuoteRepository
 */
class SaveHandlerPlugin
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * Save Gift Card codes to Gift Card quote table
     *
     * @param SaveHandler $subject
     * @param CartInterface $quote
     * @return CartInterface
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave($subject, $quote)
    {
        return $this->processQuoteGiftcards($quote);
    }

    /**
     * Process giftcards in the quote
     *
     * @param Quote $quote
     * @return Quote
     * @throws LocalizedException
     */
    public function processQuoteGiftcards($quote)
    {
        if ($quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getAwGiftcardCodes()) {
            $giftcards = $quote->getExtensionAttributes()->getAwGiftcardCodes();
            $this->saveGiftcards($giftcards);
            $this->notifyAboutInvalidGiftcard($giftcards);
        }
        return $quote;
    }

    /**
     * Save Gift Card codes to Gift Card quote table
     *
     * @param GiftcardQuoteInterface[] $giftcards
     */
    public function saveGiftcards($giftcards)
    {
        foreach ($giftcards as $giftcard) {
            if ($giftcard->isRemove()) {
                $this->entityManager->delete($giftcard);
            } else {
                $this->entityManager->save($giftcard);
            }
        }
    }

    /**
     * Notify about invalid giftcard
     *
     * @param GiftcardQuoteInterface[] $giftcards
     * @throws LocalizedException
     */
    public function notifyAboutInvalidGiftcard($giftcards)
    {
        foreach ($giftcards as $giftcard) {
            if ($giftcard->isInvalid()) {
                $exceptionMessage = __(
                    'Gift Card code "%1" is not valid anymore and has been canceled',
                    $giftcard->getGiftcardCode()
                );
                throw new LocalizedException($exceptionMessage);
            }
        }
    }
}
