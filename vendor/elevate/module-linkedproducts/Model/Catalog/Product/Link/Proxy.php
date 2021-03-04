<?php

namespace Elevate\LinkedProducts\Model\Catalog\Product\Link;

class Proxy extends \Magento\Catalog\Model\Product\Link\Proxy
{
    /**
     * {@inheritdoc}
     */
    public function useLinkedproductsLinks()
    {
        return $this->_getSubject()->useLinkedproductsLinks();
    }
}