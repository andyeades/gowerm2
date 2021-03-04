<?php

namespace Elevate\BundleAdvanced\Block\Adminhtml\Catalog\Product\Composite\Fieldset\Options\Type;

use Magento\Bundle\Block\Adminhtml\Catalog\Product\Composite\Fieldset\Options\Type\Checkbox as BundleCheckbox;

/**
 * Class Checkbox
 * @package Elevate\BundleAdvanced\Block\Adminhtml\Catalog\Product\Composite\Fieldset\Options\Type
 */
class Checkbox extends BundleCheckbox
{
    /**
     * @var string
     */
    protected $_template = 'Elevate_BundleAdvanced::product/composite/fieldset/options/type/checkbox.phtml';
}
