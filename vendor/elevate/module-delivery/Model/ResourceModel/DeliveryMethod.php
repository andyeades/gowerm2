<?php


namespace Elevate\Delivery\Model\ResourceModel;

class DeliveryMethod extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_delivery_deliverymethod', 'deliverymethod_id');
    }
}
