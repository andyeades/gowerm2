<?php
declare(strict_types=1);
/**
 * Copyright © 2019 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Block\Swatches\Product\Renderer;

use Firebear\ConfigurableProducts\Model\ProductOptionsRepository;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Swatch renderer block
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Configurable
{
    /**
     * Custom Swatch renderer template.
     */
    const SWATCH_RENDERER_TEMPLATE = 'Firebear_ConfigurableProducts::product/view/renderer.phtml';
    const SWATCH_RENDERER_MATRIX_TEMPLATE = 'Firebear_ConfigurableProducts::product/view/renderer_matrix.phtml';
    const CONFIGURABLE_RENDERER_TEMPLATE = 'Firebear_ConfigurableProducts::product/view/configurable_renderer.phtml';
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ConfigurableAttributeData
     */
    protected $configurableAttributeData;
    /**
     * @var ProductOptionsRepository
     */
    protected $productOptionsRepository;

    /**
     * Configurable constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ProductOptionsRepository $optionsRepository
     * @param ConfigurableAttributeData $configurableAttributeData
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ProductOptionsRepository $optionsRepository,
        ConfigurableAttributeData $configurableAttributeData
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configurableAttributeData = $configurableAttributeData;
        $this->productOptionsRepository = $optionsRepository;
    }

    /**
     * @param \Magento\Swatches\Block\Product\Renderer\Configurable $subject
     * @param $template
     * @return string
     */
    public function beforeSetTemplate(
        \Magento\Swatches\Block\Product\Renderer\Configurable $subject,
        $template
    ) {
        $_currentAttributes = [];
        $displayMatrixSwatch = false;
        $displayMatrixForCurrentProduct = $yAxisCode = $xAxisCode = null;
        $matrixSwatchExtensionConfiguration = $this->scopeConfig->getValue(
            'firebear_configurableproducts/matrix/matrix_swatch',
            ScopeInterface::SCOPE_STORE
        );
        $matrixSwatchXAxis = $this->scopeConfig->getValue(
            'firebear_configurableproducts/matrix/x_axis',
            ScopeInterface::SCOPE_STORE
        );
        $matrixSwatchYAxis = $this->scopeConfig->getValue(
            'firebear_configurableproducts/matrix/y_axis',
            ScopeInterface::SCOPE_STORE
        );
        $currentProduct = $subject->getProduct();
        if ($currentProduct) {
            $defaultProductOptions =
                $this->productOptionsRepository->getByProductId($currentProduct->getId());
            $displayMatrixForCurrentProduct = $defaultProductOptions->getDisplayMatrix();
            $xAxisCode = $defaultProductOptions->getXAxis();
            $yAxisCode = $defaultProductOptions->getYAxis();
            $configProductAttributeData = $this->configurableAttributeData->getAttributesData($currentProduct);
            if (isset($configProductAttributeData['attributes'])) {
                foreach ($configProductAttributeData['attributes'] as $attribute) {
                    $_currentAttributes[] = $attribute['code'];
                }
            }
        }

        if ($matrixSwatchExtensionConfiguration
            && ($displayMatrixForCurrentProduct == null || $displayMatrixForCurrentProduct < 2)
        ) {
            $_currentProductHasAxis = in_array($xAxisCode, $_currentAttributes) || in_array(
                $yAxisCode,
                $_currentAttributes
            );
            $_currentProductHasAxisFromConfig = in_array(
                $matrixSwatchXAxis,
                $_currentAttributes
            ) || in_array($matrixSwatchYAxis, $_currentAttributes);

            if ($_currentProductHasAxis) {
                $displayMatrixSwatch = true;
            } elseif ($_currentProductHasAxisFromConfig) {
                $displayMatrixSwatch = true;
            }
        }

        if ($displayMatrixSwatch) {
            $template = self::SWATCH_RENDERER_MATRIX_TEMPLATE;
        } elseif ($template == 'Magento_ConfigurableProduct::product/view/type/options/configurable.phtml') {
            $template = self::CONFIGURABLE_RENDERER_TEMPLATE;
        } else {
            $template = self::SWATCH_RENDERER_TEMPLATE;
        }

        return $template;
    }
}
