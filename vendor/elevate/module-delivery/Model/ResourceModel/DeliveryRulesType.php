<?php


namespace Elevate\Delivery\Model\ResourceModel;

class DeliveryRulesType extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_delivery_deliveryrules_type', 'deliveryrules_type_id');
    }
}
