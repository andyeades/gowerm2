<?php

namespace Elevate\Themeoptions\Model\Config\Source;

class Csscolortypemodification implements \Magento\Framework\Data\OptionSourceInterface
{

    protected $helper;
    /**
     * @param \Elevate\Themeoptions\Helper\General $helper
     *
     */
    public function __construct(
        \Elevate\Themeoptions\Helper\General $helper
    ) {
        $this->helper = $helper;
    }
    /**
     * Return list of Color Modification Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {

        $output = array(
            array(
                'value' => 'darken',
                'label' => 'Darken Color'
            ),
            array(
                'value' => 'lighten',
                'label' => 'Lighten Color'
            )
        );

        return $output;
    }
}