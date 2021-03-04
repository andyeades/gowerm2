<?php
declare(strict_types=1);
/**
 * ConfigurableAxisAttributes
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Model\Config\Source;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ConfigurableAxisAttributes
 * @package Firebear\ConfigurableProducts\Model\Config\Source
 */
class ConfigurableAxisAttributes implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var ConfigurableAttributeHandler
     */
    protected $configurableAttributeHandler;

    /**
     * ConfigurableAxisAttributes constructor.
     * @param ConfigurableAttributeHandler $configurableAttributeHandler
     */
    public function __construct(
        ConfigurableAttributeHandler $configurableAttributeHandler
    ) {
        $this->configurableAttributeHandler = $configurableAttributeHandler;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options[] = ['value' => '', 'label' => __('Please Select')];
            /** @var ProductAttributeInterface $attribute */
            foreach ($this->configurableAttributeHandler->getApplicableAttributes() as $attribute) {
                if ($this->configurableAttributeHandler->isAttributeApplicable($attribute)) {
                    $this->options[] = [
                        'value' => $attribute->getAttributeCode(),
                        'label' => $attribute->getDefaultFrontendLabel() ?? $attribute->getAttributeCode()
                    ];
                }
            }
        }
        return $this->options;
    }
}
