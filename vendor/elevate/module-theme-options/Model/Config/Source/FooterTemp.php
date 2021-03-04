<?php

namespace Elevate\Themeoptions\Model\Config\Source;

class FooterTemp implements \Magento\Framework\Data\OptionSourceInterface
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
              'value' => 'crucialfitness',
              'label' => 'Crucial Fitness'
          ),
            array(
                'value' => 'posturite',
                'label' => 'Posturite'
            ),
            array(
                'value' => 'happybeds',
                'label' => 'Happy Beds'
            ),
            array(
                'value' => 'handlesfordoors',
                'label' => 'Handles For Doors'
            ),
            array(
                'value' => 'gowercottage',
                'label' => 'Gower Cottage'
            ),
            array(
                'value' => 'misc',
                'label' => 'misc'
            ),
        );
    }
}