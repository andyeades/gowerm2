<?php

namespace Elevate\Themeoptions\Model\Data;

use Elevate\Themeoptions\Api\Data\TranslationsInterface;

class Translations extends \Magento\Framework\Api\AbstractExtensibleObject implements TranslationsInterface {


    /**
     * Get entity_id
     *
     * @return string|null
     */
    public function getEntityId() {
        return $this->_get(self::ENTITY_ID);
    }

    /**
     * Set entity_id
     *
     * @param string $entityId
     *
     * @return \Elevate\Themeoptions\Api\Data\TranslationsInterface
     */
    public function setEntityId($entityId) {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @return mixed
     */
    public function getTranslationsName() {
        return $this->_get(self::TRANSLATIONS_NAME);

    }

    /**
     * @param mixed $translations_name
     */
    public function setTranslationsName($translations_name) {
        return $this->setData(self::TRANSLATIONS_NAME, $translations_name);
    }


    /**
     * @return mixed
     */
    public function getTranslationsContent() {
        return $this->_get(self::TRANSLATIONS_CONTENT);

    }

    /**
     * @param mixed $translations_content
     */
    public function setTranslationsContent($translations_content) {
        return $this->setData(self::TRANSLATIONS_CONTENT, $translations_content);
    }



    /**
     * @return mixed
     */
    public function getTranslationsAreaCode() {
        return $this->_get(self::TRANSLATIONS_AREA_CODE);

    }

    /**
     * @param mixed $translations_area_code
     */
    public function setTranslationsAreaCode($translations_area_code) {
        return $this->setData(self::TRANSLATIONS_AREA_CODE, $translations_area_code);
    }


    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Themeoptions\Api\Data\TranslationsExtensionInterface|null
     */
    public function getExtensionAttributes() {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Themeoptions\Api\Data\TranslationsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Themeoptions\Api\Data\TranslationsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
