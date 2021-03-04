<?php


namespace Elevate\Support\Model\ResourceModel\Support;

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
            \Elevate\Support\Model\Support::class,
            \Elevate\Support\Model\ResourceModel\Support::class
        );
    }
}
