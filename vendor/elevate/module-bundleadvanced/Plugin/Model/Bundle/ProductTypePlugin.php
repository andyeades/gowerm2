<?php

namespace Elevate\BundleAdvanced\Plugin\Model\Bundle;

use Elevate\BundleAdvanced\Model\Product\Type\BuyRequestManager;
use Magento\Bundle\Model\ResourceModel\Selection\Collection as BundleSelectionCollection;
use Magento\Bundle\Model\Product\Type as BundleProduct;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Elevate\BundleAdvanced\Model\Product\Checker as ProductChecker;

/**
 * Class ProductTypePlugin
 * @package Elevate\BundleAdvanced\Plugin\Model\Bundle
 */
class ProductTypePlugin
{
    /**
     * @var BuyRequestManager
     */
    private $buyRequestManager;

    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @param BuyRequestManager $buyRequestManager
     * @param ProductChecker $productChecker
     */
    public function __construct(
        ProductChecker $productChecker,
        BuyRequestManager $buyRequestManager
    ) {
        $this->buyRequestManager = $buyRequestManager;
        $this->productChecker = $productChecker;
    }

    /**
     * Add visibility attribute to bundle selections
     *
     * @param BundleProduct $subject
     * @param BundleSelectionCollection $result
     * @return BundleSelectionCollection
     */
    public function afterGetSelectionsCollection($subject, $result)
    {
        $result->addAttributeToSelect(ProductInterface::VISIBILITY);

        return $result;
    }

    /**
     * Add default option if needed
     *
     * @param BundleProduct $subject
     * @param DataObject $buyRequest
     * @param Product $product
     * @param string $processMode
     * @return void
     */
    public function beforeProcessConfiguration(
        $subject,
        DataObject $buyRequest,
        $product,
        $processMode = AbstractType::PROCESS_MODE_LITE
    ) {
        $this->buyRequestManager->addDefaultOptions($product, $buyRequest);
    }

    /**
     * Add default option if needed
     *
     * @param BundleProduct $subject
     * @param DataObject $buyRequest
     * @param Product $product
     * @param string|null $processMode
     * @return void
     */
    public function beforePrepareForCartAdvanced($subject, DataObject $buyRequest, $product, $processMode = null)
    {
        $this->buyRequestManager->addDefaultOptions($product, $buyRequest);
    }

    /**
     * Check if product can be configured
     *
     * @param BundleProduct $subject
     * @param Product $product
     * @param bool $result
     * @return bool
     */
    public function afterCanConfigure($subject, $result, $product)
    {
        if ($this->productChecker->isSimpleBundleProduct($product)) {
            $result = false;
        }

        return $result;
    }
}
