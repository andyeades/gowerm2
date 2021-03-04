<?php

namespace Elevate\LandingPages\Block;

use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;
use Magento\Catalog\Helper\Product\ProductList;

/**
 * Class Navigation
 * @package WeltPixel\LayeredNavigation\Block
 */
class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{
    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Catalog\Model\Layer\FilterList
     */
    protected $filterList;

    /**
     * @var \Magento\Catalog\Model\Layer\AvailabilityFlagInterface
     */
    protected $visibilityFlag;

    /**
     * @var ProductList
     */
    protected $_productListHelper;

    /**
     * Default Order field
     *
     * @var string
     */
    protected $_orderField = null;

    /**
     * Default direction
     *
     * @var string
     */
    protected $_direction = ProductList::DEFAULT_SORT_DIRECTION;
    protected $_coreRegistry = null;
    /**
     * @var \Elevate\Firmness\Helper\Data
     */
    protected $_firmnessHelper;

    protected $_registry;
    /**
     * Navigation constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag
     * @param \Elevate\Firmness\Helper\Data $firmnessHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
        \Elevate\LandingPages\Firmness\Helper\Data $firmnessHelper,
        \Magento\Framework\Registry $coreRegistry,

        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->_coreRegistry = $coreRegistry;
        $this->visibilityFlag = $visibilityFlag;
        $this->_firmnessHelper = $firmnessHelper;
        $this->_registry = $registry;

        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
    }

    public function getCurrentCategory(){

        $category = $this->_registry->registry('current_category');//get current category

        return $category;
    }
public function getFirmnessHelper(){

        return $this->_firmnessHelper;
}
public function andy(){

        return "andy";
}
public function isLandingPage(){

    $is_landing_page = $this->_coreRegistry->registry('elevate_landingpage');

    if($is_landing_page){
        return true;
    }
    return false;
}
    public function getLandingPageData(){
        return $this->_coreRegistry->registry('elevate_landingpage_data');
    }


}