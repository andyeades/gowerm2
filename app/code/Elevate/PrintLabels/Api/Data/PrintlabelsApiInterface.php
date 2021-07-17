<?php

namespace Elevate\PrintLabels\Api\Data;

interface PrintlabelsApiInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRINTLABELS_API_ID = 'printlabels_api_id';
    const LAST_CHECKED = 'last_checked';
    const GEOSESSION = 'geosession';
    const EXTENSION_ATTRIBUTES = '';

    /**
     * Get printlabels_api_id
     * @return int
     */
    public function getPrintlabelsApiId();

    /**
     * Set printlabels_api_id
     * @param int $printlabelsApiId
     * @return \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface
     */
    public function setPrintlabelsApiId(int $printlabelsApiId);

    /**
     * @return mixed
     */
    public function getLastChecked();


    /**
     * @param mixed $last_checked
     * @return void
     */
    public function setLastChecked($last_checked);

    /**
     * @return mixed
     */
    public function getGeosession();

    /**
     * @param mixed $geosession
     * @return void
     */
    public function setGeosession($geosession);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\PrintLabels\Api\Data\PrintlabelsApiExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\PrintLabels\Api\Data\PrintlabelsApiExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\PrintLabels\Api\Data\PrintlabelsApiExtensionInterface $extensionAttributes
    );

}
