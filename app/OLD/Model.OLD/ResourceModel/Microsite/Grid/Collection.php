<?php


namespace Elevate\Microsite\Model\ResourceModel\Microsite\Grid;

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
            \Elevate\Microsite\Model\Microsite::class,
            \Elevate\Microsite\Model\ResourceModel\Microsite::class
        );
    }
}
