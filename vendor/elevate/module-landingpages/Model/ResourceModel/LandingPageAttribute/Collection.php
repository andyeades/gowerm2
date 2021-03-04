<?php
namespace Elevate\LandingPages\Model\ResourceModel\LandingPageAttribute;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'landingpage_attributes_id';

    protected function _construct()
    {
        $this->_init('Elevate\LandingPages\Model\LandingPageAttribute','Elevate\LandingPages\Model\ResourceModel\LandingPageAttribute');
    }
  
}
