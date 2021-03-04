<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Model\Product\Option;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Option\Repository as OptionRepository;
use Magento\Framework\Registry;

class Repository
{
    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @var ProductInterface
     */
    protected $product = null;

    /**
     * Repository constructor.
     *
     * @param \Aitoc\OptionsManagement\Helper\Data $helper
     * @param Registry $coreRegistry
     */
    public function __construct(
        \Aitoc\OptionsManagement\Helper\Data $helper,
        Registry $coreRegistry
    ) {
        $this->helper = $helper;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param OptionRepository $optionRepository
     * @param ProductInterface $product
     * @param bool $requiredOnly
     * @return array
     */
    public function beforeGetProductOptions($optionRepository, ProductInterface $product, $requiredOnly = false)
    {
        $this->product = $product;
        return [$product, $requiredOnly];
    }

    /**
     * @param OptionRepository $optionRepository
     * @param array $result
     * @return array
     */
    public function afterGetProductOptions($optionRepository, $result)
    {
        if ($result && $this->product && !$this->product->getData('_edit_mode')
            && $this->helper->isEnabledPerOptionEnabled()
            && !$this->coreRegistry->registry('option_edit_mode')
        ) {
            $options = $result;
            $result = [];
            foreach($options as $option) {
                if ($option->getIsEnable()) {
                    $result[] = $option;
                }
            }
        }
        return $result;
    }

    /**
     * @param OptionRepository $optionRepository
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
     * @return array
     */
    public function beforeSave($optionRepository, \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option)
    {
        if (!$this->coreRegistry->registry('option_edit_mode')) {
            $this->coreRegistry->register('option_edit_mode', true);
        }

        return [$option];
    }
}
