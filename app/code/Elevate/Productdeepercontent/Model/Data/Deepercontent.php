<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Model\Data;

use Elevate\Productdeepercontent\Api\Data\DeepercontentInterface;

class Deepercontent extends \Magento\Framework\Api\AbstractExtensibleObject implements DeepercontentInterface
{

    /**
     * Get deepercontent_id
     * @return string|null
     */
    public function getDeepercontentId()
    {
        return $this->_get(self::DEEPERCONTENT_ID);
    }

    /**
     * Set deepercontent_id
     * @param string $deepercontentId
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontentId($deepercontentId)
    {
        return $this->setData(self::DEEPERCONTENT_ID, $deepercontentId);
    }
    /**
     * Get deepercontent_title
     * @return string|null
     */
    public function getDeepercontentTitle()
    {
        return $this->_get(self::DEEPERCONTENT_TITLE);
    }

    /**
     * Set deepercontent_title
     * @param string $deepercontentTitle
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontentTitle($deepercontentTitle)
    {
        return $this->setData(self::DEEPERCONTENT_TITLE, $deepercontentTitle);
    }

    /**
     * Get deepercontent_image
     * @return string|null
     */
    public function getDeepercontentImage()
    {
        return $this->_get(self::DEEPERCONTENT_IMAGE);
    }

    /**
     * Set deepercontent_image
     * @param string $deepercontentImage
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontentImage($deepercontentImage)
    {
        return $this->setData(self::DEEPERCONTENT_IMAGE, $deepercontentImage);
    }

    /**
     * Get deepercontent
     * @return string|null
     */
    public function getDeepercontent()
    {
        return $this->_get(self::DEEPERCONTENT);
    }

    /**
     * Set deepercontent
     * @param string $deepercontent
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface
     */
    public function setDeepercontent($deepercontent)
    {
        return $this->setData(self::DEEPERCONTENT, $deepercontent);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Productdeepercontent\Api\Data\DeepercontentExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Productdeepercontent\Api\Data\DeepercontentExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Productdeepercontent\Api\Data\DeepercontentExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }


}

