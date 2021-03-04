<?php

namespace Firebear\ConfigurableProducts\Block\Product\Renderer;

use Magento\Swatches\Model\Swatch;

class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{

    protected function addSwatchDataForAttribute(
        array $options,
        array $swatchesCollectionArray,
        array $attributeDataArray
    ) {
        $result = [];
        foreach ($options as $optionId => $label) {
            if (isset($swatchesCollectionArray[$optionId])) {
                $result[$optionId] = $this->extractNecessarySwatchData($swatchesCollectionArray[$optionId]);
                $result[$optionId] = $this->addAdditionalMediaData($result[$optionId], $optionId, $attributeDataArray);
                $result[$optionId]['label'] = $label;
                    if ($attributeDataArray['swatch_input_type'] == 'visual'
                        && !$result[$optionId]['value']
                        && $result[$optionId]['type'] != Swatch::SWATCH_TYPE_VISUAL_IMAGE
                    ) {
                        $result[$optionId]['type'] = Swatch::SWATCH_TYPE_VISUAL_COLOR;
                        continue;
                    }
            }
        }
        return $result;
    }
}
