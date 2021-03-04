<?php

/**
 * used for looping through data sets specialty objects.
 */

namespace Punchout2go\Purchaseorder\Model\Order\Request;

class Items
    implements \Iterator,\Countable
{

    protected $_position;
    protected $_className;
    protected $_data;
    protected $_dataObjectFactory;

    /**
     * Items constructor.
     * @param \Magento\Framework\DataObject $dataObjectFactory
     */
    public function __construct(
        \Magento\Framework\DataObject $dataObjectFactory
    )
    {
        $this->_position = 0;
        $this->_className = '\Magento\Framework\DataObject';
        $this->_data = array();
        $this->_dataObjectFactory = $dataObjectFactory;
    }

    /**
     * @return $this
     */
    public function current()
    {
        if ($this->valid()) {
            //$class = $this->_dataObjectFactory;
            $class = $this->_data[$this->key()];
            return $class;
        }
        return $this->rewind();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_data);
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->_position;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     * increment position counter
     */
    public function next()
    {
        $this->_position++;
    }

    /**
     * reset position to zero
     */
    public function rewind()
    {
        $this->_position = 0;
    }

    /**
     * @param \Magento\Framework\DataObject $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @param string $class_name
     */
    public function setClassName($className)
    {
        $this->_className = $className;
    }

    /**
     * @param $position
     */
    public function setPosition($position)
    {
        $this->_position = $position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if (isset($this->_data[$this->key()])) {
            return true;
        }
        return false;
    }
}