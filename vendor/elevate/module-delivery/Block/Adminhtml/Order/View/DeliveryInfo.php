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
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $salesOrderResourceModel;

    /**
     * @var \Elevate\PrintLabels\Helper\Data
     */
    protected $helper;

    /**
     * @var \Elevate\PrintLabels\Helper\OrderData
     */
    protected $orderHelper;



    /**
     * Index constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                    $context
     * @param \Elevate\PrintLabels\Helper\Data                           $helper
     * @param \Elevate\PrintLabels\Helper\OrderData                      $orderHelper
     * @param \Magento\Sales\Model\ResourceModel\Order $salesOrderResourceModel
     * @param \Magento\Framework\Registry $registry
     * @param array                                                      $data
     *

     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Elevate\PrintLabels\Helper\Data $helper,
        \Elevate\PrintLabels\Helper\OrderData $orderHelper,
        \Magento\Sales\Model\ResourceModel\Order $salesOrderResourceModel,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->orderHelper = $orderHelper;
        $this->salesOrderResourceModel = $salesOrderResourceModel;
        $this->_coreRegistry = $registry;
    }

    public function getOrderId()
    {
        $order = $this->getOrder();


        return $order->getIncrementId();
    }

    public function getDeliveryDateSelected()
    {
        $order = $this->getOrder();
        $delivery_date_selected = $order->getDeliveryDateSelected();

        return $delivery_date_selected;
    }

    public function getDetailedDeliveryInfoDates()
    {
        $order = $this->getOrder();

        return $order->getDetailedDeliveryInfoDates();
    }

    public function getDetailedDeliveryTeamnumber()
    {
        $order = $this->getOrder();

        return $order->getDetailedDeliveryTeamnumber();
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
