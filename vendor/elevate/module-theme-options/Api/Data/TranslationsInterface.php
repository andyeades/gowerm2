<?php
namespace Elevate\Themeoptions\Api\Data;

interface TranslationsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const ENTITY_ID = 'entity_id';
    const TRANSLATIONS_NAME = 'translations_name';
    const TRANSLATIONS_CONTENT = 'translations_content';
    const TRANSLATIONS_AREA_CODE = 'translations_area_code';

    /**
     * Get entity_id
     *
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     *
     * @param string $entityId
     *
     * @return \Elevate\Themeoptions\Api\Data\TranslationsInterface
     */
    public function setEntityId($entityId);

    /**
     * @param mixed $translations_name
     */
    public function setTranslationsName($translations_name);

    /**
     * @return mixed
     */
    public function getTranslationsContent();

    /**
     * @param mixed $translations_content
     */
    public function setTranslationsContent($translations_content);

    /**
     * @return mixed
     */
    public function getTranslationsAreaCode();

    /**
     * @param mixed $translations_area_code
     */
    public function setTranslationsAreaCode($translations_area_code);


    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Themeoptions\Api\Data\TranslationsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Themeoptions\Api\Data\TranslationsExtensionInterface    $extensionAttributes
     *
     * @return   $this
     */
    public function setExtensionAttributes(
        \Elevate\Themeoptions\Api\Data\TranslationsExtensionInterface $extensionAttributes
    );
}
