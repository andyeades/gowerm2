<?php

namespace Elevate\Discontinuedproducts\Model;

class Product extends \Elevate\LinkedProducts\Model\Product
{
    /**
     *
     * THIS HAS?
     *
     *
     * Retrieve array of custom type products
     *
     * @return array
     */
    public function getDiscontinuedproductsProducts()
    {
        $bob = 0;
        if (!$this->hasDiscontinuedproductsProducts()) {
            $products = [];
            foreach ($this->getDiscontinuedproductsProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setDiscontinuedproductsProducts($products);
        }
        return $this->getData('discontinuedproducts_products');
    }
    /**
     * Retrieve custom type products identifiers
     *
     * @return array
     */
    public function getDiscontinuedproductsIds()
    {
        if (!$this->hasDiscontinuedproductsProductIds()) {
            $ids = [];
            foreach ($this->getDiscontinuedproductsProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setDiscontinuedproductsProducts(ProductIds($ids));
        }
        return $this->getData('discontinuedproducts_product_ids');
    }
    /**
     * Retrieve collection custom type product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getDiscontinuedproductsProductCollection()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection $collection */
        $collection = $this->getLinkInstance()->useDiscontinuedproductsLinks()->getProductCollection()->setIsStrongMode();
        $collection
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToSelect('price')
             ->addAttributeToSelect('swatch_text')
            //elevate - needs edit for attribute pull
            ->addAttributeToSelect('swatch_colour_code')
            ->addAttributeToSelect('special_price');
        $collection->setProduct($this);
        return $collection;
    }
    /**
     * Retrieve collection custom type link
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Collection
     */
    public function getDiscontinuedproductsLinkCollection()
    {
        $collection = $this->getLinkInstance()->useDiscontinuedproductsLinks()->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }

}
