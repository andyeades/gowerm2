<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Api\Data;

interface DeepercontentInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const DEEPERCONTENT_ID = 'deepercontent_id';
    const DEEPERCONTENT_IMAGE = 'deepercontent_image';
    const DEEPERCONTENT_TITLE = 'deepercontent_title';
    const DEEPERCONTENT = 'deepercontent';

    /**
     * Get deepercontent_id
     * @return string|null
     */
    public function getDeepercontentId();

    /**
     * Set deepercontent_id
     * @param string $deepercontentId
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontentId($deepercontentId);

    /**
     * Get deepercontent
     * @return string|null
     */
    public function getDeepercontent();

    /**
     * Set deepercontent
     * @param string $deepercontent
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontent($deepercontent);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Productdeepercontent\Api\Data\DeepercontentExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Productdeepercontent\Api\Data\DeepercontentExtensionInterface $extensionAttributes
    );

    /**
     * Get deepercontent_title
     * @return string|null
     */
    public function getDeepercontentTitle();

    /**
     * Set deepercontent_title
     * @param string $deepercontentTitle
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontentTitle($deepercontentTitle);

    /**
     * Get deepercontent_image
     * @return string|null
     */
    public function getDeepercontentImage();

    /**
     * Set deepercontent_image
     * @param string $deepercontentImage
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontentImage($deepercontentImage);
}

