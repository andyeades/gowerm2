<?php

namespace Elevate\Discontinuedproducts\Model\Catalog\Product;

class Link extends \Elevate\LinkedProducts\Model\Catalog\Product\Link
{
    const LINK_TYPE_DISCONTINUEDPRODUCTS = 7;

    /**
     * @return \Magento\Catalog\Model\Product\Link $this
     */
    public function useDiscontinuedproductsLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_DISCONTINUEDPRODUCTS);
        return $this;
    }

    /**
     * Save data for product relations
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product\Link
     */
    public function saveDiscontinuedproductss($product)
    {
        parent::saveDiscontinuedproductss($product);

        $data = $product->getDiscontinuedproductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveProductLinks($product->getId(), $data, self::LINK_TYPE_DISCONTINUEDPRODUCTS);
        }
    }
}
