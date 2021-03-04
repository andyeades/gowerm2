<?php


namespace Elevate\Assignments\Model\ResourceModel\Items;

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
            \Elevate\Assignments\Model\Items::class,
            \Elevate\Assignments\Model\ResourceModel\Items::class
        );
    }
}
