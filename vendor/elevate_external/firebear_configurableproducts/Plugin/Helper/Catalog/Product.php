<?php
/**
 * Copyright © 2016 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Helper\Catalog;

use Magento\Catalog\Model\Session;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class Product
{
    /**
     * Configurable product resource model.
     *
     * @var Configurable
     */
    private $configurableResource;

    /**
     * Catalog session.
     *
     * @var Session
     */
    private $catalogSession;

    /**
     * Core registry.
     *
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Configurable $configurableResource
     * @param Session $catalogSession
     * @param Registry $coreRegistry
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Configurable $configurableResource,
        Session $catalogSession,
        Registry $coreRegistry,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->configurableResource = $configurableResource;
        $this->catalogSession = $catalogSession;
        $this->coreRegistry = $coreRegistry;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param \Magento\Catalog\Helper\Product $subject
     * @param $productId
     * @param $controller
     * @param $params
     * @return array
     * @throws NoSuchEntityException
     */
    public function beforeInitProduct(\Magento\Catalog\Helper\Product $subject, $productId, $controller, $params = null)
    {
        if ($productId) {
            $parentIds = $this->configurableResource->getParentIdsByChild($productId);
            if (!empty($parentIds)) {
                $childProductId = $productId;
                try {
             print_r($parentIds);
                    $parentProduct = $this->productRepository->getById($parentIds[0], false, $this->storeManager->getStore()->getId());
                    $this->coreRegistry->register(
                        'firebear_configurableproducts',
                        [
                            'child_id'  => $childProductId,
                            'parent_id' => $parentIds[0]
                        ]
                    );
                } catch (NoSuchEntityException $e) {
                    $this->coreRegistry->unregister('firebear_configurableproducts');
                    return [$productId, $controller, $params];
                }
                if ($parentProduct->isSaleable()) {
                    $productId = $parentIds[0];
                    return [$productId, $controller, $params, $childProductId];
                }
            }
        }
        return [$productId, $controller, $params];
    }

    /**
     * Set configurable meta data based on current simple product.
     *
     * @return bool|\Magento\Catalog\Model\Product
     * @throws NoSuchEntityException
     */
    public function afterInitProduct(
        \Magento\Catalog\Helper\Product $subject,
        $result,
        $productId,
        $controller,
        $params = null,
        $childProductId = null
    ) {
        if ($childProductId) {
            $storeId = $this->storeManager->getStore()->getId();
            $childProduct = $this->productRepository->getById($childProductId, false, $storeId);
            $result->setName($childProduct->getName());
            $result->setSku($childProduct->getSku());
            $result->setShortDescription($childProduct->getShortDescription());
            $result->setDescription($childProduct->getDescription());
            $result->setMetaTitle($childProduct->getMetaTitle());
            $result->setMetaKeyword($childProduct->getMetaKeyword());
            $result->setMetaDescription($childProduct->getMetaDescription());
            $result->setFinalPrice($childProduct->getPrice());
            $result->setPrice($childProduct->getPrice());
            $result->setWasPrice($childProduct->getWasPrice());
            $result->setSpecialPrice($childProduct->getSpecialPrice());
            $result->setIsChild(1);
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
        }
        return $result;
    }
}
