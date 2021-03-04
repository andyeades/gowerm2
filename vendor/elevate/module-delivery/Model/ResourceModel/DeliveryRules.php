<?php


namespace Elevate\Delivery\Model\ResourceModel;

class DeliveryRules extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_delivery_deliveryrules', 'deliveryrules_id');
    }
}
