<?php


namespace Elevate\Microsite\Model\Data;

use Elevate\Microsite\Api\Data\MicrositeInterface;

class Microsite extends \Magento\Framework\Api\AbstractExtensibleObject implements MicrositeInterface
{

    /**
     * Get microsite_id
     * @return string|null
     */
    public function getMicrositeId()
    {
        return $this->_get(self::MICROSITE_ID);
    }

    /**
     * Set microsite_id
     * @param string $micrositeId
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeId($micrositeId)
    {
        return $this->setData(self::MICROSITE_ID, $micrositeId);
    }

    /**
     * Get id
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * Set id
     * @param string $id
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Microsite\Api\Data\MicrositeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Microsite\Api\Data\MicrositeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Microsite\Api\Data\MicrositeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get microsite_url
     * @return string|null
     */
    public function getMicrositeUrl()
    {
        return $this->_get(self::MICROSITE_URL);
    }

    /**
     * Set microsite_url
     * @param string $micrositeUrl
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeUrl($micrositeUrl)
    {
        return $this->setData(self::MICROSITE_URL, $micrositeUrl);
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
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
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
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get microsite_name
     * @return string|null
     */
    public function getMicrositeName()
    {
        return $this->_get(self::MICROSITE_NAME);
    }

    /**
     * Set microsite_name
     * @param string $micrositeName
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeName($micrositeName)
    {
        return $this->setData(self::MICROSITE_NAME, $micrositeName);
    }

    /**
     * Get microsite_description
     * @return string|null
     */
    public function getMicrositeDescription()
    {
        return $this->_get(self::MICROSITE_DESCRIPTION);
    }

    /**
     * Set microsite_description
     * @param string $micrositeDescription
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeDescription($micrositeDescription)
    {
        return $this->setData(self::MICROSITE_DESCRIPTION, $micrositeDescription);
    }

    /**
     * Get microsite_contact_name
     * @return string|null
     */
    public function getMicrositeContactName()
    {
        return $this->_get(self::MICROSITE_CONTACT_NAME);
    }

    /**
     * Set microsite_contact_name
     * @param string $micrositeContactName
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeContactName($micrositeContactName)
    {
        return $this->setData(self::MICROSITE_CONTACT_NAME, $micrositeContactName);
    }

    /**
     * Get microsite_contact_number
     * @return string|null
     */
    public function getMicrositeContactNumber()
    {
        return $this->_get(self::MICROSITE_CONTACT_NUMBER);
    }

    /**
     * Set microsite_contact_number
     * @param string $micrositeContactNumber
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeContactNumber($micrositeContactNumber)
    {
        return $this->setData(self::MICROSITE_CONTACT_NUMBER, $micrositeContactNumber);
    }

    /**
     * Get microsite_contact_email
     * @return string|null
     */
    public function getMicrositeContactEmail()
    {
        return $this->_get(self::MICROSITE_CONTACT_EMAIL);
    }

    /**
     * Set microsite_contact_email
     * @param string $micrositeContactEmail
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeContactEmail($micrositeContactEmail)
    {
        return $this->setData(self::MICROSITE_CONTACT_EMAIL, $micrositeContactEmail);
    }

    /**
     * Get microsite_notes
     * @return string|null
     */
    public function getMicrositeNotes()
    {
        return $this->_get(self::MICROSITE_NOTES);
    }

    /**
     * Set microsite_notes
     * @param string $micrositeNotes
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeNotes($micrositeNotes)
    {
        return $this->setData(self::MICROSITE_NOTES, $micrositeNotes);
    }

    /**
     * Get main_logo
     * @return string|null
     */
    public function getMainLogo()
    {
        return $this->_get(self::MAIN_LOGO);
    }

    /**
     * Set main_logo
     * @param string $mainLogo
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMainLogo($mainLogo)
    {
        return $this->setData(self::MAIN_LOGO, $mainLogo);
    }

    /**
     * Get price_display_type
     * @return string|null
     */
    public function getPriceDisplayType()
    {
        return $this->_get(self::PRICE_DISPLAY_TYPE);
    }

    /**
     * Set price_display_type
     * @param string $priceDisplayType
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setPriceDisplayType($priceDisplayType)
    {
        return $this->setData(self::PRICE_DISPLAY_TYPE, $priceDisplayType);
    }

    /**
     * Get selected_theme
     * @return string|null
     */
    public function getSelectedTheme()
    {
        return $this->_get(self::SELECTED_THEME);
    }

    /**
     * Set selected_theme
     * @param string $selectedTheme
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setSelectedTheme($selectedTheme)
    {
        return $this->setData(self::SELECTED_THEME, $selectedTheme);
    }

    /**
     * Get login_by_customer
     * @return string|null
     */
    public function getLoginByCustomer()
    {
        return $this->_get(self::LOGIN_BY_CUSTOMER);
    }

    /**
     * Set login_by_customer
     * @param string $loginByCustomer
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setLoginByCustomer($loginByCustomer)
    {
        return $this->setData(self::LOGIN_BY_CUSTOMER, $loginByCustomer);
    }

    /**
     * Get login_splash_screen
     * @return string|null
     */
    public function getLoginSplashScreen()
    {
        return $this->_get(self::LOGIN_SPLASH_SCREEN);
    }

    /**
     * Set login_splash_screen
     * @param string $loginSplashScreen
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setLoginSplashScreen($loginSplashScreen)
    {
        return $this->setData(self::LOGIN_SPLASH_SCREEN, $loginSplashScreen);
    }

    /**
     * Get force_login_email
     * @return string|null
     */
    public function getForceLoginEmail()
    {
        return $this->_get(self::FORCE_LOGIN_EMAIL);
    }

    /**
     * Set force_login_email
     * @param string $forceLoginEmail
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setForceLoginEmail($forceLoginEmail)
    {
        return $this->setData(self::FORCE_LOGIN_EMAIL, $forceLoginEmail);
    }

    /**
     * Get whitelist_ips
     * @return string|null
     */
    public function getWhitelistIps()
    {
        return $this->_get(self::WHITELIST_IPS);
    }

    /**
     * Set whitelist_ips
     * @param string $whitelistIps
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setWhitelistIps($whitelistIps)
    {
        return $this->setData(self::WHITELIST_IPS, $whitelistIps);
    }

    /**
     * Get header_dynamic_fields_id
     * @return string|null
     */
    public function getHeaderDynamicFieldsId()
    {
        return $this->_get(self::HEADER_DYNAMIC_FIELDS_ID);
    }

    /**
     * Set header_dynamic_fields_id
     * @param string $headerDynamicFieldsId
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setHeaderDynamicFieldsId($headerDynamicFieldsId)
    {
        return $this->setData(self::HEADER_DYNAMIC_FIELDS_ID, $headerDynamicFieldsId);
    }

    /**
     * Get footer_dynamic_fields_id
     * @return string|null
     */
    public function getFooterDynamicFieldsId()
    {
        return $this->_get(self::FOOTER_DYNAMIC_FIELDS_ID);
    }

    /**
     * Set footer_dynamic_fields_id
     * @param string $footerDynamicFieldsId
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setFooterDynamicFieldsId($footerDynamicFieldsId)
    {
        return $this->setData(self::FOOTER_DYNAMIC_FIELDS_ID, $footerDynamicFieldsId);
    }
}