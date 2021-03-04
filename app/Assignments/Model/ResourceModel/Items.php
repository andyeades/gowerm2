<?php


namespace Elevate\Assignments\Model\ResourceModel;

class Items extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_assignments_addons', 'addon_id');
    }
}
