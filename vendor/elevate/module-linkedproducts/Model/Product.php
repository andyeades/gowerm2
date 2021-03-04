<?php

namespace Elevate\LinkedProducts\Model;

class Product extends \Magento\Catalog\Model\Product
{
    /**
     * Retrieve array of custom type products
     *
     * @return array
     */
    public function getLinkedProductsProducts() 
    {
        if (!$this->hasLinkedProductsProducts()) {
            $products = [];
            foreach ($this->getLinkedProductsProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setLinkedproductsProducts($products);
        }
        return $this->getData('linkedproducts_products');
    }
    /**
     * Retrieve custom type products identifiers
     *
     * @return array
     */
    public function getLinkedProductsIds() 
    {
        if (!$this->hasLinkedroductsProductIds()) {
            $ids = [];
            foreach ($this->getLinkedroductsProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setLinkedroductsProductIds($ids);
        }
        return $this->getData('linkedproducts_product_ids');
    }
    /**
     * Retrieve collection custom type product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getLinkedProductsProductCollection() 
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection $collection */
        $collection = $this->getLinkInstance()->useLinkedProductsLinks()->getProductCollection()->setIsStrongMode();
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
    public function getLinkedProductsLinkCollection() 
    {
        $collection = $this->getLinkInstance()->useLinkedProductsLinks()->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }
    
}