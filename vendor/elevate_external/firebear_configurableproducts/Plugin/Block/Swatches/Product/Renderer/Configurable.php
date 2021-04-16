<?php
/**
 * Copyright © 2019 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Block\Swatches\Product\Renderer;

use Magento\Store\Model\ScopeInterface;
use Firebear\ConfigurableProducts\Model\ProductOptionsRepository;
use Firebear\ConfigurableProducts\Helper\Data;


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

    private $scopeConfig;
    private $productOptionsRepository;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ProductOptionsRepository $optionsRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->productOptionsRepository = $optionsRepository;
    }

    /**
     * @param \Magento\Swatches\Block\Product\Renderer\Configurable $subject
     * @param                                                       $template
     *
     * @return string
     */
    public function afterGetTemplate(
        \Magento\Swatches\Block\Product\Renderer\Configurable $subject,
        $template
    ) {
        $matrixSwatchExtensionConfiguration =
            $this->scopeConfig->getValue(
                'firebear_configurableproducts/matrix/matrix_swatch',
                ScopeInterface::SCOPE_STORE
            );
        $currentProduct = $subject->getProduct();
       // if ($currentProduct) {
        //    $displayMatrixForCurrentProduct =
         //       $this->productOptionsRepository->getByProductId($currentProduct->getId())->getDisplayMatrix();
       // } else {
            $displayMatrixForCurrentProduct = null;
        //}
        if (!$displayMatrixForCurrentProduct) {
            $displayMatrixForCurrentProduct = $matrixSwatchExtensionConfiguration;
        }
        if ($matrixSwatchExtensionConfiguration == 1 && $displayMatrixForCurrentProduct == 1) {
            return self::SWATCH_RENDERER_MATRIX_TEMPLATE;
        } elseif ($template == 'Magento_ConfigurableProduct::product/view/type/options/configurable.phtml') {
            return self::CONFIGURABLE_RENDERER_TEMPLATE;
        } else {
            return self::SWATCH_RENDERER_TEMPLATE;
        }
    }
}
