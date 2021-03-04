<?php


namespace Elevate\Microsite\Model\ResourceModel;

class Microsite extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_microsite', 'microsite_id');
    }
}
