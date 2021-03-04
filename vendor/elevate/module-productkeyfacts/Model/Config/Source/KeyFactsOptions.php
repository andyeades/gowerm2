<?php

namespace Elevate\ProductKeyFacts\Model\Config\Source;

use Magento\Framework\DB\Ddl\Table;

/**
 * Class KeyFactsOptions
 * @package Elevate\ProductKeyFacts\Model\Config\Source;
 */
class KeyFactsOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('Select Option'), 'value' => ''],
            ['label' => __('Short - example: (11.4g BCAA)'), 'value' => 'Short'],
            ['label' => __('Long - example: (Used by Athletes to increase testosterone)'), 'value' => 'Long']
        ];
        return $this->_options;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default'  => null,
                'extra'    => null,
                'type'     => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment'  => 'Key Facts  ' . $attributeCode . ' column',
            ],
        ];
    }
}