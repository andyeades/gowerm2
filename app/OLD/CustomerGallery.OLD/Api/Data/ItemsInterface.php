<?php


namespace Elevate\CustomerGallery\Api\Data;

interface ItemsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const NAME = 'name';
    const IMAGE = 'image';
    const SOCIAL_IMAGE = 'social_image';
    const ADDITIONAL_IMAGE_2 = 'additional_image_2';
    const ADDITIONAL_IMAGE_3 = 'additional_image_3';
    const ADDITIONAL_IMAGE_4 = 'additional_image_4';
    const ADDITIONAL_IMAGE_5 = 'additional_image_5';
    const PRODUCT_ID = 'product_id';
    const STATUS = 'status';
    const POST_LINK = 'post_link';
    const POST_TYPE = 'post_type';
    const ITEMS_ID = 'items_id';

    /**
     * Get items_id
     * @return string|null
     */
    public function getItemsId();

    /**
     * Set items_id
     * @param string $itemsId
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setItemsId($itemsId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setName($name);
    
    
    /**
     * Get image
     * @return string|null
     */
    public function getImage();

    /**
     * Set image
     * @param string $image
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setImage($image);
    
    /**
     * Get additional_image_2
     * @return string|null
     */
    public function getAdditionalImage2();

    /**
     * Set additional_image_2
     * @param string $additionalImage2
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage2($additionalImage2);
    
    
    /**
     * Get additional_image_3
     * @return string|null
     */
    public function getAdditionalImage3();

    /**
     * Set additional_image_3
     * @param string $additionalImage3
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage3($additionalImage3);
    
    
    /**
     * Get additional_image_4
     * @return string|null
     */
    public function getAdditionalImage4();

    /**
     * Set additional_image_4
     * @param string $additionalImage4
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage4($additionalImage4);
    
    /**
     * Get additional_image_5
     * @return string|null
     */
    public function getAdditionalImage5();

    /**
     * Set additional_image_5
     * @param string $additionalImage5
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setAdditionalImage5($additionalImage5);
    
    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setProductId($productId);
    
    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setStatus($status);
    
    /**
     * Get post_link
     * @return string|null
     */
    public function getPostLink();

    /**
     * Set post_link
     * @param string $postLink
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setPostLink($postLink);
    
    /**
     * Get post_type
     * @return string|null
     */
    public function getPostType();

    /**
     * Set post_type
     * @param string $postType
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     */
    public function setPostType($postType);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\CustomerGallery\Api\Data\ItemsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\CustomerGallery\Api\Data\ItemsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\CustomerGallery\Api\Data\ItemsExtensionInterface $extensionAttributes
    );
}


