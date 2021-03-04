<?php

namespace Elevate\Discontinuedproducts\Model\Product\Initialization\Helper;

use Elevate\Discontinuedproducts\Model\Catalog\Product\Link;
use Magento\Catalog\Api\Data\ProductLinkExtensionFactory;
use Magento\Catalog\Api\Data\ProductLinkInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;

class ProductLinks
{
    /**
     * String name for link type
     */
    const TYPE_NAME_A = 'discontinuedproducts';

    const TYPE_NAME_B = 'linkedproducts';
    /**
     * @var ProductLinkInterfaceFactory
     */
    protected $productLinkFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ProductLinkExtensionFactory
     */
    protected $productLinkExtensionFactory;

    /**
     * Init
     *
     * @param ProductLinkInterfaceFactory $productLinkFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ProductLinkExtensionFactory $productLinkExtensionFactory
     */
    public function __construct(
        ProductLinkInterfaceFactory $productLinkFactory,
        ProductRepositoryInterface $productRepository,
        ProductLinkExtensionFactory $productLinkExtensionFactory
    ) {
        $this->productLinkFactory = $productLinkFactory;
        $this->productRepository = $productRepository;
        $this->productLinkExtensionFactory = $productLinkExtensionFactory;
    }

    public function beforeInitializeLinks(
        \Magento\Catalog\Model\Product\Initialization\Helper\ProductLinks $subject,
        \Magento\Catalog\Model\Product $product,
        array $links
    )
    {
        if(isset($links[self::TYPE_NAME_A]) && !$product->getDiscontinuedproductsReadonly()) {

            $links = (isset($links[self::TYPE_NAME_A])) ? $links[self::TYPE_NAME_A] : $product->getDiscontinuedproductsLinkData();
            if (!is_array($links)) {
                $links = [];
            }

            if ($product->getDiscontinuedproductsLinkData()) {
                $links = array_merge($links, $product->getDiscontinuedproductsLinkData());
            }
            $newLinks = [];
            $existingLinks = $product->getProductLinks();
            foreach ($links as $linkRaw) {
                /** @var \Magento\Catalog\Api\Data\ProductLinkInterface $productLink */
                $productLink = $this->productLinkFactory->create();
                if (!isset($linkRaw['id'])) {
                    continue;
                }
                $productId = $linkRaw['id'];
                if (!isset($linkRaw['qty'])) {
                    $linkRaw['qty'] = 0;
                }
                $linkedProduct = $this->productRepository->getById($productId);

                $productLink->setSku($product->getSku())
                    ->setLinkType(self::TYPE_NAME_A)
                    ->setLinkedProductSku($linkedProduct->getSku())
                    ->setLinkedProductType($linkedProduct->getTypeId())
                    ->setPosition($linkRaw['position'])
                    ->getExtensionAttributes()
                    ->setQty($linkRaw['qty']);

                $newLinks[] = $productLink;
            }

            $existingLinks = $this->removeUnExistingLinks($existingLinks, $newLinks,self::TYPE_NAME_A);
            $product->setProductLinks(array_merge($existingLinks, $newLinks));
        }
        if(isset($links[self::TYPE_NAME_B]) && !$product->getLinkedproductsReadonly()) {

            $links = (isset($links[self::TYPE_NAME_B])) ? $links[self::TYPE_NAME_B] : $product->getLinkedproductsLinkData();
            if (!is_array($links)) {
                $links = [];
            }

            if ($product->getLinkedproductsLinkData()) {
                $links = array_merge($links, $product->getLinkedproductsLinkData());
            }
            $newLinks = [];
            $existingLinks = $product->getProductLinks();
            foreach ($links as $linkRaw) {
                /** @var \Magento\Catalog\Api\Data\ProductLinkInterface $productLink */
                $productLink = $this->productLinkFactory->create();
                if (!isset($linkRaw['id'])) {
                    continue;
                }
                $productId = $linkRaw['id'];
                if (!isset($linkRaw['qty'])) {
                    $linkRaw['qty'] = 0;
                }
                $linkedProduct = $this->productRepository->getById($productId);

                $productLink->setSku($product->getSku())
                            ->setLinkType(self::TYPE_NAME_B)
                            ->setLinkedProductSku($linkedProduct->getSku())
                            ->setLinkedProductType($linkedProduct->getTypeId())
                            ->setPosition($linkRaw['position'])
                            ->getExtensionAttributes()
                            ->setQty($linkRaw['qty']);

                $newLinks[] = $productLink;
            }

            $existingLinks = $this->removeUnExistingLinks($existingLinks, $newLinks, self::TYPE_NAME_B);
            $product->setProductLinks(array_merge($existingLinks, $newLinks));
        }
    }

    /**
     * Removes unexisting links
     *
     * @param array $existingLinks
     * @param array $newLinks
     * @param string $typename
     * @return array
     */
    private function removeUnExistingLinks($existingLinks, $newLinks, $typename)
    {
        $result = [];
        foreach ($existingLinks as $key => $link) {
            $result[$key] = $link;

            if ($link->getLinkType() == $typename) {
                $exists = false;
                foreach ($newLinks as $newLink) {
                    if ($link->getLinkedProductSku() == $newLink->getLinkedProductSku()) {
                        $exists = true;
                    }
                }
                if (!$exists) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

}
