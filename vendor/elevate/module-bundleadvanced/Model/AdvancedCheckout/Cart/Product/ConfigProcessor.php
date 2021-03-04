<?php

namespace Elevate\BundleAdvanced\Model\AdvancedCheckout\Cart\Product;

use Elevate\BundleAdvanced\Model\Product\Checker as ProductChecker;
use Elevate\BundleAdvanced\Model\Product\Type\SimpleBundle\OptionResolver as SimpleBundleProductOptionResolver;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\Product;

/**
 * Class ConfigProcessor
 *
 * @package Elevate\BundleAdvanced\Model\AdvancedCheckout\Cart\Product
 */
class ConfigProcessor
{
    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SimpleBundleProductOptionResolver
     */
    private $simpleBundleProductOptionResolver;

    /**
     * @param ProductChecker $productChecker
     * @param ProductRepositoryInterface $productRepository
     * @param SimpleBundleProductOptionResolver $simpleBundleProductOptionResolver
     */
    public function __construct(
        ProductChecker $productChecker,
        ProductRepositoryInterface $productRepository,
        SimpleBundleProductOptionResolver $simpleBundleProductOptionResolver
    ) {
        $this->productChecker = $productChecker;
        $this->productRepository = $productRepository;
        $this->simpleBundleProductOptionResolver = $simpleBundleProductOptionResolver;
    }

    /**
     * Process configuration data of the product
     *
     * @param string $sku
     * @param int $storeId
     * @param array $config
     * @return array
     */
    public function process($sku, $storeId, $config)
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->get($sku, false, $storeId);
            if ($this->productChecker->isSimpleBundleProduct($product)) {
                $bundleOptionsData = $this->simpleBundleProductOptionResolver->getDefaultOptionsData($product);
                $config['bundle_option'] = $bundleOptionsData;
            }
        } catch (NoSuchEntityException $exception) {
        }

        return $config;
    }
}
