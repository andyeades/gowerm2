<?php


namespace Elevate\CartAssignments\Model\Data;

use Elevate\CartAssignments\Api\Data\CartAssignmentsInterface;

/**
 * Class CartAssignments
 *
 * @package Elevate\CartAssignments\Model\Data
 */
class CartAssignments extends \Magento\Framework\Api\AbstractExtensibleObject implements CartAssignmentsInterface
{

    /**
     * Get cartassignments_id
     * @return string|null
     */
    public function getCartassignmentsId()
    {
        return $this->_get(self::CARTASSIGNMENTS_ID);
    }

    /**
     * Set cartassignments_id
     * @param string $cartassignmentsId
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCartassignmentsId($cartassignmentsId)
    {
        return $this->setData(self::CARTASSIGNMENTS_ID, $cartassignmentsId);
    }

    /**
     * Get enable_addon
     * @return string|null
     */
    public function getEnableAddon()
    {
        return $this->_get(self::ENABLE_ADDON);
    }

    /**
     * Set enable_addon
     * @param string $enableAddon
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setEnableAddon($enableAddon)
    {
        return $this->setData(self::ENABLE_ADDON, $enableAddon);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\CartAssignments\Api\Data\CartAssignmentsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\CartAssignments\Api\Data\CartAssignmentsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get title
     * @return string|null
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * Set title
     * @param string $title
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get addon_type
     * @return string|null
     */
    public function getAddonType()
    {
        return $this->_get(self::ADDON_TYPE);
    }

    /**
     * Set addon_type
     * @param string $addonType
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setAddonType($addonType)
    {
        return $this->setData(self::ADDON_TYPE, $addonType);
    }

    /**
     * Get template_id
     * @return string|null
     */
    public function getTemplateId()
    {
        return $this->_get(self::TEMPLATE_ID);
    }

    /**
     * Set template_id
     * @param string $templateId
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setTemplateId($templateId)
    {
        return $this->setData(self::TEMPLATE_ID, $templateId);
    }

    /**
     * Get description
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get start_date
     * @return string|null
     */
    public function getStartDate()
    {
        return $this->_get(self::START_DATE);
    }

    /**
     * Set start_date
     * @param string $startDate
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setStartDate($startDate)
    {
        return $this->setData(self::START_DATE, $startDate);
    }

    /**
     * Get end_date
     * @return string|null
     */
    public function getEndDate()
    {
        return $this->_get(self::END_DATE);
    }

    /**
     * Set end_date
     * @param string $endDate
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setEndDate($endDate)
    {
        return $this->setData(self::END_DATE, $endDate);
    }

    /**
     * Get sku
     * @return string|null
     */
    public function getSku()
    {
        return $this->_get(self::SKU);
    }

    /**
     * Set sku
     * @param string $sku
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Get product_match_attribute_one_parent
     * @return string|null
     */
    public function getProductMatchAttributeOneParent()
    {
        return $this->_get(self::PRODUCT_MATCH_ATTRIBUTE_ONE_PARENT);
    }

    /**
     * Set product_match_attribute_one_parent
     * @param string $productMatchAttributeOneParent
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setProductMatchAttributeOneParent($productMatchAttributeOneParent)
    {
        return $this->setData(self::PRODUCT_MATCH_ATTRIBUTE_ONE_PARENT, $productMatchAttributeOneParent);
    }

    /**
     * Get product_match_attribute_one_child
     * @return string|null
     */
    public function getProductMatchAttributeOneChild()
    {
        return $this->_get(self::PRODUCT_MATCH_ATTRIBUTE_ONE_CHILD);
    }

    /**
     * Set product_match_attribute_one_child
     * @param string $productMatchAttributeOneChild
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setProductMatchAttributeOneChild($productMatchAttributeOneChild)
    {
        return $this->setData(self::PRODUCT_MATCH_ATTRIBUTE_ONE_CHILD, $productMatchAttributeOneChild);
    }

    /**
     * Get product_match_attribute_two_parent
     * @return string|null
     */
    public function getProductMatchAttributeTwoParent()
    {
        return $this->_get(self::PRODUCT_MATCH_ATTRIBUTE_TWO_PARENT);
    }

    /**
     * Set product_match_attribute_two_parent
     * @param string $productMatchAttributeTwoParent
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setProductMatchAttributeTwoParent($productMatchAttributeTwoParent)
    {
        return $this->setData(self::PRODUCT_MATCH_ATTRIBUTE_TWO_PARENT, $productMatchAttributeTwoParent);
    }

    /**
     * Get product_match_attribute_two_child
     * @return string|null
     */
    public function getProductMatchAttributeTwoChild()
    {
        return $this->_get(self::PRODUCT_MATCH_ATTRIBUTE_TWO_CHILD);
    }

    /**
     * Set product_match_attribute_two_child
     * @param string $productMatchAttributeTwoChild
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setProductMatchAttributeTwoChild($productMatchAttributeTwoChild)
    {
        return $this->setData(self::PRODUCT_MATCH_ATTRIBUTE_TWO_CHILD, $productMatchAttributeTwoChild);
    }

    /**
     * Get assigned_categories
     * @return string|null
     */
    public function getAssignedCategories()
    {
        return $this->_get(self::ASSIGNED_CATEGORIES);
    }

    /**
     * Set assigned_categories
     * @param string $assignedCategories
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setAssignedCategories($assignedCategories)
    {
        return $this->setData(self::ASSIGNED_CATEGORIES, $assignedCategories);
    }

    /**
     * Get conditions
     * @return string|null
     */
    public function getConditions()
    {
        return $this->_get(self::CONDITIONS);
    }

    /**
     * Set conditions
     * @param string $conditions
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setConditions($conditions)
    {
        return $this->setData(self::CONDITIONS, $conditions);
    }

    /**
     * Get assigned_skus
     * @return string|null
     */
    public function getAssignedSkus()
    {
        return $this->_get(self::ASSIGNED_SKUS);
    }

    /**
     * Set assigned_skus
     * @param string $assignedSkus
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setAssignedSkus($assignedSkus)
    {
        return $this->setData(self::ASSIGNED_SKUS, $assignedSkus);
    }

    /**
     * Get categories_blacklist
     * @return string|null
     */
    public function getCategoriesBlacklist()
    {
        return $this->_get(self::CATEGORIES_BLACKLIST);
    }

    /**
     * Set categories_blacklist
     * @param string $categoriesBlacklist
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCategoriesBlacklist($categoriesBlacklist)
    {
        return $this->setData(self::CATEGORIES_BLACKLIST, $categoriesBlacklist);
    }

    /**
     * Get product_blacklist
     * @return string|null
     */
    public function getProductBlacklist()
    {
        return $this->_get(self::PRODUCT_BLACKLIST);
    }

    /**
     * Set product_blacklist
     * @param string $productBlacklist
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setProductBlacklist($productBlacklist)
    {
        return $this->setData(self::PRODUCT_BLACKLIST, $productBlacklist);
    }

    /**
     * Get store_ids
     * @return string|null
     */
    public function getStoreIds()
    {
        return $this->_get(self::STORE_IDS);
    }

    /**
     * Set store_ids
     * @param string $storeIds
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setStoreIds($storeIds)
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * Get dependant_addon_ids
     * @return string|null
     */
    public function getDependantAddonIds()
    {
        return $this->_get(self::DEPENDANT_ADDON_IDS);
    }

    /**
     * Set dependant_addon_ids
     * @param string $dependantAddonIds
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setDependantAddonIds($dependantAddonIds)
    {
        return $this->setData(self::DEPENDANT_ADDON_IDS, $dependantAddonIds);
    }

    /**
     * Get dependant_addon_blacklist
     * @return string|null
     */
    public function getDependantAddonBlacklist()
    {
        return $this->_get(self::DEPENDANT_ADDON_BLACKLIST);
    }

    /**
     * Set dependant_addon_blacklist
     * @param string $dependantAddonBlacklist
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setDependantAddonBlacklist($dependantAddonBlacklist)
    {
        return $this->setData(self::DEPENDANT_ADDON_BLACKLIST, $dependantAddonBlacklist);
    }

    /**
     * Get link_type
     * @return string|null
     */
    public function getLinkType()
    {
        return $this->_get(self::LINK_TYPE);
    }

    /**
     * Set link_type
     * @param string $linkType
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setLinkType($linkType)
    {
        return $this->setData(self::LINK_TYPE, $linkType);
    }

    /**
     * Get link_url
     * @return string|null
     */
    public function getLinkUrl()
    {
        return $this->_get(self::LINK_URL);
    }

    /**
     * Set link_url
     * @param string $linkUrl
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setLinkUrl($linkUrl)
    {
        return $this->setData(self::LINK_URL, $linkUrl);
    }

    /**
     * Get link_static_block_id
     * @return string|null
     */
    public function getLinkStaticBlockId()
    {
        return $this->_get(self::LINK_STATIC_BLOCK_ID);
    }

    /**
     * Set link_static_block_id
     * @param string $linkStaticBlockId
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setLinkStaticBlockId($linkStaticBlockId)
    {
        return $this->setData(self::LINK_STATIC_BLOCK_ID, $linkStaticBlockId);
    }

    /**
     * Get link_text
     * @return string|null
     */
    public function getLinkText()
    {
        return $this->_get(self::LINK_TEXT);
    }

    /**
     * Set link_text
     * @param string $linkText
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setLinkText($linkText)
    {
        return $this->setData(self::LINK_TEXT, $linkText);
    }

    /**
     * Get link_style
     * @return string|null
     */
    public function getLinkStyle()
    {
        return $this->_get(self::LINK_STYLE);
    }

    /**
     * Set link_style
     * @param string $linkStyle
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setLinkStyle($linkStyle)
    {
        return $this->setData(self::LINK_STYLE, $linkStyle);
    }

    /**
     * Get promotion_message
     * @return string|null
     */
    public function getPromotionMessage()
    {
        return $this->_get(self::PROMOTION_MESSAGE);
    }

    /**
     * Set promotion_message
     * @param string $promotionMessage
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setPromotionMessage($promotionMessage)
    {
        return $this->setData(self::PROMOTION_MESSAGE, $promotionMessage);
    }

    /**
     * Get enable_postcode
     * @return string|null
     */
    public function getEnablePostcode()
    {
        return $this->_get(self::ENABLE_POSTCODE);
    }

    /**
     * Set enable_postcode
     * @param string $enablePostcode
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setEnablePostcode($enablePostcode)
    {
        return $this->setData(self::ENABLE_POSTCODE, $enablePostcode);
    }

    /**
     * Get enable_countdown_timer
     * @return string|null
     */
    public function getEnableCountdownTimer()
    {
        return $this->_get(self::ENABLE_COUNTDOWN_TIMER);
    }

    /**
     * Set enable_countdown_timer
     * @param string $enableCountdownTimer
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setEnableCountdownTimer($enableCountdownTimer)
    {
        return $this->setData(self::ENABLE_COUNTDOWN_TIMER, $enableCountdownTimer);
    }

    /**
     * Get countdown_time
     * @return string|null
     */
    public function getCountdownTime()
    {
        return $this->_get(self::COUNTDOWN_TIME);
    }

    /**
     * Set countdown_time
     * @param string $countdownTime
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCountdownTime($countdownTime)
    {
        return $this->setData(self::COUNTDOWN_TIME, $countdownTime);
    }

    /**
     * Get countdown_background_colour
     * @return string|null
     */
    public function getCountdownBackgroundColour()
    {
        return $this->_get(self::COUNTDOWN_BACKGROUND_COLOUR);
    }

    /**
     * Set countdown_background_colour
     * @param string $countdownBackgroundColour
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCountdownBackgroundColour($countdownBackgroundColour)
    {
        return $this->setData(self::COUNTDOWN_BACKGROUND_COLOUR, $countdownBackgroundColour);
    }

    /**
     * Get countdown_font_colour
     * @return string|null
     */
    public function getCountdownFontColour()
    {
        return $this->_get(self::COUNTDOWN_FONT_COLOUR);
    }

    /**
     * Set countdown_font_colour
     * @param string $countdownFontColour
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCountdownFontColour($countdownFontColour)
    {
        return $this->setData(self::COUNTDOWN_FONT_COLOUR, $countdownFontColour);
    }

    /**
     * Get countdown_font_colour_overlay
     * @return string|null
     */
    public function getCountdownFontColourOverlay()
    {
        return $this->_get(self::COUNTDOWN_FONT_COLOUR_OVERLAY);
    }

    /**
     * Set countdown_font_colour_overlay
     * @param string $countdownFontColourOverlay
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCountdownFontColourOverlay($countdownFontColourOverlay)
    {
        return $this->setData(self::COUNTDOWN_FONT_COLOUR_OVERLAY, $countdownFontColourOverlay);
    }

    /**
     * Get enable_image_overlay_banner
     * @return string|null
     */
    public function getEnableImageOverlayBanner()
    {
        return $this->_get(self::ENABLE_IMAGE_OVERLAY_BANNER);
    }

    /**
     * Set enable_image_overlay_banner
     * @param string $enableImageOverlayBanner
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setEnableImageOverlayBanner($enableImageOverlayBanner)
    {
        return $this->setData(self::ENABLE_IMAGE_OVERLAY_BANNER, $enableImageOverlayBanner);
    }

    /**
     * Get custom_icon
     * @return string|null
     */
    public function getCustomIcon()
    {
        return $this->_get(self::CUSTOM_ICON);
    }

    /**
     * Set custom_icon
     * @param string $customIcon
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCustomIcon($customIcon)
    {
        return $this->setData(self::CUSTOM_ICON, $customIcon);
    }

    /**
     * Get enable_quantity
     * @return string|null
     */
    public function getEnableQuantity()
    {
        return $this->_get(self::ENABLE_QUANTITY);
    }

    /**
     * Set enable_quantity
     * @param string $enableQuantity
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setEnableQuantity($enableQuantity)
    {
        return $this->setData(self::ENABLE_QUANTITY, $enableQuantity);
    }

    /**
     * Get match_quantity
     * @return string|null
     */
    public function getMatchQuantity()
    {
        return $this->_get(self::MATCH_QUANTITY);
    }

    /**
     * Set match_quantity
     * @param string $matchQuantity
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setMatchQuantity($matchQuantity)
    {
        return $this->setData(self::MATCH_QUANTITY, $matchQuantity);
    }

    /**
     * Get cap_qty_type
     * @return string|null
     */
    public function getCapQtyType()
    {
        return $this->_get(self::CAP_QTY_TYPE);
    }

    /**
     * Set cap_qty_type
     * @param string $capQtyType
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCapQtyType($capQtyType)
    {
        return $this->setData(self::CAP_QTY_TYPE, $capQtyType);
    }

    /**
     * Get cap_qty_amount
     * @return string|null
     */
    public function getCapQtyAmount()
    {
        return $this->_get(self::CAP_QTY_AMOUNT);
    }

    /**
     * Set cap_qty_amount
     * @param string $capQtyAmount
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setCapQtyAmount($capQtyAmount)
    {
        return $this->setData(self::CAP_QTY_AMOUNT, $capQtyAmount);
    }

    /**
     * Get force_free_shipping
     * @return string|null
     */
    public function getForceFreeShipping()
    {
        return $this->_get(self::FORCE_FREE_SHIPPING);
    }

    /**
     * Set force_free_shipping
     * @param string $forceFreeShipping
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setForceFreeShipping($forceFreeShipping)
    {
        return $this->setData(self::FORCE_FREE_SHIPPING, $forceFreeShipping);
    }

    /**
     * Get discount_amount
     * @return string|null
     */
    public function getDiscountAmount()
    {
        return $this->_get(self::DISCOUNT_AMOUNT);
    }

    /**
     * Set discount_amount
     * @param string $discountAmount
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setDiscountAmount($discountAmount)
    {
        return $this->setData(self::DISCOUNT_AMOUNT, $discountAmount);
    }

    /**
     * Get discount_percentage
     * @return string|null
     */
    public function getDiscountPercentage()
    {
        return $this->_get(self::DISCOUNT_PERCENTAGE);
    }

    /**
     * Set discount_percentage
     * @param string $discountPercentage
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setDiscountPercentage($discountPercentage)
    {
        return $this->setData(self::DISCOUNT_PERCENTAGE, $discountPercentage);
    }

    /**
     * Get discount_rule_id
     * @return string|null
     */
    public function getDiscountRuleId()
    {
        return $this->_get(self::DISCOUNT_RULE_ID);
    }

    /**
     * Set discount_rule_id
     * @param string $discountRuleId
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setDiscountRuleId($discountRuleId)
    {
        return $this->setData(self::DISCOUNT_RULE_ID, $discountRuleId);
    }

    /**
     * Get position
     * @return string|null
     */
    public function getPosition()
    {
        return $this->_get(self::POSITION);
    }

    /**
     * Set position
     * @param string $position
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }
}

