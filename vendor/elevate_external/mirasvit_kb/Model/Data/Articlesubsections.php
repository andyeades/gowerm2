<?php

namespace Mirasvit\Kb\Model\Data;

use Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface;

class Articlesubsections extends \Magento\Framework\Api\AbstractExtensibleObject implements ArticlesubsectionsInterface
{


    /**
     * @return mixed
     */
    public function getArticlesubsectionId() {
        return $this->_get(self::ARTICLESUBSECTION_ID);

    }

    /**
     * @param mixed $articlesubsection_id
     */
    public function setArticlesubsectionId($articlesubsection_id) {
        return $this->setData(self::ARTICLESUBSECTION_ID, $articlesubsection_id);
    }

    /**
     * @return mixed
     */
    public function getParentarticlesectionId() {
        return $this->_get(self::PARENTARTICLESECTION_ID);

    }

    /**
     * @param mixed $parentarticlesection_id
     */
    public function setParentarticlesectionId($parentarticlesection_id) {
        return $this->setData(self::PARENTARTICLESECTION_ID, $parentarticlesection_id);
    }

    /**
     * @return mixed
     */
    public function getAsecsubName() {
        return $this->_get(self::ASECSUB_NAME);

    }

    /**
     * @param mixed $asecsubname
     */
    public function setAsecsubName($asecsubname) {
        return $this->setData(self::ASECSUB_NAME, $asecsubname);
    }

    /**
     * @return mixed
     */
    public function getAsecsubValue() {
        return $this->_get(self::ASECSUB_VALUE);

    }

    /**
     * @param mixed $asecsubvalue
     */
    public function setAsecsubValue($asecsubvalue) {
        return $this->setData(self::ASECSUB_VALUE, $asecsubvalue);
    }

    /**
     * @return mixed
     */
    public function getAsecsubPosition() {
        return $this->_get(self::ASECSUB_POSITION);

    }

    /**
     * @param mixed $asecsubposition
     */
    public function setAsecsubPosition($asecsubposition) {
        return $this->setData(self::ASECSUB_POSITION, $asecsubposition);
    }

    /**
     * @return mixed
     */
    public function getAsecsubCreatedAt() {
        return $this->_get(self::ASECSUB_CREATED_AT);

    }

    /**
     * @param mixed $asecsubcreated_at
     */
    public function setAsecsubCreatedAt($asecsubcreated_at) {
        return $this->setData(self::ASECSUB_CREATED_AT, $asecsubcreated_at);
    }

    /**
     * @return mixed
     */
    public function getAsecsubUpdatedAt() {
        return $this->_get(self::ASECSUB_UPDATED_AT);

    }

    /**
     * @param mixed $asecsubupdated_at
     */
    public function setAsecsubUpdatedAt($asecsubupdated_at) {
        return $this->setData(self::ASECSUB_UPDATED_AT, $asecsubupdated_at);
    }

    /**
     * @return mixed
     */
    public function getAsecsubIsActive() {
        return $this->_get(self::ASECSUB_IS_ACTIVE);

    }

    /**
     * @param mixed $asecsubis_active
     */
    public function setAsecsubIsActive($asecsubis_active) {
        return $this->setData(self::ASECSUB_IS_ACTIVE, $asecsubis_active);
    }




    public function getAllData() {
        return $this->_data;
    }
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsectionsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Mirasvit\Kb\Api\Data\ArticlesubsectionsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticlesubsectionsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

}
