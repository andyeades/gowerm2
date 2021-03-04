<?php
/**
 * @category  Apptrian
 * @package   Apptrian_FacebookPixel
 * @author    Apptrian
 * @copyright Copyright (c) Apptrian (http://www.apptrian.com)
 * @license   http://www.apptrian.com/license Proprietary Software License EULA
 */

namespace Apptrian\FacebookPixel\Block;

class Code extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Apptrian\FacebookPixel\Helper\Data
     */
    public $helper;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Apptrian\FacebookPixel\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Apptrian\FacebookPixel\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        
        parent::__construct($context, $data);
    }
    
    /**
     * Used in .phtml file and returns array of data.
     *
     * @return array
     */
    public function getFacebookPixelData()
    {
        $data = [];
        
        $data['id_data']               = $this->helper->getFacebookPixelId();
        $data['full_action_name']      = $this->getRequest()->getFullActionName();
        $data['page_handles']          = $this->helper->getPageHandles();
        $data['page_handles_category'] = $this->helper->getPageHandles('category');
        $data['page_handles_product']  = $this->helper->getPageHandles('product');
        $data['page_handles_quote']    = $this->helper->getPageHandles('quote');
        $data['page_handles_order']    = $this->helper->getPageHandles('order');
        $data['page_handles_search']   = $this->helper->getPageHandles('search');
    
        return $data;
    }
    
    /**
     * Returns customer data needed for advanced matching.
     *
     * @return array
     */
    public function getCustomerData()
    {
        return $this->helper->getUserDataForClient();
    }
    
    /**
     * Returns category data needed for tracking.
     *
     * @return array
     */
    public function getCategoryData()
    {
        return $this->helper->getCategoryDataForClient();
    }
    
    /**
     * Returns product data needed for tracking.
     *
     * @return array
     */
    public function getProductData($id = 0)
    {
        return $this->helper->getProductData($id);
    }
    
    /**
     * Returns data needed for tracking from order object.
     *
     * @return array
     */
    public function getOrderData()
    {
        return $this->helper->getOrderDataForClient();
    }
    
    /**
     * Returns data needed for tracking from quote object.
     *
     * @return array
     */
    public function getQuoteData()
    {
        return $this->helper->getQuoteDataForClient();
    }
    
    /**
     * Returns search data needed for tracking.
     *
     * @return array
     */
    public function getSearchData()
    {
        return $this->helper->getSearchDataForClient();
    }
    
    /**
     * Returns configuration value for event.
     *
     * @return bool
     */
    public function isEventEnabled($event, $server = false)
    {
        return $this->helper->isEventEnabled($event, $server);
    }
    
    /**
     * Returns configuration value for moving params outside contents.
     *
     * @return int
     */
    public function isMoveParamsOutsideContentsEnabled($server = false)
    {
        return $this->helper->isMoveParamsOutsideContentsEnabled($server);
    }
    
    /**
     * Returns configuration value for detect_selected_sku
     *
     * @return bool
     */
    public function isDetectSelectedSkuEnabled($productType, $server = false)
    {
        return $this->helper->isDetectSelectedSkuEnabled($productType, $server);
    }
    
    /**
     * Returns price decimal sign
     *
     * @return string
     */
    public function getPriceDecimalSymbol()
    {
        return $this->helper->getPriceDecimalSymbol();
    }
    
    /**
     * Returns flag based on "Stores > Cofiguration > Sales > Tax
     * > Price Display Settings > Display Product Prices In Catalog"
     * Returns 0 or 1 instead of 1, 2, 3.
     *
     * @return int
     */
    public function getDisplayTaxFlag()
    {
        return $this->helper->getDisplayTaxFlag();
    }
    
    /**
     * Returns data for CompleteRegistration event.
     *
     * @param int $customerId
     * @return array
     */
    public function getDataForCompleteRegistrationEvent($customerId = 0)
    {
        return $this->helper->getDataForClientCompleteRegistrationEvent($customerId);
    }
}
