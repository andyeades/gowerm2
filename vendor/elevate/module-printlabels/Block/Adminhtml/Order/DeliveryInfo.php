<?php

namespace Elevate\Delivery\Block\Adminhtml\Order\View;
/**
 * Class DeliveryInfo
 *
 * @category Elevate
 * @package  Elevate\Delivery\Block\Adminhtml\Order\View
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class DeliveryInfo extends \Magento\Backend\Block\Template
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;


    /**
     * @var \Elevate\PrintLabels\Helper\Data $helper,
     */
    protected $helper;

    /**
     * @var \Elevate\PrintLabels\Helper\OrderData $orderHelper,
     */
    protected $orderHelper;



    /**
     * Index constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                    $context
     * @param \Elevate\PrintLabels\Helper\Data                           $helper
     * @param \Elevate\PrintLabels\Helper\OrderData                      $orderHelper
     * @param \Magento\Framework\Registry $registry
     * @param array                                                      $data
     *

     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Elevate\PrintLabels\Helper\Data $helper,
        \Elevate\PrintLabels\Helper\OrderData $orderHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->orderHelper = $orderHelper;
        $this->_coreRegistry = $registry;
    }

    public function getDeliveryDateSelectedInfo()
    {
        $order = $this->getOrder();
        $delivery_date_selected = $order->getDeliveryDateSelected();
        return $delivery_date_selected;
    }
    /**
     * Retrieve order model
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('sales_order');
    }

}
