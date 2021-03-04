<?php

namespace Firebear\ConfigurableProducts\Model;

use Magento\Framework\Model\AbstractModel;
use Firebear\ConfigurableProducts\Model\ResourceModel\DefaultProductOptions as ResourceDefaultProductOptions;

class DefaultProductOptions extends AbstractModel implements
    \Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceDefaultProductOptions::class);
    }
    /**
     * {@inheritdoc}
     */
    public function getLinkId()
    {
        return $this->getData(self::LINK_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setLinkId($linkId)
    {
        return $this->setData(self::LINK_ID, $linkId);
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
    public function getParentId()
    {
        return $this->getData(self::PARENT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setParentId($parentId)
    {
        return $this->setData(self::PARENT_ID, $parentId);
    }
}