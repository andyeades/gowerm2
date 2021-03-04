<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\Template\Option;

use Aitoc\OptionsManagement\Model\Template\Option;
use Magento\Framework\Model\AbstractModel;

/**
 * Template option select type model
 */
class Value extends AbstractModel implements \Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface
{
    /**
     * Option type percent
     */
    const TYPE_PERCENT = 'percent';

    /**
     * @var array
     */
    protected $_values = [];

    /**
     * @var Option
     */
    protected $_option;

    /**
     * Value collection factory
     *
     * @var \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\CollectionFactory
     */
    protected $_valueCollectionFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\CollectionFactory $valueCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\CollectionFactory $valueCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_valueCollectionFactory = $valueCollectionFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionTypeId()
    {
        return $this->_getData(self::OPTION_TYPE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionTypeId($optionTypeId)
    {
        return $this->setData(self::OPTION_TYPE_ID, $optionTypeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriceType()
    {
        return $this->getData(self::PRICE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPriceType($priceType)
    {
        return $this->setData(self::PRICE_TYPE, $priceType);
    }

    // ------------------- old code --------------------------------



    /**
     * @codeCoverageIgnoreStart
     * @param mixed $value
     * @return $this
     */
    public function addValue($value)
    {
        $this->_values[] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues($values)
    {
        $this->_values = $values;
        return $this;
    }

    /**
     * @return $this
     */
    public function unsetValues()
    {
        $this->_values = [];
        return $this;
    }

    /**
     * @param Option $option
     * @return $this
     */
    public function setOption(Option $option)
    {
        $this->_option = $option;
        return $this;
    }

    /**
     * @return $this
     */
    public function unsetOption()
    {
        $this->_option = null;
        return $this;
    }

    /**
     * Enter description here...
     *
     * @return Option
     */
    public function getOption()
    {
        return $this->_option;
    }

    /**
     * @return $this
     */
    public function saveValues()
    {
        foreach ($this->getValues() as $value) {
            $this->setData(
                $value
            )->setData(
                'option_id',
                $this->getOption()->getId()
            )->setData(
                'store_id',
                $this->getOption()->getStoreId()
            );

            if ($this->getData('is_delete') == '1') {
                if ($this->getId()) {
                    $this->deleteValues($this->getId());
                    $this->delete();
                }
            } else {
                $this->save();
            }
        }
        //eof foreach()
        return $this;
    }

    /**
     * Enter description here...
     *
     * @param Option $option
     * @return \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\Collection
     */
    public function getValuesCollection(Option $option)
    {
        $collection = $this->_valueCollectionFactory->create()->addFieldToFilter(
            'option_id',
            $option->getId()
        )->getValues(
            $option->getStoreId()
        );

        return $collection;
    }

    /**
     * @param array $optionIds
     * @param int $option_id
     * @param int $store_id
     * @return \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value\Collection
     */
    public function getValuesByOption($optionIds, $option_id, $store_id)
    {
        $collection = $this->_valueCollectionFactory->create()->addFieldToFilter(
            'option_id',
            $option_id
        )->getValuesByOption(
            $optionIds,
            $store_id
        );

        return $collection;
    }

    /**
     * @param int $option_id
     * @return $this
     */
    public function deleteValue($option_id)
    {
        $this->getResource()->deleteValue($option_id);
        return $this;
    }

    /**
     * @param int $option_type_id
     * @return $this
     */
    public function deleteValues($option_type_id)
    {
        $this->getResource()->deleteValues($option_type_id);
        return $this;
    }

    //@codeCoverageIgnoreEnd
}
