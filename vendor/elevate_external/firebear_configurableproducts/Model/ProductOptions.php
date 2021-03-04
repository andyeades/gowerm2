<?php


namespace Firebear\ConfigurableProducts\Model;

use Firebear\ConfigurableProducts\Model\ResourceModel\ProductOptions as ProductOptionsResource;

class ProductOptions extends \Magento\Framework\Model\AbstractModel implements \Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface
{

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ProductOptionsResource::class);
    }
    /**
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setItemId($itemId)
    {
        return $this->setData(self::ENTITY_ID, $itemId);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }
    
     /**
      * {@inheritdoc}
      */
    public function getXAxis()
    {
        return $this->getData(self::X_AXIS);
    }
    
     /**
      * {@inheritdoc}
      */
    public function setXAxis($xAxis)
    {
         return $this->setData(self::X_AXIS, $xAxis);
    }
    
     /**
      * {@inheritdoc}
      */
    public function getYAxis()
    {
        return $this->getData(self::Y_AXIS);
    }
    
     /**
      * {@inheritdoc}
      */
    public function setYAxis($yAxis)
    {
        return $this->setData(self::Y_AXIS, $yAxis);
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkedAttributes()
    {
        return $this->getData(self::LINKED_ATTRIBUTE_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setLinkedAttributes($linkedAttributes)
    {
        return $this->setData(self::LINKED_ATTRIBUTE_IDS, $linkedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayMatrix()
    {
        return $this->getData(self::DISPLAY_ATTRIBUTES_IN_MATRIX);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayMatrix($displayMatrixFlag)
    {
        return $this->setData(self::DISPLAY_ATTRIBUTES_IN_MATRIX, $displayMatrixFlag);
    }
}
