<?php


namespace Elevate\Assignments\Api\Data;

interface Addon extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRODUCT_BLACKLIST = 'product_blacklist';
    const PRODUCT_MATCH_ATTRIBUTE_ONE_CHILD = 'product_match_attribute_one_child';
    const ENABLE_COUNTDOWN_TIMER = 'enable_countdown_timer';
    const PRODUCT_MATCH_ATTRIBUTE_ONE_PARENT = 'product_match_attribute_one_parent';
    const CAP_QTY_AMOUNT = 'cap_qty_amount';
    const ADDON_ID = 'addon_id';
    const STORE_IDS = 'store_ids';
    const CATEGORIES_BLACKLIST = 'categories_blacklist';
    const DISCOUNT_AMOUNT = 'discount_amount';
    const DISCOUNT_PERCENTAGE = 'discount_percentage';
    const LINK_STYLE = 'link_style';
    const ENABLE_POSTCODE = 'enable_postcode';
    const POSITION = 'position';
    const COUNTDOWN_TIME = 'countdown_time';
    const ID = 'id';
    const TEMPLATE_ID = 'template_id';
    const ENABLE_ADDON = 'enable_addon';
    const ENABLE_IMAGE_OVERLAY_BANNER = 'enable_image_overlay_banner';
    const COUNTDOWN_FONT_COLOUR_OVERLAY = 'countdown_font_colour_overlay';
    const LIGHTBOX_TITLE = 'lightbox_title';
    const END_DATE = 'end_date';
    const PROMOTION_MESSAGE = 'promotion_message';
    const FORCE_FREE_SHIPPING = 'force_free_shipping';
    const ASSIGNED_CATEGORIES = 'assigned_categories';
    const LINK_URL = 'link_url';
    const LINK_TYPE = 'link_type';
    const SKU = 'sku';
    const START_DATE = 'start_date';
    const TITLE = 'title';
    const CONDITIONS = 'conditions';
    const ASSIGNED_SKUS = 'assigned_skus';
    const DESCRIPTION = 'description';
    const CUSTOM_ICON = 'custom_icon';
    const CAP_QTY_TYPE = 'cap_qty_type';
    const LIGHTBOX_FOOTER = 'lightbox_footer';
    const DEPENDANT_ADDON_BLACKLIST = 'dependant_addon_blacklist';
    const DEPENDANT_ADDON_IDS = 'dependant_addon_ids';
    const LINK_STATIC_BLOCK_ID = 'link_static_block_id';
    const ADDON_TYPE = 'addon_type';
    const COUNTDOWN_FONT_COLOUR = 'countdown_font_colour';
    const DISCOUNT_RULE_ID = 'discount_rule_id';
    const MATCH_QUANTITY = 'match_quantity';
    const COUNTDOWN_BACKGROUND_COLOUR = 'countdown_background_colour';
    const PRODUCT_MATCH_ATTRIBUTE_TWO_CHILD = 'product_match_attribute_two_child';
    const PRODUCT_MATCH_ATTRIBUTE_TWO_PARENT = 'product_match_attribute_two_parent';
    const LINK_TEXT = 'link_text';
    const ENABLE_QUANTITY = 'enable_quantity';

    /**
     * Get addon_id
     * @return string|null
     */
    public function getAddonId();

    /**
     * Set addon_id
     * @param string $addonId
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setAddonId($addonId);

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Assignments\Api\Data\AddonExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Assignments\Api\Data\AddonExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Assignments\Api\Data\AddonExtensionInterface $extensionAttributes
    );

    /**
     * Get enable_addon
     * @return string|null
     */
    public function getEnableAddon();

    /**
     * Set enable_addon
     * @param string $enableAddon
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setEnableAddon($enableAddon);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setTitle($title);

    /**
     * Get addon_type
     * @return string|null
     */
    public function getAddonType();

    /**
     * Set addon_type
     * @param string $addonType
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setAddonType($addonType);

    /**
     * Get template_id
     * @return string|null
     */
    public function getTemplateId();

    /**
     * Set template_id
     * @param string $templateId
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setTemplateId($templateId);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setDescription($description);

    /**
     * Get start_date
     * @return string|null
     */
    public function getStartDate();

    /**
     * Set start_date
     * @param string $startDate
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setStartDate($startDate);

    /**
     * Get end_date
     * @return string|null
     */
    public function getEndDate();

    /**
     * Set end_date
     * @param string $endDate
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setEndDate($endDate);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setSku($sku);

    /**
     * Get product_match_attribute_one_parent
     * @return string|null
     */
    public function getProductMatchAttributeOneParent();

    /**
     * Set product_match_attribute_one_parent
     * @param string $productMatchAttributeOneParent
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setProductMatchAttributeOneParent($productMatchAttributeOneParent);

    /**
     * Get product_match_attribute_one_child
     * @return string|null
     */
    public function getProductMatchAttributeOneChild();

    /**
     * Set product_match_attribute_one_child
     * @param string $productMatchAttributeOneChild
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setProductMatchAttributeOneChild($productMatchAttributeOneChild);

    /**
     * Get product_match_attribute_two_parent
     * @return string|null
     */
    public function getProductMatchAttributeTwoParent();

    /**
     * Set product_match_attribute_two_parent
     * @param string $productMatchAttributeTwoParent
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setProductMatchAttributeTwoParent($productMatchAttributeTwoParent);

    /**
     * Get product_match_attribute_two_child
     * @return string|null
     */
    public function getProductMatchAttributeTwoChild();

    /**
     * Set product_match_attribute_two_child
     * @param string $productMatchAttributeTwoChild
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setProductMatchAttributeTwoChild($productMatchAttributeTwoChild);

    /**
     * Get assigned_categories
     * @return string|null
     */
    public function getAssignedCategories();

    /**
     * Set assigned_categories
     * @param string $assignedCategories
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setAssignedCategories($assignedCategories);

    /**
     * Get conditions
     * @return string|null
     */
    public function getConditions();

    /**
     * Set conditions
     * @param string $conditions
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setConditions($conditions);

    /**
     * Get assigned_skus
     * @return string|null
     */
    public function getAssignedSkus();

    /**
     * Set assigned_skus
     * @param string $assignedSkus
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setAssignedSkus($assignedSkus);

    /**
     * Get categories_blacklist
     * @return string|null
     */
    public function getCategoriesBlacklist();

    /**
     * Set categories_blacklist
     * @param string $categoriesBlacklist
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCategoriesBlacklist($categoriesBlacklist);

    /**
     * Get product_blacklist
     * @return string|null
     */
    public function getProductBlacklist();

    /**
     * Set product_blacklist
     * @param string $productBlacklist
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setProductBlacklist($productBlacklist);

    /**
     * Get store_ids
     * @return string|null
     */
    public function getStoreIds();

    /**
     * Set store_ids
     * @param string $storeIds
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setStoreIds($storeIds);

    /**
     * Get dependant_addon_ids
     * @return string|null
     */
    public function getDependantAddonIds();

    /**
     * Set dependant_addon_ids
     * @param string $dependantAddonIds
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setDependantAddonIds($dependantAddonIds);

    /**
     * Get dependant_addon_blacklist
     * @return string|null
     */
    public function getDependantAddonBlacklist();

    /**
     * Set dependant_addon_blacklist
     * @param string $dependantAddonBlacklist
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setDependantAddonBlacklist($dependantAddonBlacklist);

    /**
     * Get link_type
     * @return string|null
     */
    public function getLinkType();

    /**
     * Set link_type
     * @param string $linkType
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setLinkType($linkType);

    /**
     * Get link_url
     * @return string|null
     */
    public function getLinkUrl();

    /**
     * Set link_url
     * @param string $linkUrl
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setLinkUrl($linkUrl);

    /**
     * Get link_static_block_id
     * @return string|null
     */
    public function getLinkStaticBlockId();

    /**
     * Set link_static_block_id
     * @param string $linkStaticBlockId
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setLinkStaticBlockId($linkStaticBlockId);

    /**
     * Get link_text
     * @return string|null
     */
    public function getLinkText();

    /**
     * Set link_text
     * @param string $linkText
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setLinkText($linkText);

    /**
     * Get link_style
     * @return string|null
     */
    public function getLinkStyle();

    /**
     * Set link_style
     * @param string $linkStyle
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setLinkStyle($linkStyle);

    /**
     * Get lightbox_title
     * @return string|null
     */
    public function getLightboxTitle();

    /**
     * Set lightbox_title
     * @param string $lightboxTitle
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setLightboxTitle($lightboxTitle);

    /**
     * Get lightbox_footer
     * @return string|null
     */
    public function getLightboxFooter();

    /**
     * Set lightbox_footer
     * @param string $lightboxFooter
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setLightboxFooter($lightboxFooter);

    /**
     * Get promotion_message
     * @return string|null
     */
    public function getPromotionMessage();

    /**
     * Set promotion_message
     * @param string $promotionMessage
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setPromotionMessage($promotionMessage);

    /**
     * Get enable_postcode
     * @return string|null
     */
    public function getEnablePostcode();

    /**
     * Set enable_postcode
     * @param string $enablePostcode
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setEnablePostcode($enablePostcode);

    /**
     * Get enable_countdown_timer
     * @return string|null
     */
    public function getEnableCountdownTimer();

    /**
     * Set enable_countdown_timer
     * @param string $enableCountdownTimer
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setEnableCountdownTimer($enableCountdownTimer);

    /**
     * Get countdown_time
     * @return string|null
     */
    public function getCountdownTime();

    /**
     * Set countdown_time
     * @param string $countdownTime
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCountdownTime($countdownTime);

    /**
     * Get countdown_background_colour
     * @return string|null
     */
    public function getCountdownBackgroundColour();

    /**
     * Set countdown_background_colour
     * @param string $countdownBackgroundColour
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCountdownBackgroundColour($countdownBackgroundColour);

    /**
     * Get countdown_font_colour
     * @return string|null
     */
    public function getCountdownFontColour();

    /**
     * Set countdown_font_colour
     * @param string $countdownFontColour
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCountdownFontColour($countdownFontColour);

    /**
     * Get countdown_font_colour_overlay
     * @return string|null
     */
    public function getCountdownFontColourOverlay();

    /**
     * Set countdown_font_colour_overlay
     * @param string $countdownFontColourOverlay
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCountdownFontColourOverlay($countdownFontColourOverlay);

    /**
     * Get enable_image_overlay_banner
     * @return string|null
     */
    public function getEnableImageOverlayBanner();

    /**
     * Set enable_image_overlay_banner
     * @param string $enableImageOverlayBanner
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setEnableImageOverlayBanner($enableImageOverlayBanner);

    /**
     * Get custom_icon
     * @return string|null
     */
    public function getCustomIcon();

    /**
     * Set custom_icon
     * @param string $customIcon
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCustomIcon($customIcon);

    /**
     * Get enable_quantity
     * @return string|null
     */
    public function getEnableQuantity();

    /**
     * Set enable_quantity
     * @param string $enableQuantity
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setEnableQuantity($enableQuantity);

    /**
     * Get match_quantity
     * @return string|null
     */
    public function getMatchQuantity();

    /**
     * Set match_quantity
     * @param string $matchQuantity
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setMatchQuantity($matchQuantity);

    /**
     * Get cap_qty_type
     * @return string|null
     */
    public function getCapQtyType();

    /**
     * Set cap_qty_type
     * @param string $capQtyType
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCapQtyType($capQtyType);

    /**
     * Get cap_qty_amount
     * @return string|null
     */
    public function getCapQtyAmount();

    /**
     * Set cap_qty_amount
     * @param string $capQtyAmount
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setCapQtyAmount($capQtyAmount);

    /**
     * Get force_free_shipping
     * @return string|null
     */
    public function getForceFreeShipping();

    /**
     * Set force_free_shipping
     * @param string $forceFreeShipping
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setForceFreeShipping($forceFreeShipping);

    /**
     * Get discount_amount
     * @return string|null
     */
    public function getDiscountAmount();

    /**
     * Set discount_amount
     * @param string $discountAmount
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setDiscountAmount($discountAmount);

    /**
     * Get discount_percentage
     * @return string|null
     */
    public function getDiscountPercentage();

    /**
     * Set discount_percentage
     * @param string $discountPercentage
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setDiscountPercentage($discountPercentage);

    /**
     * Get discount_rule_id
     * @return string|null
     */
    public function getDiscountRuleId();

    /**
     * Set discount_rule_id
     * @param string $discountRuleId
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setDiscountRuleId($discountRuleId);

    /**
     * Get position
     * @return string|null
     */
    public function getPosition();

    /**
     * Set position
     * @param string $position
     * @return \Elevate\Assignments\Api\Data\AddonInterface
     */
    public function setPosition($position);
}