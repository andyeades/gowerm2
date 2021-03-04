<?php


namespace Elevate\Microsite\Api\Data;

interface MicrositeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRICE_DISPLAY_TYPE = 'price_display_type';
    const MICROSITE_DESCRIPTION = 'microsite_description';
    const MICROSITE_NOTES = 'microsite_notes';
    const MICROSITE_URL = 'microsite_url';
    const CREATED_AT = 'created_at';
    const FORCE_LOGIN_EMAIL = 'force_login_email';
    const MICROSITE_ID = 'microsite_id';
    const UPDATED_AT = 'updated_at';
    const MICROSITE_CONTACT_EMAIL = 'microsite_contact_email';
    const SELECTED_THEME = 'selected_theme';
    const ID = 'id';
    const MICROSITE_NAME = 'microsite_name';
    const HEADER_DYNAMIC_FIELDS_ID = 'header_dynamic_fields_id';
    const WHITELIST_IPS = 'whitelist_ips';
    const MAIN_LOGO = 'main_logo';
    const LOGIN_SPLASH_SCREEN = 'login_splash_screen';
    const FOOTER_DYNAMIC_FIELDS_ID = 'footer_dynamic_fields_id';
    const MICROSITE_CONTACT_NAME = 'microsite_contact_name';
    const MICROSITE_CONTACT_NUMBER = 'microsite_contact_number';
    const LOGIN_BY_CUSTOMER = 'login_by_customer';

    /**
     * Get microsite_id
     * @return string|null
     */
    public function getMicrositeId();

    /**
     * Set microsite_id
     * @param string $micrositeId
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeId($micrositeId);

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Microsite\Api\Data\MicrositeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Microsite\Api\Data\MicrositeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Microsite\Api\Data\MicrositeExtensionInterface $extensionAttributes
    );

    /**
     * Get microsite_url
     * @return string|null
     */
    public function getMicrositeUrl();

    /**
     * Set microsite_url
     * @param string $micrositeUrl
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeUrl($micrositeUrl);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
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
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get microsite_name
     * @return string|null
     */
    public function getMicrositeName();

    /**
     * Set microsite_name
     * @param string $micrositeName
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeName($micrositeName);

    /**
     * Get microsite_description
     * @return string|null
     */
    public function getMicrositeDescription();

    /**
     * Set microsite_description
     * @param string $micrositeDescription
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeDescription($micrositeDescription);

    /**
     * Get microsite_contact_name
     * @return string|null
     */
    public function getMicrositeContactName();

    /**
     * Set microsite_contact_name
     * @param string $micrositeContactName
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeContactName($micrositeContactName);

    /**
     * Get microsite_contact_number
     * @return string|null
     */
    public function getMicrositeContactNumber();

    /**
     * Set microsite_contact_number
     * @param string $micrositeContactNumber
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeContactNumber($micrositeContactNumber);

    /**
     * Get microsite_contact_email
     * @return string|null
     */
    public function getMicrositeContactEmail();

    /**
     * Set microsite_contact_email
     * @param string $micrositeContactEmail
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeContactEmail($micrositeContactEmail);

    /**
     * Get microsite_notes
     * @return string|null
     */
    public function getMicrositeNotes();

    /**
     * Set microsite_notes
     * @param string $micrositeNotes
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMicrositeNotes($micrositeNotes);

    /**
     * Get main_logo
     * @return string|null
     */
    public function getMainLogo();

    /**
     * Set main_logo
     * @param string $mainLogo
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setMainLogo($mainLogo);

    /**
     * Get price_display_type
     * @return string|null
     */
    public function getPriceDisplayType();

    /**
     * Set price_display_type
     * @param string $priceDisplayType
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setPriceDisplayType($priceDisplayType);

    /**
     * Get selected_theme
     * @return string|null
     */
    public function getSelectedTheme();

    /**
     * Set selected_theme
     * @param string $selectedTheme
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setSelectedTheme($selectedTheme);

    /**
     * Get login_by_customer
     * @return string|null
     */
    public function getLoginByCustomer();

    /**
     * Set login_by_customer
     * @param string $loginByCustomer
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setLoginByCustomer($loginByCustomer);

    /**
     * Get login_splash_screen
     * @return string|null
     */
    public function getLoginSplashScreen();

    /**
     * Set login_splash_screen
     * @param string $loginSplashScreen
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setLoginSplashScreen($loginSplashScreen);

    /**
     * Get force_login_email
     * @return string|null
     */
    public function getForceLoginEmail();

    /**
     * Set force_login_email
     * @param string $forceLoginEmail
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setForceLoginEmail($forceLoginEmail);

    /**
     * Get whitelist_ips
     * @return string|null
     */
    public function getWhitelistIps();

    /**
     * Set whitelist_ips
     * @param string $whitelistIps
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setWhitelistIps($whitelistIps);

    /**
     * Get header_dynamic_fields_id
     * @return string|null
     */
    public function getHeaderDynamicFieldsId();

    /**
     * Set header_dynamic_fields_id
     * @param string $headerDynamicFieldsId
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setHeaderDynamicFieldsId($headerDynamicFieldsId);

    /**
     * Get footer_dynamic_fields_id
     * @return string|null
     */
    public function getFooterDynamicFieldsId();

    /**
     * Set footer_dynamic_fields_id
     * @param string $footerDynamicFieldsId
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     */
    public function setFooterDynamicFieldsId($footerDynamicFieldsId);
}