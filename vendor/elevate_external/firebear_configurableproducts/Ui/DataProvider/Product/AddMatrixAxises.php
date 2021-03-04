<?php

namespace Firebear\ConfigurableProducts\Ui\DataProvider\Product;

use Magento\Framework\Data\Collection;

/**
 * Class AddMatrixAxises
 * @package Firebear\ConfigurableProducts\Ui\DataProvider\Product
 */
class AddMatrixAxises implements \Magento\Ui\DataProvider\AddFieldToCollectionInterface
{
    /**
     * @param Collection $collection
     * @param $field
     * @param $alias
     */
    public function addField(Collection $collection, $field, $alias = null)
    {
        $collection
            ->joinField('x_axis', 'icp_product_attributes', 'x_axis', 'product_id=entity_id', null, 'left')
            ->joinField('y_axis', 'icp_product_attributes', 'y_axis', 'product_id=entity_id', null, 'left');
    }
}
