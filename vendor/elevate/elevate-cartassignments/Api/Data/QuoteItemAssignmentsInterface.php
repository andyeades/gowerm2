<?php


namespace Elevate\CartAssignments\Api\Data;

/**
 * Interface QuoteItemAssignmentsInterface
 *
 * @package Elevate\CartAssignments\Api\Data
 */
interface QuoteItemAssignmentsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const LINKED_QUOTE_ITEM_ID = 'linked_quote_item_id';
    const TEMPLATE_ITEM_ID = 'template_item_id';
    const QTY = 'qty';
    const PARENT_QUOTE_ITEM_ID = 'parent_quote_item_id';
    const ADDON_ID = 'addon_id';
    const QUOTE_ID = 'quote_id';
    const UPDATED_AT = 'updated_at';
    const LOCATION = 'location';
    const QUOTEITEMASSIGNMENTS_ID = 'quoteitemassignments_id';
    const CREATED_AT = 'created_at';

    /**
     * Get quoteitemassignments_id
     * @return string|null
     */
    public function getQuoteitemassignmentsId();

    /**
     * Set quoteitemassignments_id
     * @param string $quoteitemassignmentsId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setQuoteitemassignmentsId($quoteitemassignmentsId);

    /**
     * Get linked_quote_item_id
     * @return string|null
     */
    public function getLinkedQuoteItemId();

    /**
     * Set linked_quote_item_id
     * @param string $linkedQuoteItemId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setLinkedQuoteItemId($linkedQuoteItemId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsExtensionInterface $extensionAttributes
    );

    /**
     * Get parent_quote_item_id
     * @return string|null
     */
    public function getParentQuoteItemId();

    /**
     * Set parent_quote_item_id
     * @param string $parentQuoteItemId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setParentQuoteItemId($parentQuoteItemId);

    /**
     * Get quote_id
     * @return string|null
     */
    public function getQuoteId();

    /**
     * Set quote_id
     * @param string $quoteId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setQuoteId($quoteId);

    /**
     * Get addon_id
     * @return string|null
     */
    public function getAddonId();

    /**
     * Set addon_id
     * @param string $addonId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setAddonId($addonId);

    /**
     * Get template_item_id
     * @return string|null
     */
    public function getTemplateItemId();

    /**
     * Set template_item_id
     * @param string $templateItemId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setTemplateItemId($templateItemId);

    /**
     * Get location
     * @return string|null
     */
    public function getLocation();

    /**
     * Set location
     * @param string $location
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setLocation($location);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get qty
     * @return string|null
     */
    public function getQty();

    /**
     * Set qty
     * @param string $qty
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     */
    public function setQty($qty);
}

