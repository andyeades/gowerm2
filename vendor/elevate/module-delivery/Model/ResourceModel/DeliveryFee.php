<?php


namespace Elevate\Delivery\Model\ResourceModel;

class DeliveryFee extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_delivery_deliveryfee', 'deliveryfee_id');
    }
}
