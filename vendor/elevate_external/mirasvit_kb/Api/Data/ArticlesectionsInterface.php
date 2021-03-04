<?php

namespace Mirasvit\Kb\Api\Data;

interface ArticlesectionsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const ARTICLESECTION_ID = 'articlesection_id';
    const PARENTARTICLE_ID = 'parentarticle_id';
    const ASEC_NAME = 'asec_name';
    const ASEC_VALUE = 'asec_value';
    const ASEC_POSITION = 'asec_position';
    const ASEC_CREATED_AT = 'asec_created_at';
    const ASEC_UPDATED_AT = 'asec_updated_at';
    const ASEC_IS_ACTIVE = 'asec_is_active';

    /**
     * @return mixed
     */
    public function getArticlesectionId();

    /**
     * @param mixed $articlesection_id
     */
    public function setArticlesectionId($articlesection_id);

    /**
     * @return mixed
     */
    public function getParentarticleId();

    /**
     * @param mixed $parentarticle_id
     */
    public function setParentarticleId($parentarticle_id);

    /**
     * @return mixed
     */
    public function getAsecName();

    /**
     * @param mixed $asec_name
     */
    public function setAsecName($asec_name);

    /**
     * @return mixed
     */
    public function getAsecValue();

    /**
     * @param mixed $asec_value
     */
    public function setAsecValue($asec_value);

    /**
     * @return mixed
     */
    public function getAsecPosition();

    /**
     * @param mixed $asec_position
     */
    public function setAsecPosition($asec_position);

    /**
     * @return mixed
     */
    public function getAsecCreatedAt();

    /**
     * @param mixed $asec_created_at
     */
    public function setAsecCreatedAt($asec_created_at);
    /**
     * @return mixed
     */
    public function getAsecUpdatedAt();

    /**
     * @param mixed $asec_updated_at
     */
    public function setAsecUpdatedAt($asec_updated_at);

    /**
     * @return mixed
     */
    public function getAsecIsActive();

    /**
     * @param mixed $asec_is_active
     */
    public function setAsecIsActive($asec_is_active);
    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Mirasvit\Kb\Api\Data\ArticlesectionsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticlesectionsExtensionInterface $extensionAttributes
    );
}
