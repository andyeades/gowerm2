<?php
namespace Elevate\LandingPages\Model\ResourceModel\LandingPageFaq;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'landingpage_faq_id';

    protected function _construct()
    {
        $this->_init('Elevate\LandingPages\Model\LandingPageFaq','Elevate\LandingPages\Model\ResourceModel\LandingPageFaq');
    }
  
}
