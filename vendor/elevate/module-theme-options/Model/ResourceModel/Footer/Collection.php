<?php
namespace Elevate\Themeoptions\Model\ResourceModel\Footer;

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
            \Elevate\Themeoptions\Model\Footer::class,
            \Elevate\Themeoptions\Model\ResourceModel\Footer::class
        );
    }
}
