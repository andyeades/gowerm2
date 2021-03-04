<?php


namespace Elevate\EmailDesign\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class FontWeight
 * @package Elevate\EmailDesign\Model\Config\Source
 */
class FontWeight implements ArrayInterface
{
    /**
     * @var array
     */
    protected $_weights = array(
        'normal' => 'Normal (default)',
        'inherit' => 'Inherit (from its parent)',
        'initial' => 'Initial (default value)',
        'bold' => 'Bold',
        'bolder' => 'Bolder',
        'lighter' => 'Lighter',
        '300' => '300',
        '400' => '400',
        '600' => '600',
        '700' => '700',
        '800' => '800',
        '900' => '900',

    );

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        foreach ($this->_weights as $id => $weight) :
            $options[] = array(
                'value' => $id,
                'label' => $weight
            );
        endforeach;
        return $options;
    }
}