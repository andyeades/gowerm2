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
namespace Aheadworks\Giftcard\Model\Giftcard;

use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Quote
 *
 * @package Aheadworks\Giftcard\Model\Giftcard
 */
class Quote extends AbstractExtensibleModel implements QuoteInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getGiftcardId()
    {
        return $this->getData(self::GIFTCARD_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftcardId($giftcardId)
    {
        return $this->setData(self::GIFTCARD_ID, $giftcardId);
    }

    /**
     * {@inheritdoc}
     */
    public function getGiftcardCode()
    {
        return $this->getData(self::GIFTCARD_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftcardCode($giftcardCode)
    {
        return $this->setData(self::GIFTCARD_CODE, $giftcardCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * {@inheritdoc}
     */
    public function getGiftcardBalance()
    {
        return $this->getData(self::GIFTCARD_BALANCE);
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftcardBalance($balance)
    {
        return $this->setData(self::GIFTCARD_BALANCE, $balance);
    }

    /**
     * {@inheritdoc}
     */
    public function getGiftcardBalanceUsed()
    {
        return $this->getData(self::GIFTCARD_BALANCE_USED);
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftcardBalanceUsed($balanceUsed)
    {
        return $this->setData(self::GIFTCARD_BALANCE_USED, $balanceUsed);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseGiftcardBalanceUsed()
    {
        return $this->getData(self::BASE_GIFTCARD_BALANCE_USED);
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseGiftcardBalanceUsed($baseBalanceUsed)
    {
        return $this->setData(self::BASE_GIFTCARD_BALANCE_USED, $baseBalanceUsed);
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseGiftcardAmount()
    {
        return $this->getData(self::BASE_GIFTCARD_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseGiftcardAmount($amount)
    {
        return $this->setData(self::BASE_GIFTCARD_AMOUNT, $amount);
    }

    /**
     * {@inheritdoc}
     */
    public function getGiftcardAmount()
    {
        return $this->getData(self::GIFTCARD_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftcardAmount($amount)
    {
        return $this->setData(self::GIFTCARD_AMOUNT, $amount);
    }

    /**
     * {@inheritdoc}
     */
    public function isRemove()
    {
        return $this->getData(self::IS_REMOVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsRemove($isRemove)
    {
        return $this->setData(self::IS_REMOVE, $isRemove);
    }

    /**
     * {@inheritdoc}
     */
    public function isInvalid()
    {
        return $this->getData(self::IS_INVALID);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsInvalid($isInvalid)
    {
        return $this->setData(self::IS_INVALID, $isInvalid);
    }

    /**
     * {@inheritdoc}
     */
    public function getGiftcardProductId()
    {
        return $this->getData(self::GIFTCARD_PRODUCT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftcardProductId($productId)
    {
        return $this->setData(self::GIFTCARD_PRODUCT_ID, $productId);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Giftcard\Api\Data\Giftcard\QuoteExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
