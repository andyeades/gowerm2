<?php


namespace Elevate\Promotions\Model\ResourceModel;

class Promotions extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_promotions_promotions', 'promotions_id');
    }
}
