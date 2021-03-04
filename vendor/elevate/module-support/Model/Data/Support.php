<?php


namespace Elevate\Support\Model\Data;

use Elevate\Support\Api\Data\SupportInterface;

class Support extends \Magento\Framework\Api\AbstractExtensibleObject implements SupportInterface
{

    /**
     * Get support_id
     * @return string|null
     */
    public function getSupportId()
    {
        return $this->_get(self::SUPPORT_ID);
    }

    /**
     * Set support_id
     * @param string $supportId
     * @return \Elevate\Support\Api\Data\SupportInterface
     */
    public function setSupportId($supportId)
    {
        return $this->setData(self::SUPPORT_ID, $supportId);
    }

    /**
     * Get operating_system
     * @return string|null
     */
    public function getOperatingSystem()
    {
        return $this->_get(self::OPERATING_SYSTEM);
    }

    /**
     * Set operating_system
     * @param string $operatingSystem
     * @return \Elevate\Support\Api\Data\SupportInterface
     */
    public function setOperatingSystem($operatingSystem)
    {
        return $this->setData(self::OPERATING_SYSTEM, $operatingSystem);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Support\Api\Data\SupportExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Support\Api\Data\SupportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Support\Api\Data\SupportExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
