<?php



namespace Firebear\ConfigurableProducts\Api\Data;

/**
 * Interface ProductOptionsInterface
 * @package Firebear\ConfigurableProducts\Api\Data
 * @api
 */
interface ProductOptionsInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const ENTITY_ID = 'item_id';
    const PRODUCT_ID = 'product_id';
    const X_AXIS = 'x_axis';
    const Y_AXIS = 'y_axis';
    const LINKED_ATTRIBUTE_IDS = 'linked_attributes';
    const DISPLAY_ATTRIBUTES_IN_MATRIX = 'display_matrix';

    /**
     * Returns entity_id field
     *
     * @return int|null
     */
    public function getItemId();

    /**
     * @param int $itemId
     *
     * @return $this
     */
    public function setItemId($itemId);
    
     /**
     * Returns product_id field
     *
     * @return int|null
     */
    public function getProductId();
    
     /**
     * @param int $productId
     *
     * @return $this
     */
    public function setProductId($productId);
    

    /**
     * Returns x_axis field
     *
     * @return string|null
     */
    public function getXAxis();
    
     /**
     * @param int $xAxis
     *
     * @return $this
     */
    public function setXAxis($xAxis);
    
    /**
     * Returns size_code field
     *
     * @return string|null
     */
    public function getYAxis();
    
     /**
      * @param int $yAxis
      *
      * @return $this
      */
    public function setYAxis($yAxis);

    /**
     * Returns linked_attributes field
     *
     * @return string|null
     */

    public function getLinkedAttributes();

    /**
     * @param string $linkedAttributes
     *
     * @return $this
     */
    public function setLinkedAttributes($linkedAttributes);

    /**
     * Returns display matrix flag
     *
     * @return int|null
     */

    public function getDisplayMatrix();

    /**
     * Set display matrix flag
     * @param int $displayMatrixFlag
     *
     * @return $this
     */
    
    public function setDisplayMatrix($displayMatrixFlag);
}
