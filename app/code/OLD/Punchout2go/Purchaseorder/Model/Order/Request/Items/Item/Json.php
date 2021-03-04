<?php

namespace Punchout2go\Purchaseorder\Model\Order\Request\Items\Item;

class Json
    extends \Punchout2go\Purchaseorder\Model\Order\Request\Items\Item\AbstractItem
{

    /** @var \Magento\Framework\DataObject */
    protected $_data;

    /**
     * get data from a specific source type;
     * leave this alone until we need to do custom-mapped line item data
     *
     * @param $param
     * @return mixed|string
     */
    /*public function srcData($param, $tree = null)
    {
        if (is_string($param)) {
            $nodes = $this->getXmlHelper()->selector_xpath($param,$this->getData());
            if (!empty($nodes)) {
                return (string) $nodes[0];
            }
        }
        return null;
    }*/

    /**
     * @return SimpleXMLElement
     */
    public function getData()
    {
        return parent::getData();
    }

    /**
     * leave this alone until we need to do custom-mapped line item data
     *
     * @return Vbw_Procurement_Helper_Src_Xml
     */
    /*public function getXmlHelper()
    {
        return Mage::helper('vbw_procurement/src_xml');
    }*/

}