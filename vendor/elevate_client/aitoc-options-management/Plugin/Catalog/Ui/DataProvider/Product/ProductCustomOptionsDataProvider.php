<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Ui\DataProvider\Product;

use Magento\Framework\Registry;

class ProductCustomOptionsDataProvider
{
    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * ProductCustomOptionsDataProvider constructor.
     * @param Registry $coreRegistry
     */
    public function __construct(Registry $coreRegistry)
    {
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param \Magento\Catalog\Ui\DataProvider\Product\ProductCustomOptionsDataProvider $productCustomOptionsDataProvider
     * @return array
     */
    public function beforeGetData(
        \Magento\Catalog\Ui\DataProvider\Product\ProductCustomOptionsDataProvider $productCustomOptionsDataProvider
    ) {
        if (!$this->coreRegistry->registry('option_edit_mode')) {
            $this->coreRegistry->register('option_edit_mode', true);
        }

        if (!$this->coreRegistry->registry('option_import_mode')) {
            $this->coreRegistry->register('option_import_mode', true);
        }
        return [];
    }
}
