<?php


namespace Elevate\Themeoptions\Model\ResourceModel;

class Options extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_themeoptions_options', 'entity_id');
    }
}
