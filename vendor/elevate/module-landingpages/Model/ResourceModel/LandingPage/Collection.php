<?php
namespace Elevate\LandingPages\Model\ResourceModel\LandingPage;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'landingpage_id';

    protected function _construct()
    {
        $this->_init('Elevate\LandingPages\Model\LandingPage','Elevate\LandingPages\Model\ResourceModel\LandingPage');
    }
  
}
