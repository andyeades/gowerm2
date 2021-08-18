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
namespace Aheadworks\Giftcard\Model\Giftcard\History;

use Aheadworks\Giftcard\Api\Data\Giftcard\History\EntityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Class Entity
 *
 * @package Aheadworks\Giftcard\Model\Giftcard\History
 */
class Entity extends AbstractExtensibleModel implements EntityInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHistoryId()
    {
        return $this->getData(self::HISTORY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setHistoryId($historyId)
    {
        return $this->setData(self::HISTORY_ID, $historyId);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityLabel()
    {
        return $this->getData(self::ENTITY_LABEL);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityLabel($entityLabel)
    {
        return $this->setData(self::ENTITY_LABEL, $entityLabel);
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
        \Aheadworks\Giftcard\Api\Data\Giftcard\History\EntityExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
