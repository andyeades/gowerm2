<?php

namespace Elevate\PrintLabels\Block\Adminhtml\Index;

class Index extends \Magento\Backend\Block\Widget\Container
{

    /**
     * @var \Elevate\PrintLabels\Helper\Data $helper,
     */
    protected $helper;

    /**
     * @var \Elevate\PrintLabels\Helper\OrderData $orderHelper,
     */
    protected $orderHelper;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $backendUrl;
    /**
     * Index constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context                      $context
     * @param \Elevate\PrintLabels\Helper\Data                           $helper
     * @param \Elevate\PrintLabels\Helper\OrderData                      $orderHelper
     * @param \Magento\Backend\Model\UrlInterface                        $backendUrl
     * @param array                                                      $data
     *

     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Elevate\PrintLabels\Helper\Data $helper,
        \Elevate\PrintLabels\Helper\OrderData $orderHelper,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->orderHelper = $orderHelper;
        $this->backendUrl = $backendUrl;
    }

    public function getAdminUrl($params = null)
    {
        if (!isset($params)) {
            $params = [];
        }
        return $this->backendUrl->getUrl('printlabels/index/index', $params);
    }

    public function getTheUrl($pageno)
    {
        return $this->backendUrl->getUrl('printlabels/index/index', ['page' => $pageno]);
    }

    public function getOrders()
    {
        return $this->orderHelper->getOrders();
    }

    public function getOrdersByPage($pagenumber)
    {
        return $this->orderHelper->getOrdersByPage($pagenumber);
    }

    public function getOrdersByDeliveryDate($pagenumber, $date)
    {
        return $this->orderHelper->getOrdersByDeliveryDate($pagenumber, $date);
    }

    public function getParams()
    {
        $params = $this->getRequest()->getParams();
        return $params;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_PrintLabels::index');
    }
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
