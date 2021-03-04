<?php


namespace Elevate\CartAssignments\Model\Data;

use Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface;

/**
 * Class QuoteItemAssignments
 *
 * @package Elevate\CartAssignments\Model\Data
 */
class QuoteItemAssignments extends \Magento\Framework\Api\AbstractExtensibleObject implements QuoteItemAssignmentsInterface
{

    /**
     * Get quoteitemassignments_id
     * @return string|null
     */
    public function getQuoteitemassignmentsId()
    {
        return $this->_get(self::QUOTEITEMASSIGNMENTS_ID);
    }

    /**
     * Set quoteitemassignments_id
     * @param string $quoteitemassignmentsId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setQuoteitemassignmentsId($quoteitemassignmentsId)
    {
        return $this->setData(self::QUOTEITEMASSIGNMENTS_ID, $quoteitemassignmentsId);
    }

    /**
     * Get linked_quote_item_id
     * @return string|null
     */
    public function getLinkedQuoteItemId()
    {
        return $this->_get(self::LINKED_QUOTE_ITEM_ID);
    }

    /**
     * Set linked_quote_item_id
     * @param string $linkedQuoteItemId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setLinkedQuoteItemId($linkedQuoteItemId)
    {
        return $this->setData(self::LINKED_QUOTE_ITEM_ID, $linkedQuoteItemId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get parent_quote_item_id
     * @return string|null
     */
    public function getParentQuoteItemId()
    {
        return $this->_get(self::PARENT_QUOTE_ITEM_ID);
    }

    /**
     * Set parent_quote_item_id
     * @param string $parentQuoteItemId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setParentQuoteItemId($parentQuoteItemId)
    {
        return $this->setData(self::PARENT_QUOTE_ITEM_ID, $parentQuoteItemId);
    }

    /**
     * Get quote_id
     * @return string|null
     */
    public function getQuoteId()
    {
        return $this->_get(self::QUOTE_ID);
    }

    /**
     * Set quote_id
     * @param string $quoteId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * Get addon_id
     * @return string|null
     */
    public function getAddonId()
    {
        return $this->_get(self::ADDON_ID);
    }

    /**
     * Set addon_id
     * @param string $addonId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setAddonId($addonId)
    {
        return $this->setData(self::ADDON_ID, $addonId);
    }

    /**
     * Get template_item_id
     * @return string|null
     */
    public function getTemplateItemId()
    {
        return $this->_get(self::TEMPLATE_ITEM_ID);
    }

    /**
     * Set template_item_id
     * @param string $templateItemId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setTemplateItemId($templateItemId)
    {
        return $this->setData(self::TEMPLATE_ITEM_ID, $templateItemId);
    }

    /**
     * Get location
     * @return string|null
     */
    public function getLocation()
    {
        return $this->_get(self::LOCATION);
    }

    /**
     * Set location
     * @param string $location
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setLocation($location)
    {
        return $this->setData(self::LOCATION, $location);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get qty
     * @return string|null
     */
    public function getQty()
    {
        return $this->_get(self::QTY);
    }

    /**
     * Set qty
     * @param string $qty
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }
}

