<?php

namespace Firebear\ConfigurableProducts\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface;

class DefaultProductOptions extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('icp_catalog_product_default_super_link', DefaultProductOptionsInterface::LINK_ID);
    }
}
