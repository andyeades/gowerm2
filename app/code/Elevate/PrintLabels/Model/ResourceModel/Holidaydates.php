<?php


namespace Elevate\PrintLabels\Model\ResourceModel;

class Holidaydates extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_printlabels_holidaydates', 'printlabelsholidaydates_id');
    }
}
