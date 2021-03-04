<?php
namespace Elevate\LandingPages\Model\ResourceModel;

class LandingPageAttribute extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('elevate_landingpages_attributes','landingpage_attributes_id');
    }
}
