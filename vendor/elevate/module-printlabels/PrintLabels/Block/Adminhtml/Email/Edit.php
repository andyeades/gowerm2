<?php
/**
 * Copyright © MagePal LLC. All rights reserved.
 * See COPYING.txt for license details.
 * http://www.magepal.com | support@magepal.com
 */

namespace Elevate\PrintLabels\Block\Adminhtml\Email;

use \Elevate\PrintLabels\Helper\Data;
use \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation;

class Edit extends \Magento\Backend\Block\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $authorization;

    /**
     * @var \Elevate\PrintLabels\Helper\Data  $helper
     */
    protected $helper;

    /**
     * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
     */
    protected $dpdAuthorisation;
    /**
     * Edit constructor.
     *
     * @param \Magento\Backend\Block\Template\Context   $context
     * @param \Magento\Framework\Registry               $coreRegistry
     * @param \Magento\Framework\AuthorizationInterface $authorization
     * @param \Elevate\PrintLabels\Helper\Data          $helper
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
     * @param array                                     $data
     */

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\AuthorizationInterface $authorization,
        \Elevate\PrintLabels\Helper\Data $helper,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $coreRegistry;
        $this->authorization = $authorization;
        $this->helper = $helper;
        $this->dpdAuthorisation = $dpdAuthorisation;
    }

    /**
     * @return string
     */
    public function getAdminPostUrl()
    {
        return $this->getUrl('printlabels/edit/index');
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->getRequest()->getParam('order_id');
    }

    public function getEmailAddress()
    {
        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        if ($order = $this->coreRegistry->registry('sales_order')) {
            return $order->getCustomerEmail();
        }

        return '';
    }
    public function getOrder()
    {
        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        if ($order = $this->coreRegistry->registry('sales_order')) {
            return $order;
        }

        return '';
    }

    /**
     * @return DPDAuthorisation
     */
    public function getDpdAuthorisation(): DPDAuthorisation {
        return $this->dpdAuthorisation;
    }

    /**
     * @return Data
     */
    public function getHelper(): Data {
        return $this->helper;
    }

    protected function _toHtml()
    {
        if (!$this->_authorization->isAllowed('Elevate_PrintLabels::elevate_printlabels')) {
          //  return '';
        }

        return parent::_toHtml();
    }
}
