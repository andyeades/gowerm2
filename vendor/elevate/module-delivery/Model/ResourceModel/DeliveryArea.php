<?php


namespace Elevate\Delivery\Model\ResourceModel;

class DeliveryArea extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_delivery_deliveryarea', 'deliveryarea_id');
    }
}
