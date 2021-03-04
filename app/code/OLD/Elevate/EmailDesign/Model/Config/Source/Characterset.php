<?php

namespace Elevate\EmailDesign\Model\Config\Source;
use Magento\Framework\Option\ArrayInterface;
/**
 * Class Characterset
 * @package Elevate\EmailDesign\Model\Config\Source
 */
class Characterset implements ArrayInterface
{
    /**
     * @var array
     */
    protected $_charsets = array(
        'cyrillic' => 'Cyrillic',
        'cyrillic-ext' => 'Cyrillic Extended',
        'greek' => 'Greek',
        'greek-ext' => 'Greek Extended',
        'khmer' => 'Khmer',
        'latin' => 'Latin',
        'latin-ext' => 'Latin Extende',
        'vietnamese' => 'Vietnamese',
    );
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        foreach ($this->_charsets as $id => $charset) :
            $options[] = array(
                'value' => $id,
                'label' => $charset
            );
        endforeach;
        return $options;
    }
}