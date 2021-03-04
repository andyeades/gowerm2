<?php
namespace Elevate\Themeoptions\Model\ResourceModel\Options;

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
            \Elevate\Themeoptions\Model\Options::class,
            \Elevate\Themeoptions\Model\ResourceModel\Options::class
        );
    }
}
