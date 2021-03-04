<?php


namespace Elevate\Support\Api\Data;

interface SupportInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const SUPPORT_ID = 'support_id';
    const OPERATING_SYSTEM = 'operating_system';

    /**
     * Get support_id
     * @return string|null
     */
    public function getSupportId();

    /**
     * Set support_id
     * @param string $supportId
     * @return \Elevate\Support\Api\Data\SupportInterface
     */
    public function setSupportId($supportId);

    /**
     * Get operating_system
     * @return string|null
     */
    public function getOperatingSystem();

    /**
     * Set operating_system
     * @param string $operatingSystem
     * @return \Elevate\Support\Api\Data\SupportInterface
     */
    public function setOperatingSystem($operatingSystem);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Support\Api\Data\SupportExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Support\Api\Data\SupportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Support\Api\Data\SupportExtensionInterface $extensionAttributes
    );
}
