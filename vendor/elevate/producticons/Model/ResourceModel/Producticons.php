<?php
namespace Elevate\ProductIcons\Model\ResourceModel;

class Producticons extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_producticons', 'icon_id');
    }
}
?>