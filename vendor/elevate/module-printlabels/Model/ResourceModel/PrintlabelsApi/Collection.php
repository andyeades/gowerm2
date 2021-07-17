<?php


namespace Elevate\Printlabels\Model\ResourceModel\PrintlabelsApi;

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
            \Elevate\PrintLabels\Model\PrintlabelsApi::class,
            \Elevate\PrintLabels\Model\ResourceModel\PrintlabelsApi::class
        );
    }
}
