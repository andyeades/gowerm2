<?php

namespace Punchout2go\Purchaseorder\Model\Order\Request\Items\Item;

abstract class AbstractItem
{

    /** @var \Magento\Framework\DataObject */
    protected $_data;

    /**
     * abstract to make a source specific call.
     * leave this alone until we need to do custom-mapped line item data
     *
     * @abstract
     * @param $param
     * @return mixed
     */
    //abstract function srcData($param);

    /**
     * pass-through for methods in to source
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    function __call($name, $arguments)
    {
        return call_user_func_array(array($this->getData(),$name),$arguments);
    }

    /**
     * passthrough for getting data
     *
     * @param $name
     * @return mixed
     */
    function __get($name)
    {
        return $this->getData()->$name;
    }

    /**
     * passthrough for setting data.
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    function __set($name, $value)
    {
        return $this->getData()->$name = $value;
    }

    /**
     * set the source data, if it is an array, turn it in to
     * an object for consistent behavior with other uses.
     *
     * @param $data
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $this->_data = new \Magento\Framework\DataObject;
            $this->_data->setData($data);
        } else {
            $this->_data = $data;
        }
    }

    /**
     * get the source data object
     *
     * @return null
     */
    public function getData()
    {
        return $this->_data;
    }
}
