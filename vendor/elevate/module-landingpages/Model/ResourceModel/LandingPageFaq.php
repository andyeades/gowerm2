<?php
namespace Elevate\LandingPages\Model\ResourceModel;

class LandingPageFaq extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('elevate_landingpages_schema_faq','landingpage_faq_id');
    }
}
