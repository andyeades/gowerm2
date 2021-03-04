<?php

namespace Elevate\LandingPages\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class AjaxDescriptionType implements \Magento\Framework\Data\OptionSourceInterface

{

    /**
     * Return list of Search Versions
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(

            array(
                'value' => 'onload_default_ajax_no',
                'label' => 'Onload = Default Description, Ajax = No Description',
            ),
            array(
                'value' => 'onload_default_ajax_default',
                'label' => 'Onload = Default Description, Ajax = Default Description',
            ),
            array(
                'value' => 'onload_default_ajax_ajax',
                'label' => 'Onload = Default Description, Ajax = Ajax Description',
            ),
            array(
                'value' => 'onload_no_ajax_ajax',
                'label' => 'Onload = No Description, Ajax = Ajax Description',
            ),
            array(
                'value' => 'none',
                'label' => 'No Description at all',
            )
            
        );
        
        
        
    }
    
    
}