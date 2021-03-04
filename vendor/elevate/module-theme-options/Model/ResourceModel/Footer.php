<?php


namespace Elevate\Themeoptions\Model\ResourceModel;

class Footer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_themeoptions_footer', 'entity_id');
    }
}
