<?php


namespace Elevate\Promotions\Model\Data;

use Elevate\Promotions\Api\Data\PromotionsInterface;

class Promotions extends \Magento\Framework\Api\AbstractExtensibleObject implements PromotionsInterface
{

    /**
     * Get promotions_id
     * @return string|null
     */
    public function getPromotionsId()
    {
        return $this->_get(self::PROMOTIONS_ID);
    }

    /**
     * Set promotions_id
     * @param string $promotionsId
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setPromotionsId($promotionsId)
    {
        return $this->setData(self::PROMOTIONS_ID, $promotionsId);
    }

    /**
     * Get sitewide_enable
     * @return string|null
     */
    public function getSitewideEnable()
    {
        return $this->_get(self::SITEWIDE_ENABLE);
    }

    /**
     * Set sitewide_enable
     * @param string $sitewideEnable
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideEnable($sitewideEnable)
    {
        return $this->setData(self::SITEWIDE_ENABLE, $sitewideEnable);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Promotions\Api\Data\PromotionsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Promotions\Api\Data\PromotionsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Promotions\Api\Data\PromotionsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get sitewide_type
     * @return string|null
     */
    public function getSitewideType()
    {
        return $this->_get(self::SITEWIDE_TYPE);
    }

    /**
     * Set sitewide_type
     * @param string $sitewideType
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideType($sitewideType)
    {
        return $this->setData(self::SITEWIDE_TYPE, $sitewideType);
    }

    /**
     * Get sitewide_link
     * @return string|null
     */
    public function getSitewideLink()
    {
        return $this->_get(self::SITEWIDE_LINK);
    }

    /**
     * Set sitewide_link
     * @param string $sitewideLink
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideLink($sitewideLink)
    {
        return $this->setData(self::SITEWIDE_LINK, $sitewideLink);
    }

    /**
     * Get sitewide_background
     * @return string|null
     */
    public function getSitewideBackground()
    {
        return $this->_get(self::SITEWIDE_BACKGROUND);
    }

    /**
     * Set sitewide_background
     * @param string $sitewideBackground
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideBackground($sitewideBackground)
    {
        return $this->setData(self::SITEWIDE_BACKGROUND, $sitewideBackground);
    }

    /**
     * Get sitewide_custom_block
     * @return string|null
     */
    public function getSitewideCustomBlock()
    {
        return $this->_get(self::SITEWIDE_CUSTOM_BLOCK);
    }

    /**
     * Set sitewide_custom_block
     * @param string $sitewideCustomBlock
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideCustomBlock($sitewideCustomBlock)
    {
        return $this->setData(self::SITEWIDE_CUSTOM_BLOCK, $sitewideCustomBlock);
    }

    /**
     * Get sitewide_main_text
     * @return string|null
     */
    public function getSitewideMainText()
    {
        return $this->_get(self::SITEWIDE_MAIN_TEXT);
    }

    /**
     * Set sitewide_main_text
     * @param string $sitewideMainText
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideMainText($sitewideMainText)
    {
        return $this->setData(self::SITEWIDE_MAIN_TEXT, $sitewideMainText);
    }

    /**
     * Get sitewide_pill_colour
     * @return string|null
     */
    public function getSitewidePillColour()
    {
        return $this->_get(self::SITEWIDE_PILL_COLOUR);
    }

    /**
     * Set sitewide_pill_colour
     * @param string $sitewidePillColour
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewidePillColour($sitewidePillColour)
    {
        return $this->setData(self::SITEWIDE_PILL_COLOUR, $sitewidePillColour);
    }

    /**
     * Get sitewide_pill_text
     * @return string|null
     */
    public function getSitewidePillText()
    {
        return $this->_get(self::SITEWIDE_PILL_TEXT);
    }

    /**
     * Set sitewide_pill_text
     * @param string $sitewidePillText
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewidePillText($sitewidePillText)
    {
        return $this->setData(self::SITEWIDE_PILL_TEXT, $sitewidePillText);
    }

    /**
     * Get sitewide_invert_icons
     * @return string|null
     */
    public function getSitewideInvertIcons()
    {
        return $this->_get(self::SITEWIDE_INVERT_ICONS);
    }

    /**
     * Set sitewide_invert_icons
     * @param string $sitewideInvertIcons
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideInvertIcons($sitewideInvertIcons)
    {
        return $this->setData(self::SITEWIDE_INVERT_ICONS, $sitewideInvertIcons);
    }

    /**
     * Get sitewide_main_text_colour
     * @return string|null
     */
    public function getSitewideMainTextColour()
    {
        return $this->_get(self::SITEWIDE_MAIN_TEXT_COLOUR);
    }

    /**
     * Set sitewide_main_text_colour
     * @param string $sitewideMainTextColour
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideMainTextColour($sitewideMainTextColour)
    {
        return $this->setData(self::SITEWIDE_MAIN_TEXT_COLOUR, $sitewideMainTextColour);
    }

    /**
     * Get sitewide_mainsubtext
     * @return string|null
     */
    public function getSitewideMainsubtext()
    {
        return $this->_get(self::SITEWIDE_MAINSUBTEXT);
    }

    /**
     * Set sitewide_mainsubtext
     * @param string $sitewideMainsubtext
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     */
    public function setSitewideMainsubtext($sitewideMainsubtext)
    {
        return $this->setData(self::SITEWIDE_MAINSUBTEXT, $sitewideMainsubtext);
    }
}
