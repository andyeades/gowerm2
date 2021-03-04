<?php
namespace Elevate\LandingPages\Model;
class LandingPageAttribute extends \Magento\Framework\Model\AbstractModel implements \Elevate\LandingPages\Api\Data\LandingPageAttributeInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'elevate_landingpages_landingpage_attributes';

    protected function _construct()
    {
        $this->_init('Elevate\LandingPages\Model\ResourceModel\LandingPageAttribute');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
