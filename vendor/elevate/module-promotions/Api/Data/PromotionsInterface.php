<?php


namespace Elevate\Promotions\Api\Data;

interface PromotionsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const SITEWIDE_ENABLE = 'sitewide_enable';
    const SITEWIDE_LINK = 'sitewide_link';
    const SITEWIDE_BACKGROUND = 'sitewide_background';
    const SITEWIDE_MAIN_TEXT = 'sitewide_main_text';
    const SITEWIDE_TYPE = 'sitewide_type';
    const SITEWIDE_PILL_COLOUR = 'sitewide_pill_colour';
    const SITEWIDE_CUSTOM_BLOCK = 'sitewide_custom_block';
    const SITEWIDE_PILL_TEXT = 'sitewide_pill_text';
    const PROMOTIONS_ID = 'promotions_id';
    const SITEWIDE_INVERT_ICONS = 'sitewide_invert_icons';
    const SITEWIDE_MAIN_TEXT_COLOUR = 'sitewide_main_text_colour';
    const SITEWIDE_MAINSUBTEXT = 'sitewide_mainsubtext';

    /**
     * Get promotions_id
     * @return string|null
     */
    public function getPromotionsId();

    /**
     * Set promotions_id
     * @param string $promotionsId
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setPromotionsId($promotionsId);

    /**
     * Get sitewide_enable
     * @return string|null
     */
    public function getSitewideEnable();

    /**
     * Set sitewide_enable
     * @param string $sitewideEnable
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideEnable($sitewideEnable);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Promotions\Api\Data\PromotionsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Promotions\Api\Data\PromotionsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Promotions\Api\Data\PromotionsExtensionInterface $extensionAttributes
    );

    /**
     * Get sitewide_type
     * @return string|null
     */
    public function getSitewideType();

    /**
     * Set sitewide_type
     * @param string $sitewideType
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideType($sitewideType);

    /**
     * Get sitewide_link
     * @return string|null
     */
    public function getSitewideLink();

    /**
     * Set sitewide_link
     * @param string $sitewideLink
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideLink($sitewideLink);

    /**
     * Get sitewide_background
     * @return string|null
     */
    public function getSitewideBackground();

    /**
     * Set sitewide_background
     * @param string $sitewideBackground
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideBackground($sitewideBackground);

    /**
     * Get sitewide_custom_block
     * @return string|null
     */
    public function getSitewideCustomBlock();

    /**
     * Set sitewide_custom_block
     * @param string $sitewideCustomBlock
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideCustomBlock($sitewideCustomBlock);

    /**
     * Get sitewide_main_text
     * @return string|null
     */
    public function getSitewideMainText();

    /**
     * Set sitewide_main_text
     * @param string $sitewideMainText
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideMainText($sitewideMainText);

    /**
     * Get sitewide_pill_colour
     * @return string|null
     */
    public function getSitewidePillColour();

    /**
     * Set sitewide_pill_colour
     * @param string $sitewidePillColour
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewidePillColour($sitewidePillColour);

    /**
     * Get sitewide_pill_text
     * @return string|null
     */
    public function getSitewidePillText();

    /**
     * Set sitewide_pill_text
     * @param string $sitewidePillText
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewidePillText($sitewidePillText);

    /**
     * Get sitewide_invert_icons
     * @return string|null
     */
    public function getSitewideInvertIcons();

    /**
     * Set sitewide_invert_icons
     * @param string $sitewideInvertIcons
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideInvertIcons($sitewideInvertIcons);

    /**
     * Get sitewide_main_text_colour
     * @return string|null
     */
    public function getSitewideMainTextColour();

    /**
     * Set sitewide_main_text_colour
     * @param string $sitewideMainTextColour
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideMainTextColour($sitewideMainTextColour);

    /**
     * Get sitewide_mainsubtext
     * @return string|null
     */
    public function getSitewideMainsubtext();

    /**
     * Set sitewide_mainsubtext
     * @param string $sitewideMainsubtext
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideMainsubtext($sitewideMainsubtext);
}
