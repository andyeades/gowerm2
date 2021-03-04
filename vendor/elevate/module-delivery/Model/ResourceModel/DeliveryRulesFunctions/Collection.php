<?php


namespace Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions;

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
            \Elevate\Delivery\Model\DeliveryRulesFunctions::class,
            \Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions::class
        );
    }
}
