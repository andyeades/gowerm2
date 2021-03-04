<?php
namespace Elevate\Themeoptions\Model\ResourceModel\Translations;

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
            \Elevate\Themeoptions\Model\Translations::class,
            \Elevate\Themeoptions\Model\ResourceModel\Translations::class
        );
    }
}
