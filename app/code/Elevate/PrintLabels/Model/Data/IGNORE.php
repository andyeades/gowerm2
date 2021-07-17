<?php


namespace Elevate\PrintLabels\Model\Data;

use Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class PrintlabelsApi
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Model\Data
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class PrintlabelsApi extends \Magento\Framework\Api\AbstractExtensibleObject implements PrintlabelsApiInterface
{


    /**
     * @return mixed
     */
    public function getPrintlabelsApiId()
    {
        return $this->_getData(self::PRINTLABELS_API_ID);
    }

    /**
     * @param int $printlabelsApi_id
     */
    public function setPrintlabelsApiId($printlabelsApi_id)
    {
        return $this->setData(self::PRINTLABELS_API_ID, $printlabelsApi_id);
    }

    /**
     * @return mixed
     */
    public function getLastChecked()
    {
        return $this->_getData(self::LAST_CHECKED);
    }

    /**
     * @param mixed $last_checked
     */
    public function setLastChecked($last_checked)
    {
        return $this->setData(self::LAST_CHECKED, $last_checked);
    }

    /**
     * @return mixed
     */
    public function getGeosession()
    {
        return $this->_getData(self::GEOSESSION);
    }

    /**
     * @param mixed $geosession
     */
    public function setGeosession($geosession)
    {
        return $this->setData(self::GEOSESSION, $geosession);
    }

    public function getAllData()
    {
        $this->getData();
    }


    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\PrintLabels\Api\Data\PrintlabelsApiExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\PrintLabels\Api\Data\PrintlabelsApiExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
