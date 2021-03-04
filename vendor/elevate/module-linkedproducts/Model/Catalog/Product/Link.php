<?php

namespace Elevate\LinkedProducts\Model\Catalog\Product;

class Link extends \Magento\Catalog\Model\Product\Link
{
    const LINK_TYPE_LINKEDPRODUCTS = 6;

    /**
     * @return \Magento\Catalog\Model\Product\Link $this
     */
    public function useLinkedproductsLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_LINKEDPRODUCTS);
        return $this;
    }

    /**
     * Save data for product relations
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product\Link
     */
    public function saveLinkedProductss($product)
    {
        parent::saveLinkedProductss($product);

        $data = $product->getLinkedproductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveProductLinks($product->getId(), $data, self::LINK_TYPE_LINKEDPRODUCTS);
        }
    }
}
