<?php

namespace Elevate\LandingPages\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class IncludeExclude implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => 'include',
                'label' => 'Make custom sort Include Attributes',
            ),
            array(
                'value' => 'exclude',
                'label' => 'Make custom sort Exclude Attributes',
            )
            
        );
        
        
        
    }
    
    
}