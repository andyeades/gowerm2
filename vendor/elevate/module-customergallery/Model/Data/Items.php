<?php


namespace Elevate\CustomerGallery\Model\Data;

use Elevate\CustomerGallery\Api\Data\ItemsInterface;

class Items extends \Magento\Framework\Api\AbstractExtensibleObject implements ItemsInterface
{

    /**
     * Get items_id
     * @return string|null
     */
    public function getItemsId()
    {
        return $this->_get(self::ITEMS_ID);
    }

    /**
     * Set items_id
     * @param string $itemsId
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setItemsId($itemsId)
    {
        return $this->setData(self::ITEMS_ID, $itemsId);
    }

   
    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }
    
     /**
     * Get image
     * @return string|null
     */
    public function getImage()
    {
        return $this->_get(self::IMAGE);
    }

    /**
     * Set image
     * @param string $image
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }
    
    /**
     * Get social_image
     * @return string|null
     */
    public function getSocialImage()
    {
        return $this->_get(self::SOCIAL_IMAGE);
    }

    /**
     * Set social_image
     * @param string $socialImage
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setSocialImage($socialImage)
    {
        return $this->setData(self::SOCIAL_IMAGE, $socialImage);
    }
    
    /**
     * Get additional_image_2
     * @return string|null
     */
    public function getAdditionalImage2()
    {
        return $this->_get(self::ADDITIONAL_IMAGE_2);
    }

    /**
     * Set additional_image_2
     * @param string $additionalImage2
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage2($additionalImage2)
    {
        return $this->setData(self::ADDITIONAL_IMAGE_2, $additionalImage2);
    }
    
    /**
     * Get additional_image_3
     * @return string|null
     */
    public function getAdditionalImage3()
    {
        return $this->_get(self::ADDITIONAL_IMAGE_3);
    }

    /**
     * Set additional_image_3
     * @param string $additionalImage3
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage3($additionalImage3)
    {
        return $this->setData(self::ADDITIONAL_IMAGE_3, $additionalImage3);
    }
    
    /**
     * Get additional_image_4
     * @return string|null
     */
    public function getAdditionalImage4()
    {
        return $this->_get(self::ADDITIONAL_IMAGE_4);
    }

    /**
     * Set additional_image_4
     * @param string $additionalImage4
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage4($additionalImage4)
    {
        return $this->setData(self::ADDITIONAL_IMAGE_4, $additionalImage4);
    }
    
    /**
     * Get additional_image_5
     * @return string|null
     */
    public function getAdditionalImage5()
    {
        return $this->_get(self::ADDITIONAL_IMAGE_5);
    }

    /**
     * Set additional_image_5
     * @param string $additionalImage5
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage5($additionalImage5)
    {
        return $this->setData(self::ADDITIONAL_IMAGE_5, $additionalImage5);
    }
    
    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }
    
     /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
    
    /**
     * Get post_link
     * @return string|null
     */
    public function getPostLink()
    {
        return $this->_get(self::POST_LINK);
    }

    /**
     * Set post_link
     * @param string $postLink
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setPostLink($postLink)
    {
        return $this->setData(self::POST_LINK, $postLink);
    }
    
    /**
     * Get post_type
     * @return string|null
     */
    public function getPostType()
    {
        return $this->_get(self::POST_TYPE);
    }

    /**
     * Set post_type
     * @param string $postType
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setPostType($postType)
    {
        return $this->setData(self::POST_TYPE, $postType);
    }

    
    
    
    

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\CustomerGallery\Api\Data\ItemsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\CustomerGallery\Api\Data\ItemsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\CustomerGallery\Api\Data\ItemsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}

