<?php


namespace Elevate\PrintLabels\Model\ResourceModel;

class PrintlabelsApi extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_printlabels_api', 'printlabels_api_id');
    }
}
