<?php

namespace Mirasvit\Kb\Api\Data;

interface ArticlesubsectionsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const ARTICLESUBSECTION_ID = 'articlesubsection_id';
    const PARENTARTICLESECTION_ID = 'parentarticlesection_id';
    const ASECSUB_NAME = 'asecsub_name';
    const ASECSUB_VALUE = 'asecsub_value';
    const ASECSUB_POSITION = 'asecsub_position';
    const ASECSUB_CREATED_AT = 'asecsub_created_at';
    const ASECSUB_UPDATED_AT = 'asecsub_updated_at';
    const ASECSUB_IS_ACTIVE = 'asecsub_is_active';

    /**
     * @return mixed
     */
    public function getArticlesubsectionId();

    /**
     * @param mixed $articlesubsection_id
     */
    public function setArticlesubsectionId($articlesubsection_id);

    /**
     * @return mixed
     */
    public function getParentarticlesectionId();

    /**
     * @param mixed $parentarticlesection_id
     */
    public function setParentarticlesectionId($parentarticlesection_id);

    /**
     * @return mixed
     */
    public function getAsecsubName();

    /**
     * @param mixed $asecsub_name
     */
    public function setAsecsubName($asecsub_name);

    /**
     * @return mixed
     */
    public function getAsecsubValue();

    /**
     * @param mixed $asecsub_value
     */
    public function setAsecsubValue($asecsub_value);

    /**
     * @return mixed
     */
    public function getAsecsubPosition();

    /**
     * @param mixed $asecsub_position
     */
    public function setAsecsubPosition($asecsub_position);

    /**
     * @return mixed
     */
    public function getAsecsubCreatedAt();

    /**
     * @param mixed $asecsub_created_at
     */
    public function setAsecsubCreatedAt($asecsub_created_at);
    /**
     * @return mixed
     */
    public function getAsecsubUpdatedAt();

    /**
     * @param mixed $asecsub_updated_at
     */
    public function setAsecsubUpdatedAt($asecsub_updated_at);

    /**
     * @return mixed
     */
    public function getAsecsubIsActive();

    /**
     * @param mixed $asecsub_is_active
     */
    public function setAsecsubIsActive($asecsub_is_active);
    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsectionsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Mirasvit\Kb\Api\Data\ArticlesubsectionsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticlesubsectionsExtensionInterface $extensionAttributes
    );
}
