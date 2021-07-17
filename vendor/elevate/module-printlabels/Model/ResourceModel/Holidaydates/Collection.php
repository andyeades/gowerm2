<?php


namespace Elevate\Printlabels\Model\ResourceModel\Holidaydates;

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
            \Elevate\PrintLabels\Model\Holidaydates::class,
            \Elevate\PrintLabels\Model\ResourceModel\Holidaydates::class
        );
    }
}
