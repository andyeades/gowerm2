<?php

namespace Elevate\PrintLabels\Model\Config\Source;

use \Magento\Framework\Data\OptionSourceInterface;

/**
 * Class DaysOfWeek
 *
 * @package Elevate\PrintLabels\Model\Config\Source
 */
class DaysOfWeek implements OptionSourceInterface {

    protected $daysOfWeek = array(
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '0' => 'Sunday'
    );

    /**
     * Return list
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray() {

        $options = array();

        foreach ($this->daysOfWeek as $id => $dayofweek) :
            $options[] = array(
                'value' => $id,
                'label' => $dayofweek
            );
        endforeach;

        return $options;
    }

}
