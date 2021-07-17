<?php

namespace Elevate\LandingPages\Block\Navigation;

use Magento\Framework\View\Element\Template;

/**
 * Class FilterRenderer
 * @package WeltPixel\LayeredNavigation\Block\Navigation
 */
class FilterRenderer extends \Magento\LayeredNavigation\Block\Navigation\FilterRenderer
{
    /**
     * @var \WeltPixel\LayeredNavigation\Helper\Data
     */
    protected $_coreRegistry;
    protected $_firmnessRatingHelper;
    
    

                          
    /**
     * FilterRenderer constructor.
     * @param \WeltPixel\LayeredNavigation\Helper\Data $wpHelper
     * @param \WeltPixel\LayeredNavigation\Model\AttributeOptions $attributeOptions
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Elevate\LandingPages\Firmness\Helper\Data $firmnessRatingHelper,
        Template\Context $context,
        array $data = []
    )
    {
         $this->_coreRegistry = $coreRegistry;
        $this->_firmnessRatingHelper = $firmnessRatingHelper;
          
        parent::__construct($context, $data);
    }

    /**
     * @param $filter
     */
    public function getLandingPageAttributes()
    {
             $currentLandingAttributes = $this->_coreRegistry->registry('elevate_landingpage_attributes');                         
             return $currentLandingAttributes;                        
    }


        public function getBodyweightAdjustment()
    {
              $_firmnessRatingHelper = $this->_firmnessRatingHelper->getBodyweightAdjustment();
              return $_firmnessRatingHelper;                     
    }

        
     }        