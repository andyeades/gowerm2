<?php

namespace Firebear\ConfigurableProducts\Model\ResourceModel\DefaultProductOptions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'link_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Firebear\ConfigurableProducts\Model\DefaultProductOptions',
            'Firebear\ConfigurableProducts\Model\DefaultResourceModel\ProductOptions'
        );
    }
}
