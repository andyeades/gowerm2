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
namespace Aheadworks\Giftcard\Model\Service;

use Aheadworks\Giftcard\Api\GiftcardCartManagementInterface;
use Aheadworks\Giftcard\Api\GuestGiftcardCartManagementInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class GuestGiftcardCartService
 *
 * @package Aheadworks\Giftcard\Model\Service
 */
class GuestGiftcardCartService implements GuestGiftcardCartManagementInterface
{
    /**
     * @var GiftcardCartManagementInterface
     */
    private $giftcardCartManagement;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @param GiftcardCartManagementInterface $giftcardCartManagement
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     */
    public function __construct(
        GiftcardCartManagementInterface $giftcardCartManagement,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->giftcardCartManagement = $giftcardCartManagement;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($cartId)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->giftcardCartManagement->get($quoteIdMask->getQuoteId());
    }

    /**
     * {@inheritdoc}
     */
    public function set($cartId, $giftcardCode)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->giftcardCartManagement->set($quoteIdMask->getQuoteId(), $giftcardCode);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($cartId, $giftcardCode)
    {
        /** @var $quoteIdMask QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->giftcardCartManagement->remove($quoteIdMask->getQuoteId(), $giftcardCode);
    }
}
