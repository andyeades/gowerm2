<?php

namespace Elevate\LandingPages\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class NavigationStyle implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => 'vertical',
                'label' => 'Veritcal Navigation on the left',
            ),
            array(
                'value' => 'horizontal',
                'label' => 'Horizontal navigation at the top',
            )
            
        );
        
        
        
    }
    
    
}