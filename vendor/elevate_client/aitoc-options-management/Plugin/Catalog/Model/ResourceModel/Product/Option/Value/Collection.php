<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Model\ResourceModel\Product\Option\Value;

class Collection
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Collection constructor.
     *
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(\Magento\Framework\Registry $coreRegistry)
    {
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection $collection
     * @param bool $printQuery
     * @param bool $logQuery
     * @return array
     */
    public function beforeLoadWithFilter($collection, $printQuery = false, $logQuery = false)
    {
        if (!$this->coreRegistry->registry('option_import_mode')) {
            $this->addTemplateOptionTypeIdToResult($collection);
        }

        return [$printQuery, $logQuery];
    }

    /**
     * Add tempolate info to result
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection $collection
     */
    protected function addTemplateOptionTypeIdToResult($collection)
    {
        $collection->getSelect()->joinLeft(
            ['template_product_option_type_value' =>
                $collection->getTable('aitoc_optionsmanagement_template_product_option_type_value')],
            'template_product_option_type_value.product_option_type_id = main_table.option_type_id',
            ['template_option_type_id']
        );
    }
}
