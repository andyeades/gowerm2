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

public function getFilterOverrides(){

//http://m2.happybeds.co.uk/mattresses/orthopaedic?mattress_firmness=9088
//http://m2.happybeds.co.uk/mattresses/orthopaedic?mattress_firmness=9078

$filter_override['mattress_firmness']['label']['-1']['name'] = ['Soft'];
$filter_override['mattress_firmness']['label']['0']['name'] = ['Soft'];
$filter_override['mattress_firmness']['label']['1']['name'] = ['Soft'];
$filter_override['mattress_firmness']['label']['2']['name'] = ['Soft'];

$filter_override['mattress_firmness']['label']['3']['name'] = ['Soft', 'Soft / Medium'];

$filter_override['mattress_firmness']['label']['4']['name'] = ['Soft / Medium'];
$filter_override['mattress_firmness']['label']['5']['name'] = ['Soft / Medium'];
$filter_override['mattress_firmness']['label']['6']['name'] = ['Soft / Medium'];

$filter_override['mattress_firmness']['label']['7']['name'] = ['Soft / Medium', 'Medium'];

$filter_override['mattress_firmness']['label']['8']['name'] = ['Medium'];
$filter_override['mattress_firmness']['label']['9']['name'] = ['Medium'];
$filter_override['mattress_firmness']['label']['10']['name'] = ['Medium'];
$filter_override['mattress_firmness']['label']['11']['name'] = ['Medium', 'Medium / Firm'];

$filter_override['mattress_firmness']['label']['12']['name'] = ['Medium / Firm'];
$filter_override['mattress_firmness']['label']['13']['name'] = ['Medium / Firm'];
$filter_override['mattress_firmness']['label']['14']['name'] = ['Medium / Firm'];
$filter_override['mattress_firmness']['label']['15']['name'] = ['Medium / Firm', 'Firm'];

$filter_override['mattress_firmness']['label']['16']['name'] = ['Firm'];
$filter_override['mattress_firmness']['label']['17']['name'] = ['Firm'];
$filter_override['mattress_firmness']['label']['18']['name'] = ['Firm'];
$filter_override['mattress_firmness']['label']['19']['name'] = ['Firm'];

$filter_override['mattress_firmness']['url']['Soft'] = 'soft';
$filter_override['mattress_firmness']['url']['Soft \ Medium'] = 'soft_medium';
$filter_override['mattress_firmness']['url']['Medium'] = 'medium';
$filter_override['mattress_firmness']['url']['Medium Firm'] = 'medium_firm';
$filter_override['mattress_firmness']['url']['Firm'] = 'firm';
return $filter_override;
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