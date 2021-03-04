<?php
namespace Elevate\LandingPages\Model\ResourceModel;

class LandingPage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('elevate_landingpages','landingpage_id');
    }
}
