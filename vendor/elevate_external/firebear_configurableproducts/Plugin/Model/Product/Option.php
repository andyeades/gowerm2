<?php

namespace Firebear\ConfigurableProducts\Plugin\Model\Product;

class Option
{
    protected $productRepository;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function beforeGetPrice(\Magento\Catalog\Model\Product\Option $subject)
    {
        if (!$subject->getProduct()) {
            $productId = $subject->getProductId();
            $subject->setProduct($this->productRepository->getById($productId));
        }
    }
}
