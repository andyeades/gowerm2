<?php

namespace Elevate\Themeoptions\Model\Config\Source;

class Fontweight implements \Magento\Framework\Data\OptionSourceInterface
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
              'value' => '300',
              'label' => '300'
          ),
            array(
                'value' => '400',
                'label' => '400'
            ),
            array(
                'value' => '500',
                'label' => '500'
            ),
            array(
                'value' => '600',
                'label' => '600'
            ),
            array(
                'value' => '700',
                'label' => '700'
            ),
            array(
                'value' => '800',
                'label' => '800'
            ),
        );
    }
}