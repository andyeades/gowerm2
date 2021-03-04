<?php

namespace Elevate\BundleAdvanced\Api\Data;

use Magento\Catalog\Api\Data\ProductAttributeInterface as CatalogProductAttributeInterface;

/**
 * Interface ProductAttributeInterface
 * @api
 */
interface ProductAttributeInterface extends CatalogProductAttributeInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const CODE_ELEVATE_BUNDLEADVANCED_BUNDLE_PRODUCT_TYPE = 'elevate_bundleadvanced_bundle_product_type';
    /**#@-*/
}
