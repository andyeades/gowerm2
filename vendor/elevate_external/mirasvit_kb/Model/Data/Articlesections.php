<?php


namespace Mirasvit\Kb\Model\Data;

use Mirasvit\Kb\Api\Data\ArticlesectionsInterface;

/**
 * Class Articlesections
 *
 * @category Elevate
 * @package  Mirasvit\Kb\Model\Data
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Articlesections extends \Magento\Framework\Api\AbstractExtensibleObject implements ArticlesectionsInterface
{


    /**
     * @return mixed
     */
    public function getArticlesectionId() {
        return $this->_get(self::ARTICLESECTION_ID);

    }

    /**
     * @param mixed $articlesection_id
     */
    public function setArticlesectionId($articlesection_id) {
        return $this->setData(self::ARTICLESECTION_ID, $articlesection_id);
    }

    /**
     * @return mixed
     */
    public function getParentarticleId() {
        return $this->_get(self::PARENTARTICLE_ID);

    }

    /**
     * @param mixed $parentarticle_id
     */
    public function setParentarticleId($parentarticle_id) {
        return $this->setData(self::PARENTARTICLE_ID, $parentarticle_id);
    }

    /**
     * @return mixed
     */
    public function getAsecName() {
        return $this->_get(self::ASEC_NAME);

    }

    /**
     * @param mixed $asec_name
     */
    public function setAsecName($asec_name) {
        return $this->setData(self::ASEC_NAME, $asec_name);
    }

    /**
     * @return mixed
     */
    public function getAsecValue() {
        return $this->_get(self::ASEC_VALUE);

    }

    /**
     * @param mixed $asec_value
     */
    public function setAsecValue($asec_value) {
        return $this->setData(self::ASEC_VALUE, $asec_value);
    }

    /**
     * @return mixed
     */
    public function getAsecPosition() {
        return $this->_get(self::ASEC_POSITION);

    }

    /**
     * @param mixed $asec_position
     */
    public function setAsecPosition($asec_position) {
        return $this->setData(self::ASEC_POSITION, $asec_position);
    }

    /**
     * @return mixed
     */
    public function getAsecCreatedAt() {
        return $this->_get(self::ASEC_CREATED_AT);

    }

    /**
     * @param mixed $asec_created_at
     */
    public function setAsecCreatedAt($asec_created_at) {
        return $this->setData(self::ASEC_CREATED_AT, $asec_created_at);
    }

    /**
     * @return mixed
     */
    public function getAsecUpdatedAt() {
        return $this->_get(self::ASEC_UPDATED_AT);

    }

    /**
     * @param mixed $asec_updated_at
     */
    public function setAsecUpdatedAt($asec_updated_at) {
        return $this->setData(self::ASEC_UPDATED_AT, $asec_updated_at);
    }

    /**
     * @return mixed
     */
    public function getAsecIsActive() {
        return $this->_get(self::ASEC_IS_ACTIVE);

    }

    /**
     * @param mixed $asec_is_active
     */
    public function setAsecIsActive($asec_is_active) {
        return $this->setData(self::ASEC_IS_ACTIVE, $asec_is_active);
    }

    /**
     * @return array|mixed
     */
    public function getAllData() {
        return $this->_data;
    }
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Mirasvit\Kb\Api\Data\ArticlesectionsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticlesectionsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

}
