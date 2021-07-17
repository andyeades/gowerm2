<?php

namespace Elevate\PrintLabels\Api\Data;

interface HolidaydatesInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const PRINTLABELSHOLIDAYDATES_ID = 'printlabelsholidaydates_id';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const PRINTLABELSHOLIDAYTITLE = 'printlabelsholidaytitle';
    const EXTENSION_ATTRIBUTES = '';

    /**
     * Get printlabelsholidaydates_id
     * @return string|null
     */
    public function getPrintlabelsholidaydatesId();

    /**
     * Set printlabelsholidaydates_id
     * @param string $printlabelsholidaydatesId
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesInterface
     */
    public function setPrintlabelsholidaydatesId(string $printlabelsholidaydatesId);

    /**
     * @return mixed
     */
    public function getStartDate();


    /**
     * @param mixed $start_date
     * @return void
     */
    public function setStartDate($start_date);

    /**
     * @return mixed
     */
    public function getEndDate();

    /**
     * @param mixed $end_date
     * @return void
     */
    public function setEndDate($end_date);

    /**
     * @return mixed
     */
    public function getPrintlabelsholidaytitle();

    /**
     * @param mixed $printlabelsholidaytitle
     * @return void
     */
    public function setPrintlabelsholidaytitle($printlabelsholidaytitle);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\PrintLabels\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\PrintLabels\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
    );

}
