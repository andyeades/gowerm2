<?php

namespace Firebear\ConfigurableProducts\Api\Data;

/**
 * Interface DefaultProductOptionsInterface
 * @package Firebear\ConfigurableProducts\Api\Data
 * @api
 */
interface DefaultProductOptionsInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const LINK_ID = 'link_id';
    const PRODUCT_ID = 'product_id';
    const PARENT_ID = 'parent_id';

    /**
     * Return entity id
     *
     * @return int|null
     */
    public function getLinkId();

    /**
     * Set entity id
     *
     * @param $linkId
     * @return $this
     */
    public function setLinkId($linkId);

    /**
     * Return child product id
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Set child product id
     *
     * @param $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * Return parent product id
     *
     * @return int|null
     */
    public function getParentId();

    /**
     * Set parent product id
     *
     * @param $productId
     * @return $this
     */
    public function setParentId($productId);
}
