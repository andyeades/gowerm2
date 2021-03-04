<?php

namespace Elevate\LandingPages\Model\ResourceModel;

/**
 * Class AttributeOptions
 * @package Elevate\LandingPages\Model\ResourceModel
 */
class AttributeOptions extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * AttributeOptions constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('elevate_ln_attribute_options', 'id');
    }

    /**
     * Load Attribute display option by attribute_id & store_id
     *
     * @param \Elevate\LandingPages\Model\AttributeOptions $attributeOptions
     * @param $attributeId
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadDisplayOptionsByAttribute(
        \Elevate\LandingPages\Model\AttributeOptions $attributeOptions,
        $attributeId
    ) {
        $connection = $this->getConnection();
        $bind = [
            'attribute_id' => $attributeId
        ];

        $select = $connection->select()->from(
            $this->getMainTable(),
            [$this->getIdFieldName(), 'attribute_id', 'display_option', 'visible_options', 'visible_options_step']
        )->where(
            'attribute_id = :attribute_id'
        );

        
        $lineId = $connection->fetchOne($select, $bind);
        if ($lineId) {
            $this->load($attributeOptions, $lineId);
        } else {
            $attributeOptions->setData([]);
        }
    }

}
