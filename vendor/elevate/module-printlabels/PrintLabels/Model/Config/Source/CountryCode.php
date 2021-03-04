<?php

namespace Elevate\PrintLabels\Model\Config\Source;

use \Magento\Framework\Data\OptionSourceInterface;

/**
 * Class CountryCode
 *
 * @package Elevate\PrintLabels\Model\Config\Source
 */
class CountryCode implements OptionSourceInterface {

    protected $countrycodes = array(
        'GB' => 'GB - (United Kingdom)'
    );

    /**
     * Return list
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray() {

        $options = array();

        foreach ($this->countrycodes as $id => $countrycode) :
            $options[] = array(
                'value' => $id,
                'label' => $countrycode
            );
        endforeach;

        return $options;
    }

}
