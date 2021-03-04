<?php
declare(strict_types=1);
/**
 * Copyright © 2016 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Helper\Catalog;

use Closure;
use Firebear\ConfigurableProducts\Service\ProductProvider;
use Magento\Catalog\Model\Product as ModelProduct;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Framework\App\Action\Action;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;

class Product
{
    /**
     * @var ProductProvider
     */
    protected $productProvider;

    /**
     * Configurable product resource model.
     *
     * @var Configurable
     */
    private $configurableResource;

    /**
     * Core registry.
     *
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param Configurable $configurableResource
     * @param Registry $coreRegistry
     * @param ProductProvider $productProvider
     */
    public function __construct(
        Configurable $configurableResource,
        Registry $coreRegistry,
        ProductProvider $productProvider
    ) {
        $this->configurableResource = $configurableResource;
        $this->coreRegistry = $coreRegistry;
        $this->productProvider = $productProvider;
    }

    /**
     * @param \Magento\Catalog\Helper\Product $subject
     * @param Closure $proceed
     * @param int $productId
     * @param Action $controller
     * @param DataObject $params
     * @return mixed
     */
    public function aroundInitProduct(
        \Magento\Catalog\Helper\Product $subject,
        Closure $proceed,
        $productId,
        $controller,
        $params = null
    ) {
        /** @var bool|ModelProduct $result */
        $result = $proceed($productId, $controller, $params);
        if ($productId) {
            $parentIds = $this->configurableResource->getParentIdsByChild($productId);
            if (!empty($parentIds)) {
                $parentProductId = $parentIds[0];
                $childProductId = $productId;
                try {
                    $parentProduct = $this->productProvider->getProductById($parentProductId);
                    $this->coreRegistry->register(
                        'firebear_configurableproducts',
                        [
                            'child_id' => $childProductId,
                            'parent_id' => $parentIds[0]
                        ]
                    );
                } catch (NoSuchEntityException $noSuchEntityException) {
                    $this->coreRegistry->unregister('firebear_configurableproducts');
                    return $result;
                }
                if ($parentProduct->isSaleable()) {
                    if ($childProductId) {
                        try {
                            $childProduct = $this->productProvider
                                ->getProductById($childProductId);
                            if (!$result) {
                                $result = $proceed($parentProductId, $controller, $params);
                            }
                            $result->setName($childProduct->getName());
                            $result->setSku($childProduct->getSku());
                            $result->setShortDescription($childProduct->getShortDescription());
                            $result->setDescription($childProduct->getDescription());
                            $result->setMetaTitle($childProduct->getMetaTitle());
                            $result->setMetaKeyword($childProduct->getMetaKeyword());
                            $result->setMetaDescription($childProduct->getMetaDescription());
                            $result->setFinalPrice($childProduct->getPrice());
                            $result->setPrice($childProduct->getPrice());
                            $result->setSpecialPrice($childProduct->getSpecialPrice());
                            /**
                             * Set product images.
                             * If simple product does not have images than parent will be used.
                             */
                            if ($childProduct->getData('image')) {
                                $result->setData('image', $childProduct->getData('image'));
                            }
                            if ($childProduct->getData('small_image')) {
                                $result->setData('small_image', $childProduct->getData('small_image'));
                            }
                            if ($childProduct->getData('thumbnail')) {
                                $result->setData('thumbnail', $childProduct->getData('thumbnail'));
                            }
                            if ($childProduct->getMediaGalleryImages()) {
                                $result->setMediaGalleryImages($childProduct->getMediaGalleryImages());
                            }
                            /**
                             * Add updated product to registry.
                             */
                            $this->coreRegistry->unregister('product');
                            $this->coreRegistry->register('product', $result);
                        } catch (NoSuchEntityException $noSuchEntityException) {
                            $this->coreRegistry->unregister('firebear_configurableproducts');
                            return $result;
                        }
                    }
                }
            }
        }
        return $result;
    }
}
