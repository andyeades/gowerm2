<?php


namespace Elevate\CustomerGallery\Model\ResourceModel\Items;

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
            \Elevate\CustomerGallery\Model\Items::class,
            \Elevate\CustomerGallery\Model\ResourceModel\Items::class
        );
    }
}
