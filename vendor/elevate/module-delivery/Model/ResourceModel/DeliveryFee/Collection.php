<?php


namespace Elevate\Delivery\Model\ResourceModel\DeliveryFee;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Elevate\Delivery\Model\DeliveryFee::class,
            \Elevate\Delivery\Model\ResourceModel\DeliveryFee::class
        );
    }
}
