<?php


namespace Elevate\Delivery\Model\ResourceModel;

class DeliveryRulesProducts extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_delivery_deliveryrules_products', 'deliveryrulesproducts_id');
    }
}
