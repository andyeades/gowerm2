<?php


namespace Elevate\Promotions\Model\ResourceModel\Promotions;

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
            \Elevate\Promotions\Model\Promotions::class,
            \Elevate\Promotions\Model\ResourceModel\Promotions::class
        );
    }
}
