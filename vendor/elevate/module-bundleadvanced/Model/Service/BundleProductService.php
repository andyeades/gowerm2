<?php

namespace Elevate\BundleAdvanced\Model\Service;

use Elevate\BundleAdvanced\Api\BundleProductManagementInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Copier as ProductCopier;
use Elevate\BundleAdvanced\Model\Product\Checker as ProductChecker;
use Magento\Framework\Exception\LocalizedException;
use Elevate\BundleAdvanced\Model\Product\Builder as ProductBuilder;

/**
 * Class BundleProductService
 * @package Elevate\BundleAdvanced\Model\Service
 */
class BundleProductService implements BundleProductManagementInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductCopier
     */
    private $productCopier;

    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @var ProductBuilder
     */
    private $productBuilder;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ProductCopier $productCopier
     * @param ProductChecker $productChecker
     * @param ProductBuilder $productBuilder
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductCopier $productCopier,
        ProductChecker $productChecker,
        ProductBuilder $productBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->productCopier = $productCopier;
        $this->productChecker = $productChecker;
        $this->productBuilder = $productBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function duplicateAsSimpleBundle($product)
    {
        /** @var Product $product */
        $product = $product instanceof ProductInterface
            ? $product
            : $this->productRepository->getById($product);
        if (!$this->productChecker->isNotSimpleBundleProduct($product)) {
            throw new LocalizedException(__('Requested product can\'t be converted to Simple Bundle Product.'));
        }
        $this->productBuilder->build($product);
        $duplicateProduct = $this->productCopier->copy($product);

        return $duplicateProduct;
    }
}
