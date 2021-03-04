<?php


namespace Elevate\Support\Model\ResourceModel;

class Support extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_support_support', 'support_id');
    }
}
