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

use Aheadworks\Giftcard\Api\Data\Giftcard\CreditmemoInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Creditmemo
 *
 * @package Aheadworks\Giftcard\Model\Giftcard
 */
class Creditmemo extends AbstractExtensibleModel implements CreditmemoInterface
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

    public function getCreditmemoId()
    {
        return $this->getData(self::CREDITMEMO_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreditmemoId($creditmemoId)
    {
        return $this->setData(self::CREDITMEMO_ID, $creditmemoId);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
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
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Giftcard\Api\Data\Giftcard\CreditmemoExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
