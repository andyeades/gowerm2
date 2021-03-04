<?php


namespace Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations;

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
            \Elevate\Delivery\Model\DeliveryRulesCombinations::class,
            \Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations::class
        );
    }
}
