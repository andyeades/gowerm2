<?php

namespace Elevate\Discontinuedproducts\Model\Catalog\Product\Link;

class Proxy extends \Elevate\LinkedProducts\Model\Catalog\Product\Link\Proxy
{
    /**
     * {@inheritdoc}
     */
    public function useDiscontinuedproductsLinks()
    {
        return $this->_getSubject()->useDiscontinuedproductsLinks();
    }
}
