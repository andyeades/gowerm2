<?php


namespace Elevate\Delivery\Model\ResourceModel\DeliveryArea;

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
            \Elevate\Delivery\Model\DeliveryArea::class,
            \Elevate\Delivery\Model\ResourceModel\DeliveryArea::class
        );
    }
}
