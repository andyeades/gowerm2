<?php


namespace Elevate\Themeoptions\Model\ResourceModel;

class Translations extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_themeoptions_translations', 'entity_id');
    }
}
