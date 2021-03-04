<?php

namespace Elevate\Themeoptions\Model\Config\Source;

class Textalign implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Return list of Theme Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {

        return array(
          array(
              'value' => 'left',
              'label' => 'Left'
          ),
            array(
                'value' => 'right',
                'label' => 'Right'
            ),
            array(
                'value' => 'center',
                'label' => 'Center'
            ),
            array(
                'value' => 'justify',
                'label' => 'Justify'
            ),
            array(
                'value' => 'inherit',
                'label' => 'Inherit'
            ),
        );
    }
}