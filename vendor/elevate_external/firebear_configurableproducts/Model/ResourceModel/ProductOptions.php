<?php




namespace Firebear\ConfigurableProducts\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface;

class ProductOptions extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('icp_product_attributes', ProductOptionsInterface::ENTITY_ID);
    }
}
